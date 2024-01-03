<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesevidenciasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clientesevidencias', function (Blueprint $table) {
			$table->id('IdClienteEvidencia');
			$table->bigInteger('IdCliente');
			$table->string('Evidencia', 355)->nullable();
			$table->text('Observaciones')->nullable();
			$table->string('TipoEvidencia', 45)->nullable()->default('');
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
		Schema::dropIfExists('clientesevidencias');
	}
}
