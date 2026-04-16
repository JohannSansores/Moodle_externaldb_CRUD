<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DatabaseExportController extends Controller
{
    // Tablas cuyos datos se incluyen en la exportación
    private const TABLAS_CON_DATOS = [
        'cat_dependencias',
        'cat_programas',
        'cat_roles',
        'cat_semestres',
    ];

    public function export()
    {
        $database = DB::connection()->getDatabaseName();
        $fecha    = now()->format('Y-m-d_H-i-s');
        $filename = "estructura_{$database}_{$fecha}.sql";

        $output   = [];
        $output[] = "-- ============================================================";
        $output[] = "-- Exportación de estructura: {$database}";
        $output[] = "-- Generado: " . now()->format('d/m/Y H:i:s');
        $output[] = "-- ============================================================";
        $output[] = "";
        $output[] = "SET FOREIGN_KEY_CHECKS = 0;";
        $output[] = "SET NAMES utf8mb4;";
        $output[] = "";

        $objetos = DB::select("
            SELECT TABLE_NAME, TABLE_TYPE
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = ?
            ORDER BY TABLE_TYPE DESC, TABLE_NAME ASC
        ", [$database]);

        foreach ($objetos as $objeto) {
            $nombre = $objeto->TABLE_NAME;
            $tipo   = $objeto->TABLE_TYPE;

            if ($tipo === 'VIEW') {
                $output[] = "-- ------------------------------------------------------------";
                $output[] = "-- Vista: {$nombre}";
                $output[] = "-- ------------------------------------------------------------";
                $output[] = "DROP VIEW IF EXISTS `{$nombre}`;";
                $row = DB::select("SHOW CREATE VIEW `{$nombre}`")[0];
                $output[] = $row->{'Create View'} . ";";

            } else {
                $output[] = "-- ------------------------------------------------------------";
                $output[] = "-- Tabla: {$nombre}";
                $output[] = "-- ------------------------------------------------------------";
                $output[] = "DROP TABLE IF EXISTS `{$nombre}`;";
                $row = DB::select("SHOW CREATE TABLE `{$nombre}`")[0];
                $output[] = $row->{'Create Table'} . ";";

                // Exportar datos solo para tablas de catálogo
                if (in_array($nombre, self::TABLAS_CON_DATOS)) {
                    $filas = DB::table($nombre)->orderBy('id')->get();

                    if ($filas->isNotEmpty()) {
                        $output[] = "";
                        $output[] = "-- Datos de catálogo: {$nombre}";

                        foreach ($filas as $fila) {
                            $arr      = (array) $fila;
                            $columnas = implode(', ', array_map(fn($c) => "`{$c}`", array_keys($arr)));
                            $valores  = implode(', ', array_map(function ($v) {
                                return is_null($v) ? 'NULL' : "'" . addslashes($v) . "'";
                            }, array_values($arr)));

                            $output[] = "INSERT INTO `{$nombre}` ({$columnas}) VALUES ({$valores});";
                        }
                    }
                }
            }

            $output[] = "";
        }

        $output[] = "SET FOREIGN_KEY_CHECKS = 1;";

        return response(implode("\n", $output), 200, [
            'Content-Type'        => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
