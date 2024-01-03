<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MailServConfSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('mailserviceconfig')->insert([
            
            [
                "TipoServicio" => "1",
                "KeyService"   => "PROVIDER",
                "Driver"       => "smtp",
                "Host"         => "smtp.zoho.com",
                "Port"         => "465",
                "Username"     => "josecc2021@zohomail.com",
                "Password"     => "CABRERA25CIME",
                "Encryption"   => "ssl",
                "Remitente"    => "josecc2021@zohomail.com",
                "NameApp"      => "Financiera F",
                "Destinatario" => "",
                "Alias"        => "Recuperar Contraseña",
                "created_at"   => date('Y-m-d H:i:s'),
                "updated_at"   => date('Y-m-d H:i:s'),
            ],

        ]);
    }
}
