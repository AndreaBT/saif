<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelesmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panelesmenus', function (Blueprint $table) {
            $table->id('IdPanel');
            $table->string('Nombre')->nullable()->default('');
            $table->string('TipoMenu')->nullable()->default('');
            $table->integer('IdMenu')->nullable()->default('0');
            $table->integer('IdSubMenu')->nullable()->default('0');
            $table->integer('IdApartado')->nullable()->default('0');
            $table->integer('IdAsociado')->nullable()->default('0');
            $table->string('Clave')->nullable()->default('');
            $table->string('Lugar')->nullable()->default('');
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
        Schema::dropIfExists('panelesmenus');
    }
}
