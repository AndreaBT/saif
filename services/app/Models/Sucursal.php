<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $primaryKey = 'IdSucursal';
	protected $table = 'sucursales';
	protected $fillable = [
		'IdEmpresa',
		'IdEstado',
		'IdMunicipio',
		'Nombre',
		'Calle',
		'NoInt',
		'NoExt',
		'Colonia',
		'Cp',
		'Referencias',
		'Telefono',
		'Latitud',
		'Longitud',
		'ClaveSucursal',
		'NumFolioPrestamo'
	];
}
