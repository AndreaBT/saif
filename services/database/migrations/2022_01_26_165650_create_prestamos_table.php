<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestamosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prestamos', function (Blueprint $table) {
			$table->id('IdPrestamo');
			$table->bigInteger('IdCliente');
			$table->bigInteger('IdSucursal')->nullable()->default(0);
			$table->bigInteger('IdRuta')->nullable()->default(0);
            $table->bigInteger('Creador')->nullable()->default(0);
            $table->bigInteger('IdCobratario')->nullable()->default(0);
            $table->bigInteger('IdAutorizo')->nullable()->default(0);
            $table->bigInteger('IdEntrego')->nullable()->default(0);
            $table->bigInteger('IdPrestamoxCancelacion')->nullable()->default(0);
            $table->bigInteger('IdRechazo')->nullable()->default(0);

			$table->string('Folio',250)->nullable()->default('');
            $table->string('Origen',50)->nullable();

			$table->dateTime('FechaAutorizacion')->nullable();
			$table->dateTime('FechaEntrega')->nullable();
			$table->dateTime('FechaLiquidacion')->nullable();
            $table->dateTime('FechaRechazo')->nullable();

			$table->decimal('MontoSolicitud', 32,2)->nullable();
			$table->decimal('MontoEntregado', 32,2)->nullable();

			$table->string('Estatus')->nullable();
			$table->string('EstatusEntrega')->nullable();

			$table->text('Observaciones')->nullable()->default('');
			$table->text('PrestamoMotRechazo')->nullable()->default(NULL);

			$table->decimal('MontoDiario', 32,2)->default(0);
			$table->decimal('TazaInteres', 32,2)->default(0);
			$table->decimal('TotalDevolverPrestamo', 32,2)->default(0);
			$table->tinyInteger('NumPagos')->nullable()->default(0);
			$table->decimal('TotalMultas',32)->nullable()->default(0);
			$table->decimal('TotalGlobal',32)->nullable()->default(0);
			$table->tinyInteger('NumPagoActual')->nullable()->default(0);
			$table->integer('DiasTranscurridos')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();

            ## CAMPOS OBTENIDOS DE LA BD ANTERIOR EN MONGO --
            $table->string('MigrateId',250)->nullable(); #id
            $table->string('MigrateUpUser',250)->nullable(); #updatedBy
            $table->string('MigrateBorrowerId',250)->nullable(); #borrowerId
            $table->string('MigrateCollectorId',250)->nullable(); #collectorId
            $table->string('MigrateFolio',250)->nullable(); #serial
            $table->string('MigrateCreatedAt',250)->nullable(); #createdAt
            $table->string('MigrateUpdatedtAt',250)->nullable(); #updatedtAt
            $table->string('MigrateInitialDate',250)->nullable(); #initialDate
            $table->string('MigrateFinalDate',250)->nullable(); #finalDate


		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('prestamos');
	}
}
