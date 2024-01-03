<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutasxusuariosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rutasxusuarios', function (Blueprint $table) {
			$table->id('IdRutaxUsuario');
			$table->bigInteger('IdRuta');
			$table->integer('IdUsuario');
			$table->date('FechaAsignacion')->nullable();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rutasxusuarios');
    }
}
