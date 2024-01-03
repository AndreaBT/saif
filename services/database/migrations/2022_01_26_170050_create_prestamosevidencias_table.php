<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestamosevidenciasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prestamosevidencias', function (Blueprint $table) {
			$table->id('IdPrestamosEvidencia');
			$table->bigInteger('IdPrestamo');
			$table->bigInteger('IdUsuario');
			$table->string('Evidencia', 355)->nullable();
			$table->string('TipoEvidencia')->nullable();
			$table->text('Etapa')->nullable();
			$table->string('Anio');
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
		Schema::dropIfExists('prestamosevidencias');
	}
}
