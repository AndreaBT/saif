<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('empleados', function (Blueprint $table) {
			$table->id('IdEmpleado');
			$table->string('Rfc', 13)->nullable()->default('');
			$table->string('Calle', 100)->nullable()->default('');
			$table->string('NoInt', 15)->nullable()->default('');
			$table->string('NoExt', 15)->nullable()->default('');
			$table->string('Colonia', 180)->nullable()->default('');
			$table->string('Cp', 15)->nullable()->default('');
			$table->text('Referencias')->nullable()->default('');
			$table->date('FechaNacimiento')->nullable();
			$table->string('EstadoCivil')->nullable()->default('');
			$table->date('FechaAlta')->nullable();
			$table->date('FechaBaja')->nullable();
			$table->date('FechaEntrega')->nullable();
			$table->string('Finiquito')->nullable();
			$table->date('FechaFiniquito')->nullable();
			$table->string('Genero')->nullable()->default('');
			$table->string('Nacionalidad')->nullable()->default('');
			$table->string('EstatusCorte', 40)->nullable()->default('');
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
		Schema::dropIfExists('empleados');
	}
}
