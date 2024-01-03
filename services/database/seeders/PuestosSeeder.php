<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuestosSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('puestos')->insert(
            [
                [
                    "IdPuesto"   => 1,
                    "Nombre"     => "Puesto 1",
                    "IdSucursal" => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    "IdPuesto"   => 2,
                    "Nombre"     => "Puesto 2",
                    "IdSucursal" => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
                
            ]
        );
    }
}