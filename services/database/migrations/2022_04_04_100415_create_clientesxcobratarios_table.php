<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesxcobratariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientesxcobratarios', function (Blueprint $table) {
            $table->id('Idclientesxcobratarios');
            $table->integer('IdCliente')->nullable()->default(0);
            $table->integer('IdUsuario')->nullable()->default(0);
            $table->string('TipoAsignacion',5)->nullable()->default('P');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientesxcobratarios');
    }
}
