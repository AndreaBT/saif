<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestamosxcancelacionesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('prestamosxcancelaciones', function (Blueprint $table) {
            $table->id('IdPrestamoxCancelacion');
            $table->string('IdPrestamo')->nullable()->default('');
            $table->string('IdUsuario')->nullable()->default('');
            $table->string('MotivoCancelEntregado')->nullable()->default('');
            $table->string('Imagen1',350)->nullable()->default('');
            $table->string('Imagen2',350)->nullable()->default('');
            $table->string('TipoCancelacion')->nullable()->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('prestamosxcancelaciones');
    }
}
