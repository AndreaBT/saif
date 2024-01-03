<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposxusuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equiposxusuarios', function (Blueprint $table) {
            $table->id('IdEquipoxUsuario');
            $table->integer('IdUsuario');
            $table->integer('IdEquipo');
            $table->text('Descripcion')->nullable()->default('');
            $table->string('TipoHerramienta')->nullable()->default('');
            $table->date('FechaEntrega')->nullable();
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
        Schema::dropIfExists('equiposxusuarios');
    }
}
