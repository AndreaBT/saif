<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquiposSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('equipos')->insert(
            [
                [
                    "IdEquipo"        => 1,
                    "Nombre"          => "Huawei Y9s",
                    "Marca"           => 'Huawei',
                    "Modelo"          => 'Y9s 2019',
                    "Imei"            => '654814265365',                    
                    "Serie"           => '---',
                    "Placa"           => '---',                    
                    "Color"           => 'Azul',
                    "Telefono"        => '9991306215',
                    "NumeroEconomico" => '---',
                    "TipoEquipo"      => 2,
                    "Imagen"          => '20220430104223_TJZSW0E5MRVV86Y601TT.jpeg',
                    "Anio"            => '2022-04-30',
                    "Asignado"        => 'NO',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    "IdEquipo"        => 2,
                    "Nombre"          => "Iphone 13 PRO",
                    "Marca"           => 'Iphone',
                    "Modelo"          => '13 PRO 2022',
                    "Imei"            => '654814265365',                    
                    "Serie"           => '---',
                    "Placa"           => '---',                    
                    "Color"           => 'Blanco',
                    "Telefono"        => '9991306215',
                    "NumeroEconomico" => '---',
                    "TipoEquipo"      => 2,
                    "Imagen"          => '20220430104223_TJZSW0E5MRVV86Y601TT.jpeg',
                    "Anio"            => '2022-04-30',
                    "Asignado"        => 'NO',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    "IdEquipo"        => 3,
                    "Nombre"          => "Aveo",
                    "Marca"           => 'Chevrolet',
                    "Modelo"          => 'LT',
                    "Imei"            => '---',                    
                    "Serie"           => '1231123',
                    "Placa"           => 'wdas213',                    
                    "Color"           => 'Plata',
                    "Telefono"        => '---',
                    "NumeroEconomico" => '123assdasd',
                    "TipoEquipo"      => 1,
                    "Imagen"          => '20220430104355_08MVOCDAHM53ZU8Z6XZ8.png',
                    "Anio"            => '2022-04-30',
                    "Asignado"        => 'NO',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    "IdEquipo"        => 4,
                    "Nombre"          => "Jetta",
                    "Marca"           => 'Volkswagen',
                    "Modelo"          => 'GLI',
                    "Imei"            => '---',                    
                    "Serie"           => '1231123',
                    "Placa"           => 'wdas213',                    
                    "Color"           => 'Negro',
                    "Telefono"        => '---',
                    "NumeroEconomico" => '123assdasd',
                    "TipoEquipo"      => 1,
                    "Imagen"          => '20220430104355_08MVOCDAHM53ZU8Z6XZ8.png',
                    "Anio"            => '2022-04-30',
                    "Asignado"        => 'NO',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ],
                
            ]
        );
    }
}
