<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestamospagosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prestamospagos', function (Blueprint $table) {
			$table->id('IdPrestamosPago');
			$table->bigInteger('IdPrestamo');
			$table->bigInteger('IdCobratario');//Cambiar a IdCobratario. 
			$table->bigInteger('IdCliente');
			$table->date('FechaPagoReal')->nullable();// fecha real. 
			$table->date('FechaPagoEstimado')->nullable();//fecha estimada de pago. 
			$table->decimal('MontoReal', 32)->nullable();// monto real de pago
			$table->decimal('MontoEstimado', 32)->nullable();//Monto estimado 
			$table->tinyInteger('NumeroPago')->nullable();
			$table->tinyInteger('NumeroAbono')->nullable();
			$table->string('NumeroAutorizacion')->nullable(); //Número de autorización. 
			$table->boolean('Multa')->nullable()->default(false);//agregar campo para multa 0 o 1
			$table->string('EstatusPago')->nullable(); //agregar estatus de pago a la tabla. 
			$table->string('Estatus')->nullable();  
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
		Schema::dropIfExists('prestamospagos');
	}
}
