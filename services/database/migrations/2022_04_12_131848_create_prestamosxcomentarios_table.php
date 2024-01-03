<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestamosxcomentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamosxcomentarios', function (Blueprint $table) {
            $table->id('IdPrestamoComentario');
            $table->bigInteger('IdPrestamosPago');
            $table->bigInteger('IdPrestamo');
			$table->string('TipoPrestamo')->nullable();
            $table->text('Comentario')->nullable();
            $table->date('Fecha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestamosxcomentarios');
    }
}
