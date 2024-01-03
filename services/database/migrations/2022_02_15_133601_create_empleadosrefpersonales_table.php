<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosrefpersonalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleadosrefpersonales', function (Blueprint $table) {
            $table->id('IdEmpRefPer');
            $table->integer('IdEmpleado')->nullable()->default(0);
            $table->string('NombreRef')->nullable()->default('');
            $table->string('TelefonoRef',10)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleadosrefpersonales');
    }
}
