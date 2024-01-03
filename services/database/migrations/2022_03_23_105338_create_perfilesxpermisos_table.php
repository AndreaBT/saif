<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilesxpermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfilesxpermisos', function (Blueprint $table) {
            $table->id('IdPerfilxPermiso');
            $table->integer('IdPerfil');
            $table->integer('IdPanel');
            $table->integer('IdPermiso');
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
        Schema::dropIfExists('perfilesxpermisos');
    }
}
