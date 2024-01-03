<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoliosSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('folios')->insert(
            [
                [
                    'IdFolio'    => 1,
                    'TipoFolio'  => 'PRESTAMO',
                    'Serie'      => 'CRT',
                    'Numero'     => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
