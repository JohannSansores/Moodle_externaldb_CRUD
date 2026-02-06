<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // List only admins (exclude superadmin)
        $admins = User::where('role', 'admin')->get();
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // assign admin role
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }

    public function destroy($id)
    {
        // Fetch user manually to avoid binding issues
        $user = User::findOrFail($id);

        if ($user->role === 'superadmin') {
            abort(403, "You can't delete the superadmin.");
        }

        $user->delete();

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
    }
}
