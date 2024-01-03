<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosevidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleadosevidencias', function (Blueprint $table) {
            $table->id('IdEmpleadoEvidencia');
            $table->integer('IdEmpleado');
            $table->string('Evidencia',350)->nullable()->default('');
            $table->string('Huella',350)->nullable()->default('');
            $table->string('Anio')->nullable()->default('');
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
        Schema::dropIfExists('empleadosevidencias');
    }
}
