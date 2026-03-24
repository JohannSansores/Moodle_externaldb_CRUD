<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExternalUserRequest;
use App\Http\Requests\UpdateExternalUserRequest;
use App\Models\Catalogo;
use App\Models\UsuarioExterno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExternalUserController extends Controller
{
    // Carga los 4 catálogos en un solo lugar — usado en create() y edit()
    private function catalogos(): array
    {
        return [
            'dependencias' => Catalogo::tabla('cat_dependencias')->orderBy('nombre')->get(),
            'programas'    => Catalogo::tabla('cat_programas')->orderBy('nombre')->get(),
            'roles'        => Catalogo::tabla('cat_roles')->orderBy('nombre')->get(),
            'semestres'    => Catalogo::tabla('cat_semestres')->orderBy('nombre')->get(),
        ];
    }

    public function index()
    {
        $users       = DB::table('vw_usuarios_moodle')->paginate(15);
        $usersNumber = DB::table('vw_usuarios_moodle')->count();

        return view('dashboard', compact('users', 'usersNumber'));
    }

    public function create()
    {
        return view('external_users.create', $this->catalogos());
    }

    public function store(StoreExternalUserRequest $request)
    {
        UsuarioExterno::create($request->validated());

        return redirect()->route('dashboard')
            ->with('status', 'Usuario externo creado exitosamente.');
    }

    public function edit(string $id)
    {
        $user = UsuarioExterno::findOrFail($id);

        return view('external_users.edit', array_merge(
            ['user' => $user],
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

        return redirect()->route('dashboard')
            ->with('status', 'Usuario externo actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        UsuarioExterno::findOrFail($id)->delete();

        return redirect()->route('dashboard')
            ->with('status', 'Usuario externo eliminado exitosamente.');
    }

    // ─── Importación CSV ─────────────────────────────────────────────────────

    public function importForm()
    {
        return view('external_users.import');
    }

    public function importTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_usuarios.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['username', 'password', 'firstname', 'lastname', 'email', 'dependencia', 'programa', 'rol', 'semestre']);
            fputcsv($handle, ['jperez2024', 'MiClave123', 'Juan', 'Pérez', 'jperez@mail.com', 'Nombre dependencia', 'Nombre programa', 'Nombre rol', 'Nombre semestre']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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
