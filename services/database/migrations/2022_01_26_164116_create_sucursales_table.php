<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('IdSucursal');
            $table->integer('IdEmpresa');
            $table->integer('IdEstado')->default(0);
            $table->integer('IdMunicipio')->default(0);
            $table->string('Nombre',250)->nullable()->default('');
            $table->string('Calle',100)->nullable()->default('');
            $table->string('NoInt',15)->nullable()->default('');
            $table->string('NoExt',15)->nullable()->default('');
            $table->string('Colonia',180)->nullable()->default('');
            $table->string('Cp',15)->nullable()->default('');
            $table->text('Referencias')->nullable()->default('');
            $table->string('Telefono',15)->nullable()->default('');
            $table->string('Latitud',50)->nullable()->default('');
            $table->string('Longitud',50)->nullable()->default('');
            $table->string('ClaveSucursal',50)->nullable()->default('');
            $table->string('NumFolioPrestamo',50)->nullable()->default('');
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
        Schema::dropIfExists('sucursales');
    }
}
