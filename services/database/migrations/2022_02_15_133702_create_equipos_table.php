<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id('IdEquipo');
            $table->string('Nombre')->nullable()->default('');
            $table->string('Marca')->nullable()->default('');
            $table->string('Modelo')->nullable()->default('');
            $table->string('Imei')->nullable()->default('');
            $table->string('Serie')->nullable()->default('');
            $table->string('Placa')->nullable()->default('');
            $table->string('Color')->nullable()->default('');
            $table->string('Telefono',10)->nullable()->default('');
            $table->string('NumeroEconomico')->nullable()->default('');
            $table->string('TipoEquipo')->nullable()->default('');
            $table->string('Imagen',350)->nullable()->default('');
            $table->string('Anio')->nullable()->default('');
            $table->string('Asignado')->nullable()->default('');
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
        Schema::dropIfExists('equipos');
    }
}
