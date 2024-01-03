<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RutasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rutas')->insert(
            [
                [
                    "IdRuta"     => 1,
                    "NombreRuta" => "Norte",
                    "IdPais"     => 1,
                    "IdEstado"   => 31,
                    "IdSucursal" => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]
        );

        DB::table('rutasxusuarios')->insert(
            [
                [
                    "IdRutaxUsuario"  => 1,
                    "IdRuta"          => 1,
                    "IdUsuario"       => 1,
                    "FechaAsignacion" => date('Y-m-d'),
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
