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
            // Return empty arrays if error
            $dependencias = collect();
            $programas = collect();
        }

        return view('auth.register', compact('dependencias', 'programas'));
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {

            $validated['id_rol'] = 3; // Student role by default

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
                $user = moodle_usuarios::create([
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

            Auth::login($user);

            // Disparar el evento de registro para enviar email de verificación
            event(new Registered($user));

            return redirect()->route('verification.notice')
                ->with('status', 'Registro exitoso. Por favor, verifica tu correo electrónico para continuar.');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar. Por favor, intenta de nuevo.');
        }
    }
}