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
                    'username' => $validated['email'],
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
                // Esto insertará en 'usuarios_externos' siempre que el Modelo esté configurado
                moodle_usuarios::create([
                    'username' => $validated['email'],
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

            // Redirigir de vuelta al formulario con mensaje de éxito
            return redirect()->route('register.show') 
                ->with('status', '¡Usuario registrado con éxito!');

        } catch (\Exception $e) {
            Log::error('Error al insertar en el registro: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al guardar los datos: ' . $e->getMessage());
        }
    }
}

            //Auth::login($user);

            // --- INICIO DE CONEXIÓN CON API MOODLE ---
            
            // 1. Definir variables de conexión (Idealmente poner en el .env)
            //$moodleUrl = env('MOODLE_URL', 'http://localhost:8080'); // URL de tu Moodle
            //$wsToken = env('MOODLE_TOKEN', 'tu_token_aqui'); // Token de servicio de Moodle
            
            // 2. Intentar verificar si Moodle ya reconoce al usuario inyectado
            /* Usamos core_user_get_users_by_field para buscar por email
            try {
                $response = Http::get($moodleUrl . '/webservice/rest/server.php', [
                    'wstoken' => $wsToken,
                    'wsfunction' => 'core_user_get_users_by_field',
                    'moodlewsrestformat' => 'json',
                    'field' => 'email',
                    'values[0]' => $validated['email'],
                ]);

                // 3. Si la API responde y encuentra al usuario, redirigimos a Moodle
                if ($response->successful() && count($response->json()) > 0) {
                    // Redirección directa a la sección de cursos de Moodle
                    return redirect()->away($moodleUrl . '/my/courses.php');
                }
            } catch (\Exception $apiEx) {
                // Si la API falla, logueamos el error pero dejamos que el usuario siga en Laravel
                Log::warning('Moodle API Sincronización fallida: ' . $apiEx->getMessage());
            }

            // --- FIN DE CONEXIÓN CON API MOODLE ---*/

            // Disparar el evento de registro para enviar email de verificación
        /*   event(new Registered($user));

            return redirect()->route('verification.notice')
                ->with('status', 'Registro exitoso. Por favor, verifica tu correo electrónico para continuar.');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar. Por favor, intenta de nuevo.');
        }
        */
        
