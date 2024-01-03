<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('IdUsuario');
            $table->integer('IdEmpresa')->nullable()->default(0);
            $table->integer('IdSucursal')->nullable()->default(0);
            $table->integer('IdEmpleado')->nullable()->default(0);
            $table->integer('IdPerfil')->nullable()->default(0);
            $table->integer('IdPuesto')->nullable()->default(0);
            $table->integer('IdPais')->nullable()->default(0);
            $table->integer('IdEstado')->nullable()->default(0);
            $table->integer('IdMunicipio')->nullable()->default(0);

            $table->string('Nombre')->nullable()->default('');
            $table->string('ApellidoPat')->nullable()->default('');
            $table->string('ApellidoMat')->nullable()->default('');
            $table->string('NombreCompleto')->nullable()->default('');
            $table->string('Correo')->nullable()->default('');
            $table->string('Telefono',45)->nullable()->default('');

            $table->string('username')->unique();
            $table->string('password');

            $table->boolean('Confirmado')->nullable()->default(false);
            $table->boolean('Disponible')->nullable()->default(false);
            $table->boolean('UsuarioApp')->nullable()->default(false);
            $table->string('Imagen',300)->nullable()->default('');
            $table->string('UrlImg',300)->nullable()->default('');
            $table->timestamps();
            $table->softDeletes();

            $table->string('MigrateId',250)->nullable(); #Id de coleccion User
            $table->string('MigrateCollectorId',250)->nullable(); #Id de coleccion Collector


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
