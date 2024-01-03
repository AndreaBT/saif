<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estados')->insert(
            [
                ['IdPais' => 1, 'Nombre' => 'Aguascalientes'],
                ['IdPais' => 1, 'Nombre' => 'Baja California'],
                ['IdPais' => 1, 'Nombre' => 'Baja California Sur'],
                ['IdPais' => 1, 'Nombre' => 'Campeche'],
                ['IdPais' => 1, 'Nombre' => 'Coahuila'],
                ['IdPais' => 1, 'Nombre' => 'Colima'],
                ['IdPais' => 1, 'Nombre' => 'Chiapas'],
                ['IdPais' => 1, 'Nombre' => 'Chihuahua'],
                ['IdPais' => 1, 'Nombre' => 'Ciudad de México'],
                ['IdPais' => 1, 'Nombre' => 'Durango'],
                ['IdPais' => 1, 'Nombre' => 'Guanajuato'],
                ['IdPais' => 1, 'Nombre' => 'Guerrero'],
                ['IdPais' => 1, 'Nombre' => 'Hidalgo'],
                ['IdPais' => 1, 'Nombre' => 'Jalisco'],
                ['IdPais' => 1, 'Nombre' => 'Estado de México'],
                ['IdPais' => 1, 'Nombre' => 'Michoacán'],
                ['IdPais' => 1, 'Nombre' => 'Morelos'],
                ['IdPais' => 1, 'Nombre' => 'Nayarit'],
                ['IdPais' => 1, 'Nombre' => 'Nuevo León'],
                ['IdPais' => 1, 'Nombre' => 'Oaxaca'],
                ['IdPais' => 1, 'Nombre' => 'Puebla'],
                ['IdPais' => 1, 'Nombre' => 'Querétaro'],
                ['IdPais' => 1, 'Nombre' => 'Quintana Roo'],
                ['IdPais' => 1, 'Nombre' => 'San Luis Potosí'],
                ['IdPais' => 1, 'Nombre' => 'Sinaloa'],
                ['IdPais' => 1, 'Nombre' => 'Sonora'],
                ['IdPais' => 1, 'Nombre' => 'Tabasco'],
                ['IdPais' => 1, 'Nombre' => 'Tamaulipas'],
                ['IdPais' => 1, 'Nombre' => 'Tlaxcala'],
                ['IdPais' => 1, 'Nombre' => 'Veracruz'],
                ['IdPais' => 1, 'Nombre' => 'Yucatán'],
                ['IdPais' => 1, 'Nombre' => 'Zacatecas'],
            ]
        );
    }
}

