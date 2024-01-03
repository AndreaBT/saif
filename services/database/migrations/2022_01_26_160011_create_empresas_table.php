<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('IdEmpresa');
            $table->text('IdEstado')->nullable()->default('');
            $table->text('IdMunicipio')->nullable()->default('');
            $table->string('NombreComercial')->nullable()->default('');
            $table->string('RazonSocial')->nullable()->default('');
            $table->string('Rfc',13)->nullable()->default('');
            $table->string('Calle',100)->nullable()->default('');
            $table->string('NoInt',15)->nullable()->default('');
            $table->string('NoExt',15)->nullable()->default('');
            $table->string('Colonia',180)->nullable()->default('');
            $table->string('Cp',15)->nullable()->default('');
            $table->text('Referencias')->nullable()->default('');
            $table->string('Telefono',10)->nullable()->default('');
            $table->string('Imagen',300)->nullable()->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
