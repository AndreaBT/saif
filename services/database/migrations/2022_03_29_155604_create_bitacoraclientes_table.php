<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBitacoraclientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitacoraclientes', function (Blueprint $table) {
            $table->id('IdBitacora');
            $table->bigInteger('IdUsuario');
            $table->bigInteger('IdCliente');
            $table->bigInteger('IdPrestamo');
			$table->text('Observaciones')->nullable()->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bitacoraclientes');
    }
}
