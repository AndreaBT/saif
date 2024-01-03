<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipoxUsuario extends Model {
    use HasFactory;
    use SoftDeletes;
    protected $table      = 'equiposxusuarios';
	protected $primaryKey = 'IdEquipoxUsuario';
	protected $fillable   = [
		'IdUsuario',
		'IdEquipo',
		'Descripcion',
		'TipoHerramienta',
        'FechaEntrega'
	];
}
