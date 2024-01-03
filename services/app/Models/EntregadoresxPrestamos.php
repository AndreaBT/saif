<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregadoresxPrestamos extends Model
{
    use HasFactory;

    protected $table = 'entregadoresxprestamos';
	protected $primaryKey   = 'Identregadoresxprestamos';
	protected $fillable = [
		'IdUsuario',
		'IdPrestamo',
		'IdCliente'

	];
}
