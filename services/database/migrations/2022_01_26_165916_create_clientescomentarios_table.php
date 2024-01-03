<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientescomentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientescomentarios', function (Blueprint $table) {
            $table->id('IdClienteComentario');
            $table->bigInteger('IdUsuario');
            $table->bigInteger('IdCliente');
            $table->text('Comentario');
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
        Schema::dropIfExists('clientescomentarios');
    }
}
