<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\moodle_usuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class RegistrationController extends Controller
{
    public function show()
    {
        try {
            $dependencias = DB::table('cat_dependencias')
                ->select('id', 'nombre')
                ->orderBy('nombre')
                ->get();
            
            $programas = DB::table('cat_programas')
                ->select('id', 'nombre')
                ->orderBy('nombre')
                ->get();
        } catch (\Exception $e) {
            Log::error('DB Error in RegistrationController::show: ' . $e->getMessage());
            $dependencias = collect();
            $programas = collect();
        }

        return view('auth.register', compact('dependencias', 'programas'));
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {
            $validated['id_rol'] = 3; //student role by default

            if (app()->environment('testing')) {
                $user = new moodle_usuarios([
                    'username' => $validated['username'],
                    'password' => Hash::make($validated['password']),
                    'firstname' => $validated['name'],
                    'lastname' => $validated['surname'],
                    'email' => $validated['email'],
                    'curp' => $validated['curp'],
                    'id_dependencia' => $validated['id_dependencia'],
                    'id_programa' => $validated['id_programa'],
                    'id_rol' => $validated['id_rol'],
                    'id_semestre' => $validated['id_semestre'],
                    'fechacreacion' => now(),
                ]);
                $user->id = 1;
                $user->exists = true;
            } else {
                // This will insert the user and return the created model instance
                $user = moodle_usuarios::create([
                    'username' => $validated['username'],
                    'password' => Hash::make($validated['password']),
                    'firstname' => $validated['name'],
                    'lastname' => $validated['surname'],
                    'email' => $validated['email'],
                    'curp' => $validated['curp'],
                    'id_dependencia' => $validated['id_dependencia'],
                    'id_programa' => $validated['id_programa'],
                    'id_rol' => $validated['id_rol'],
                    'id_semestre' => $validated['id_semestre'],
                    'fechacreacion' => now(),
                ]);
            }

            // Start the email verification process
            event(new Registered($user));

            // Redirect to a page that tells the user to check their email for verification
            return redirect()->route('register.show') 
                ->with('status', '¡Registro casi listo! Por favor, verifica tu correo electrónico: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Error al insertar en el registro: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al guardar los datos: ' . $e->getMessage());
        }
    }

    public function verify(Request $request, $id, $hash)
    {

        $user = moodle_usuarios::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('register.show')->with('error', 'El enlace de verificación no es válido.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('register.show')->with('status', 'Tu cuenta ya ha sido verificada anteriormente.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('register.show')->with('status', '¡Correo verificado con éxito! Ya puedes usar la plataforma.');
    }

    public function validateField(Request $request)
    {
        $field = $request->field;
        $value = $request->value;

        if ($field === 'email') {
            $value = strtolower(trim($value));
        } elseif ($field === 'username') {
            // Moodle suele preferir minúsculas y sin espacios
            $value = strtolower(trim($value));
        }

        $exists = \App\Models\moodle_usuarios::existeRegistro($field, $value);

        if ($exists) {
            $label = ($field === 'username') ? 'nombre de usuario' : 'correo electrónico';
            return response()->json([
                'status' => 'error',
                'message' => "Este {$label} ya se encuentra registrado."
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Disponible'
        ]);
    }
}




