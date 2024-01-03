<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteEvidencia extends Model
{
	use HasFactory;

	protected $primaryKey = 'IdClienteEvidencia';
	protected $table = 'clientesevidencias';
	protected $fillable = [
		'IdCliente',
		'Evidencia',
		'Observaciones',
		'TipoEvidencia',
	];
}
