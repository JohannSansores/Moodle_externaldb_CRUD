<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExternalUserController extends Controller
{
    public function index()
    {
        $users = DB::table('vw_usuarios_moodle')->get();
        $usersNumber = $users->count();

        return view('dashboard', compact('users', 'usersNumber'));
    }

    public function create()
    {
        return view('external_users.create', [
            'dependencias' => DB::table('cat_dependencias')->orderBy('nombre')->get(),
            'programas'    => DB::table('cat_programas')->orderBy('nombre')->get(),
            'roles'        => DB::table('cat_roles')->orderBy('nombre')->get(),
            'semestres'    => DB::table('cat_semestres')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username'        => 'required|string|max:100|unique:usuarios_externos,username',
            'password'        => 'required|string|min:8',
            'firstname'       => 'required|string|max:100',
            'lastname'        => 'required|string|max:100',
            'email'           => 'required|email|max:150',
            'id_dependencia'  => 'required|integer|exists:cat_dependencias,id',
            'id_programa'     => 'required|integer|exists:cat_programas,id',
            'id_rol'          => 'required|integer|exists:cat_roles,id',
            'id_semestre'     => 'required|integer|exists:cat_semestres,id',
        ]);

        $data['password'] = Hash::make($data['password']);

        DB::table('usuarios_externos')->insert($data);

        return redirect()->route('dashboard')->with('status', 'External user created successfully');
    }

    public function edit(string $id)
    {
        return view('external_users.edit', [
            'user'         => DB::table('usuarios_externos')->find($id),
            'dependencias' => DB::table('cat_dependencias')->orderBy('nombre')->get(),
            'programas'    => DB::table('cat_programas')->orderBy('nombre')->get(),
            'roles'        => DB::table('cat_roles')->orderBy('nombre')->get(),
            'semestres'    => DB::table('cat_semestres')->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'username'        => 'required|string|max:100|unique:usuarios_externos,username,' . $id,
            'firstname'       => 'required|string|max:100',
            'lastname'        => 'required|string|max:100',
            'email'           => 'required|email|max:150',
            'id_dependencia'  => 'required|integer|exists:cat_dependencias,id',
            'id_programa'     => 'required|integer|exists:cat_programas,id',
            'id_rol'          => 'required|integer|exists:cat_roles,id',
            'id_semestre'     => 'required|integer|exists:cat_semestres,id',
            'password'        => 'nullable|string|min:8',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        DB::table('usuarios_externos')
            ->where('id', $id)
            ->update($data);

        return redirect()->route('dashboard')->with('status', 'External user updated successfully');
    }

    public function destroy(string $id)
    {
        DB::table('usuarios_externos')->where('id', $id)->delete();

        return redirect()->route('dashboard')->with('status', 'External user deleted successfully');
    }
}
