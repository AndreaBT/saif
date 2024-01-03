<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientesxCobratario extends Model
{
    use HasFactory;

    protected $table = 'clientesxcobratarios';
	protected $primaryKey   = 'Idclientesxcobratarios';
	protected $fillable = [
		'IdCliente',
        'IdUsuario',
		'TipoAsignacion'
	];
}
