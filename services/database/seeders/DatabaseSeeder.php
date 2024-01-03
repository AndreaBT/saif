<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EmpresasSeeder::class);
        $this->call(EquiposSeeder::class); 
        $this->call(EstadosSeeder::class);     
        $this->call(FoliosSeeder::class);  
        $this->call(MailServConfSeeder::class);
        $this->call(MunicipiosSeeder::class);
        $this->call(PerfilSeeder::class);
        $this->call(PrestamoMontoSeeder::class);
        $this->call(PuestosSeeder::class);
        $this->call(RutasSeeder::class);
        $this->call(SucursalesSeeder::class);
        $this->call(TazaSeeder::class);
        $this->call(UserSeeder::class);
    }
}
