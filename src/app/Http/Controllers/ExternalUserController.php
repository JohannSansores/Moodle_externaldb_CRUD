<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExternalUserRequest;
use App\Http\Requests\UpdateExternalUserRequest;
use App\Models\Catalogo;
use App\Models\UsuarioExterno;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExternalUserController extends Controller
{
    private function catalogos(): array
    {
        return [
            'dependencias' => Catalogo::tabla('cat_dependencias')->orderBy('nombre')->get(),
            'programas'    => Catalogo::tabla('cat_programas')->orderBy('nombre')->get(),
            'roles'        => Catalogo::tabla('cat_roles')->orderBy('nombre')->get(),
            'semestres'    => Catalogo::tabla('cat_semestres')->orderBy('nombre')->get(),
        ];
    }

    public function index(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $curp = $request->query('curp');
        $dependencia = $request->query('dependencia');
        $programa = $request->query('programa');
        $rol = $request->query('rol');
        $semestre = $request->query('semestre');

        $catalogos = $this->catalogos();

        $usersQuery = DB::table('vw_usuarios_moodle')
            ->when($fromDate, fn ($query, $fromDate) => $query->whereDate('created_at', '>=', $fromDate))
            ->when($toDate, fn ($query, $toDate) => $query->whereDate('created_at', '<=', $toDate))
            ->when($curp, fn ($query, $curp) => $query->where('curp', 'like', "%{$curp}%"))
            ->when($dependencia, fn ($query, $dependencia) => $query->where('id_dependencia', $dependencia))
            ->when($programa, fn ($query, $programa) => $query->where('id_programa', $programa))
            ->when($rol, fn ($query, $rol) => $query->where('id_rol', $rol))
            ->when($semestre, fn ($query, $semestre) => $query->where('id_semestre', $semestre));

        $users = $usersQuery->paginate(15)->withQueryString();
        $usersNumber = (clone $usersQuery)->count();

        return view('dashboard', array_merge(
            compact('users', 'usersNumber', 'fromDate', 'toDate', 'curp', 'dependencia', 'programa', 'rol', 'semestre'),
            [
                'dependencias' => $catalogos['dependencias']->pluck('nombre', 'id'),
                'programas'    => $catalogos['programas']->pluck('nombre', 'id'),
                'roles'        => $catalogos['roles']->pluck('nombre', 'id'),
                'semestres'    => $catalogos['semestres']->pluck('nombre', 'id'),
            ]
        ));
    }

    public function create()
    {
        return view('external_users.create', $this->catalogos());
    }

    public function store(StoreExternalUserRequest $request)
    {
        UsuarioExterno::create($request->validated());
        Cache::forget('dashboard_users_count');

        return redirect()->route('dashboard')
            ->with('status', 'Usuario externo creado exitosamente.');
    }

    public function edit(string $id)
    {
        return view('external_users.edit', array_merge(
            ['user' => UsuarioExterno::findOrFail($id)],
            $this->catalogos()
        ));
    }

    public function update(UpdateExternalUserRequest $request, string $id)
    {
        $user = UsuarioExterno::findOrFail($id);
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        Cache::forget('dashboard_users_count');

        return redirect()->route('dashboard')
            ->with('status', 'Usuario externo actualizado exitosamente.');
    }

    public function destroy(Request $request, string $id)
    {
    DB::transaction(function () use ($id) {
        UsuarioExterno::where('id', $id)->delete();
    });

    Cache::forget('dashboard_users_count');
    Cache::forget('moodle-externaldb-crud-cache-dashboard_users_count');
    Cache::forget('moodle-externaldb-crud-cache-dashboard_users');
    Cache::forget('moodle-externaldb-crud-cache-dashboard');

    if ($request->wantsJson() || $request->ajax()) {
        return response()->noContent(); // 204 — el fetch del dashboard lo detecta como éxito
    }

    return redirect()->route('dashboard')
        ->with('status', 'Base de datos sincronizada y registros actualizados.');
    }

    public function bulkDestroy(Request $request)
    {
    $request->validate([
        'selected_users'   => 'required|array',
        'selected_users.*' => 'integer|distinct|min:1',
    ]);

    $ids = $request->input('selected_users');

    $deletedCount = DB::transaction(function () use ($ids) {
        return UsuarioExterno::whereIn('id', $ids)->delete();
    });

    Cache::forget('dashboard_users_count');
    Cache::forget('moodle-externaldb-crud-cache-dashboard_users_count');
    Cache::forget('moodle-externaldb-crud-cache-dashboard_users');
    Cache::forget('moodle-externaldb-crud-cache-dashboard');

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json(['deleted' => $deletedCount]);
    }

    return redirect()->route('dashboard')
        ->with('status', $deletedCount . ' usuario(s) eliminado(s) correctamente.');
    }

    // ─── Importación CSV ─────────────────────────────────────────────────────

    public function importForm()
    {
        return view('external_users.import');
    }

    public function importTemplate()
    {
        $callback = function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['username', 'password', 'firstname', 'lastname', 'email', 'curp', 'dependencia', 'programa', 'rol', 'semestre']);
            fputcsv($handle, ['jperez2024', 'MiClave123', 'Juan', 'Pérez', 'jperez@mail.com', 'PERJ900101HDFRZN09', 'Nombre dependencia', 'Nombre programa', 'Nombre rol', 'Nombre semestre']);
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_usuarios.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv_file.required' => 'Debes seleccionar un archivo CSV.',
            'csv_file.mimes'    => 'El archivo debe ser de tipo CSV.',
            'csv_file.max'      => 'El archivo no debe superar 2 MB.',
        ]);

        $dependencias = Catalogo::tabla('cat_dependencias')->get()->keyBy(fn($r) => strtolower(trim($r->nombre)));
        $programas    = Catalogo::tabla('cat_programas')->get()->keyBy(fn($r) => strtolower(trim($r->nombre)));
        $roles        = Catalogo::tabla('cat_roles')->get()->keyBy(fn($r) => strtolower(trim($r->nombre)));
        $semestres    = Catalogo::tabla('cat_semestres')->get()->keyBy(fn($r) => strtolower(trim($r->nombre)));

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (!$header) {
            return back()->withErrors(['csv_file' => 'El archivo CSV está vacío o tiene un formato incorrecto.']);
        }

        $header    = array_map(fn($h) => strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h))), $header);
        $faltantes = array_diff(['username', 'password', 'firstname', 'lastname', 'email', 'dependencia', 'programa', 'rol', 'semestre'], $header);

        if (!empty($faltantes)) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'Faltan columnas en el CSV: ' . implode(', ', $faltantes)]);
        }

        $insertados = 0;
        $errores    = [];
        $fila       = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $fila++;

            if (count($row) !== count($header)) {
                $errores[] = "Fila {$fila}: número de columnas incorrecto.";
                continue;
            }

            $datos = array_map('trim', array_combine($header, $row));

            if (empty($datos['username']) || empty($datos['password']) || empty($datos['email'])) {
                $errores[] = "Fila {$fila}: username, password y email son obligatorios.";
                continue;
            }

            if (strlen($datos['password']) < 8) {
                $errores[] = "Fila {$fila}: la contraseña debe tener mínimo 8 caracteres.";
                continue;
            }

            if (UsuarioExterno::where('username', $datos['username'])->exists()) {
                $errores[] = "Fila {$fila}: el usuario '{$datos['username']}' ya existe, se omitió.";
                continue;
            }

            if (!empty($datos['email']) && UsuarioExterno::where('email', $datos['email'])->exists()) {
                $errores[] = "Fila {$fila}: el correo '{$datos['email']}' ya está registrado, se omitió.";
                continue;
            }

            $curp = strtoupper($datos['curp'] ?? '');
            if (!empty($curp)) {
                if (strlen($curp) !== 18) {
                    $errores[] = "Fila {$fila}: la CURP debe tener 18 caracteres.";
                    continue;
                }
                if (UsuarioExterno::where('curp', $curp)->exists()) {
                    $errores[] = "Fila {$fila}: la CURP '{$curp}' ya está registrada, se omitió.";
                    continue;
                }
            }

            $depKey  = strtolower($datos['dependencia']);
            $progKey = strtolower($datos['programa']);
            $rolKey  = strtolower($datos['rol']);
            $semKey  = strtolower($datos['semestre']);

            if (!isset($dependencias[$depKey])) { $errores[] = "Fila {$fila}: dependencia '{$datos['dependencia']}' no encontrada."; continue; }
            if (!isset($programas[$progKey]))    { $errores[] = "Fila {$fila}: programa '{$datos['programa']}' no encontrado.";       continue; }
            if (!isset($roles[$rolKey]))         { $errores[] = "Fila {$fila}: rol '{$datos['rol']}' no encontrado.";                 continue; }
            if (!isset($semestres[$semKey]))     { $errores[] = "Fila {$fila}: semestre '{$datos['semestre']}' no encontrado.";       continue; }

            UsuarioExterno::create([
                'username'       => $datos['username'],
                'password'       => $datos['password'],
                'firstname'      => $datos['firstname'],
                'lastname'       => $datos['lastname'],
                'email'          => $datos['email'],
                'curp'           => !empty($curp) ? $curp : null,
                'id_dependencia' => $dependencias[$depKey]->id,
                'id_programa'    => $programas[$progKey]->id,
                'id_rol'         => $roles[$rolKey]->id,
                'id_semestre'    => $semestres[$semKey]->id,
            ]);

            $insertados++;
        }

        fclose($handle);

        return redirect()->route('dashboard')
            ->with('status', "Importación completada: {$insertados} usuario(s) creado(s).")
            ->with('import_errors', $errores);
    }
}
