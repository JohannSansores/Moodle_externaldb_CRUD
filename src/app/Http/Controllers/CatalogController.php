<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    // Map catalog type to table name
    private function getTable($type)
    {
        return match ($type) {
            'dependencias' => 'cat_dependencias',
            'programas'    => 'cat_programas',
            'roles'        => 'cat_roles',
            'semestres'    => 'cat_semestres',
            default        => abort(404),
        };
    }

    // Show catalog page
    public function show($type)
    {
        $tables = [
            'dependencias' => 'Dependencias',
            'programas'    => 'Programas',
            'roles'        => 'Roles',
            'semestres'    => 'Semestres',
        ];

        if (!array_key_exists($type, $tables)) {
            abort(404);
        }

        $items = DB::table($this->getTable($type))->orderBy('nombre')->get();

        return view('catalogs.index', [
            'title' => $tables[$type],
            'type'  => $type,
            'items' => $items,
        ]);
    }

    // Store new catalog item
    public function store(Request $request)
    {
        $request->validate([
            'type'   => 'required',
            'nombre' => 'required|string|max:200'
        ]);

        DB::table($this->getTable($request->type))
            ->insert(['nombre' => $request->nombre]);

        return back()->with('success', 'Elemento creado correctamente.');
    }

    // Update catalog item
    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:200'
        ]);

        DB::table($this->getTable($type))
            ->where('id', $id)
            ->update(['nombre' => $request->nombre]);

        return back()->with('success', 'Elemento actualizado correctamente.');
    }

    // Delete catalog item
    public function destroy($type, $id)
    {
        DB::table($this->getTable($type))
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Elemento eliminado correctamente.');
    }
}
