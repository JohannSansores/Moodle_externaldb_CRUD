<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public function list()
    {
        $users = DB::connection('mysql')
        ->table('vw_usuarios_moodle')
        ->get();

        $usersNumber = 0;
        return view('dashboard', compact('users', 'usersNumber'));
    }
}
