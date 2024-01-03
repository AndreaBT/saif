<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rutas', function (Blueprint $table) {
			$table->id('IdRuta');
			$table->string('NombreRuta', 250);
			$table->integer('IdPais')->default(1);
			$table->integer('IdEstado');
			$table->bigInteger('IdSucursal');
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
		Schema::dropIfExists('rutas');
	}
}
