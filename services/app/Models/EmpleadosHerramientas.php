<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpleadosHerramientas extends Model
{
    use HasFactory;
	use SoftDeletes;
    protected $table = 'empleadosherramientas';
	protected $primaryKey   = 'IdEmpleadoHerramienta';
	protected $fillable = [
		'IdEmpleado',
		'IdEquipo',
		'Descripcion',
		'TipoHerramienta',
        'FechaEntrega'
	];
}
