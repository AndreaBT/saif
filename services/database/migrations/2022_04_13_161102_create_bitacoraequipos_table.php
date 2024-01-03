<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBitacoraequiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitacoraequipos', function (Blueprint $table) {
            $table->id('IdBitacoraEquipo');
            $table->bigInteger('IdRealizo')->default(0)->nullable();
            $table->string('Realizo')->default('')->nullable();
            $table->integer('IdEquipo')->default(0)->nullable();
            $table->integer('IdAfectado')->default(0)->nullable();
            $table->string('TipoBitacora')->default('')->nullable();
            $table->dateTime('FechaEvento')->nullable();
            $table->text('Descripcion')->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bitacoraequipos');
    }
}
