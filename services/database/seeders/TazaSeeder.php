<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TazaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tazainteres')->insert(
            [
                [
                    "IdTazainteres" => 1,
                    "Nombre"        => "Pagos",
                    "Porcentaje"    => 35,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
