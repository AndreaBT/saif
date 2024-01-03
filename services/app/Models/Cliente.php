<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $table = 'clientes';
	protected $primaryKey   = 'IdCliente';
	protected $fillable = [
		'NumCliente',
		'IdRuta',
		'Nombre',
		'ApellidoPat',
		'ApellidoMat',
		'NombreCompleto',
		'FechaNacimiento',
		'Correo',
		'Telefono',
        'NombreNegocio',
		'DescripcionNegocio',
		'TipoNegocio',
		'Calle',
		'NoInt',
		'NoExt',
		'Colonia',
		'Cp',
		'Referencias',
		'IdEstado',
		'IdMunicipio',
		'Latitud',
		'Longitud',
		'Prospecto',
		'Estatus',
		'Imagen',
		'UrlImg',
		'MotivoRechazo',
		'FechaRechazo',
		'Origen',
	];

	public function evidencias(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClienteEvidencia::class, "IdCliente",'IdCliente');
    }

    public function prestamo(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(Prestamo::class, "IdCliente",'IdCliente');
    }
}
