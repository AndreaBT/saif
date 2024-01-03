<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->insert(
            [
                [
                    'NombreComercial' => 'Fianciera F',
                    'RazonSocial'     => 'Servicios Legales del Sureste SA de CV',
                    'Rfc'             => 'XAXX010101000',
                    'Calle'           => '17-Diag',
                    'NoExt'           => '433',
                    'NoInt'           => '',
                    'Colonia'         => 'Fracc. Juan Pablo II',
                    'Cp'              => '97000',
                    'Referencias'     => 'entre 34 y 36',
                    'IdEstado'        => 1,
                    'IdMunicipio'     => 1,
                    'Telefono'        => '9992007925',
                ]
            ]
        );
    }
}
