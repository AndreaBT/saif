<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->insert(
            [
                [
                    'IdEmpresa'      => 0,
                    'IdSucursal'     => 0,
                    'IdEmpleado'     => 0,
                    'IdPerfil'       => 0,
                    'IdPuesto'       => 0,
                    'IdPais'         => 1,
                    'IdEstado'       => 0,
                    'IdMunicipio'    => 0,
                    'Nombre'         => 'Lugar Creativo',
                    'ApellidoPat'    => '',
                    'ApellidoMat'    => '',
                    'NombreCompleto' => 'Lugar Creativo',
                    'Correo'         => 'josecc850@gmail.com',
                    'Telefono'       => '',
                    'username'       => 'superadmin',
                    'password'       => Hash::make('Cadmin1319'),
                    'Confirmado'     => 1,
                    'Disponible'     => 1,
                    'Imagen'         => '',
                    'UrlImg'         => '',
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ],
                [
                    'IdEmpresa'      => 0,
                    'IdSucursal'     => 0,
                    'IdEmpleado'     => 0,
                    'IdPerfil'       => 1,
                    'IdPuesto'       => 1,
                    'IdPais'         => 1,
                    'IdEstado'       => 1,
                    'IdMunicipio'    => 1,
                    'Nombre'         => 'LugarCreativo',
                    'ApellidoPat'    => 'Admin',
                    'ApellidoMat'    => '',
                    'NombreCompleto' => 'LugarCreativo Admin',
                    'Correo'         => 'raul.mut@lugarcreativo.mx',
                    'Telefono'       => '',
                    'username'       => 'admin22',
                    'password'       => Hash::make('Admin#2022'),
                    'Confirmado'     => 1,
                    'Disponible'     => 1,
                    'Imagen'         => '',
                    'UrlImg'         => '',
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ],
                [
                    'IdEmpresa'      => 1,
                    'IdSucursal'     => 1,
                    'IdEmpleado'     => 0,
                    'IdPerfil'       => 1,
                    'IdPuesto'       => 1,
                    'IdPais'         => 1,
                    'IdEstado'       => 1,
                    'IdMunicipio'    => 1,
                    'Nombre'         => 'Jorge Luis',
                    'ApellidoPat'    => 'Casanova',
                    'ApellidoMat'    => 'Gonzaléz',
                    'NombreCompleto' => 'Jorge Luis Casanova Gonzaléz',
                    'Correo'         => 'j_l_casanova@hotmail.com',
                    'Telefono'       => '',
                    'username'       => 'Jcasanova',
                    'password'       => Hash::make('Admin#2022'),
                    'Confirmado'     => 1,
                    'Disponible'     => 1,
                    'Imagen'         => '',
                    'UrlImg'         => '',
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ],

            ]
        );
    }
}
