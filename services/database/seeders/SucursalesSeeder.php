<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sucursales')->insert(
            [
                [
                    "IdSucursal"  => 1,
                    "IdEmpresa"   => 1,
                    "IdEstado"    => 31,
                    "IdMunicipio" => 1,
                    "Nombre"      => "Sucursal Norte",
                    "Calle"       => "34",
                    "NoInt"       => "",
                    "NoExt"       => "256",
                    "Colonia"     => "Cabo Norte",
                    "Cp"          => "97000",
                    "Referencias" => "Casa con rejas",
                    "Telefono"    => "9876542310",
                    "Latitud"     => "",
                    "Longitud"    => "",
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
