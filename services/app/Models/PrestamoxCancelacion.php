<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestamoxCancelacion extends Model {
    use HasFactory;
    protected $primaryKey = 'IdPrestamoxCancelacion';
    protected $table      = 'prestamosxcancelaciones';
	protected $fillable   = [
		"IdPrestamo",
		"IdUsuario",
		"MotivoCancelacion",
        "Imagen1",
        "Imagen2",
        "TipoCancelacion"
	];
}
