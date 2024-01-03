<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntregadoresxprestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregadoresxprestamos', function (Blueprint $table) {
            $table->id('Identregadoresxprestamos');
            $table->integer('IdUsuario')->nullable()->default(0);
            $table->integer('IdPrestamo')->nullable()->default(0);
            $table->integer('IdCliente')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entregadoresxprestamos');
    }
}
