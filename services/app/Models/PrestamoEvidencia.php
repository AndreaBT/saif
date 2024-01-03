<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrestamoEvidencia extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $primaryKey = 'IdPrestamosEvidencia';
	protected $table = 'prestamosevidencias';
	protected $fillable = [
		'IdPrestamo',
		'IdUsuario',
		'Evidencia',
		'TipoEvidencia',
		'Etapa',
		'Anio'
	];
}
