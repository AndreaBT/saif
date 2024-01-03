<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosdatosfamiliaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleadosdatosfamiliares', function (Blueprint $table) {
            $table->id('IdEmpdatosFam');
            $table->integer('IdEmpleado')->nullable()->default(0);
            $table->string('NombreFam')->nullable()->default('');
            $table->string('CalleFam')->nullable()->default('');
            $table->string('NoIntFam')->nullable()->default('');
            $table->string('NoExtFam')->nullable()->default('');
            $table->string('TelefonoFam',10)->nullable()->default('');
            $table->string('TipoDeDato',2)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleadosdatosfamiliares');
    }
}
