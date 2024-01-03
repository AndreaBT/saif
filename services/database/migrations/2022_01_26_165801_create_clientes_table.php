<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clientes', function (Blueprint $table) {
			$table->id('IdCliente');
            $table->bigInteger('NumCliente')->nullable()->default(0); ## Numero de identificacion o referencia del cliente

			$table->bigInteger('IdRuta')->nullable()->default(0);
			$table->string('Nombre')->nullable()->default('');
			$table->string('ApellidoPat')->nullable()->default('');
			$table->string('ApellidoMat')->nullable()->default('');
			$table->string('NombreCompleto')->nullable()->default('');
			$table->date('FechaNacimiento')->nullable()->default(null);
            $table->string('Correo', 250)->nullable()->default('');
            $table->string('Telefono', 15)->nullable()->default('');
            $table->string('NombreNegocio')->nullable()->default('');
            $table->text('DescripcionNegocio')->nullable()->default('');
            $table->string('TipoNegocio',50)->nullable()->default('');

			$table->string('Calle', 100)->nullable()->default('');
			$table->string('NoInt', 15)->nullable()->default('');
			$table->string('NoExt', 15)->nullable()->default('');
			$table->string('Colonia', 180)->nullable()->default('');
			$table->string('Cp', 15)->nullable()->default('');
			$table->text('Referencias')->nullable()->default('');
			$table->integer('IdEstado')->nullable();
			$table->integer('IdMunicipio')->nullable();
            $table->string('Latitud', 50)->nullable()->default('');
            $table->string('Longitud', 50)->nullable()->default('');

			$table->tinyInteger('Prospecto')->nullable()->default('1');
			$table->string('Estatus', 15)->nullable()->default('Pendiente');

			$table->string('Imagen', 300)->nullable()->default('');
			$table->string('UrlImg', 300)->nullable()->default('');

			$table->text('MotivoRechazo')->nullable()->default('');
			$table->date('FechaRechazo')->nullable();
			$table->string('Origen',50)->nullable();

			$table->timestamps();
			$table->softDeletes();

            $table->string('MigrateId',250)->nullable();
            $table->string('MigrateUpUser',250)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('clientes');
	}
}
