<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestamoPago extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $primaryKey = 'IdPrestamosPago';
	protected $table = 'prestamospagos';
	protected $fillable = [
		'IdPrestamo',
		'IdCobratario',
		'IdCliente',
		'FechaPagoReal',
		'FechaPagoEstimado',
		'HoraPagoReal',
		'HoraPagoEstimada',//!quitar
		'MontoReal',
		'MontoEstimado',
		'NumeroPago',
		'NumeroAbono',
		'NumeroAutorizacion',
		'EstatusPago',
		'Multa'
	];
}
