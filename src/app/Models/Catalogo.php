<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    public $timestamps = false;

    protected $fillable = ['nombre'];

    // Permite instanciar el modelo apuntando a cualquier tabla de catálogo:
    // Catalogo::fromTable('cat_dependencias')->orderBy('nombre')->get()
    public static function fromTable(string $table): self
    {
        $instance = new static();
        $instance->setTable($table);
        return $instance;
    }

    // Devuelve un query builder apuntando a la tabla indicada
    public static function tabla(string $table)
    {
        return (new static())->setTable($table)->newQuery();
    }
}
