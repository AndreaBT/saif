<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestamoxComentario extends Model
{
    use HasFactory;

    protected $primaryKey = 'IdPrestamoComentario';
    protected $table = 'prestamosxcomentarios';
	protected $fillable = [
		"IdPrestamosPago",
		"IdPrestamo",
		"Fecha",
        "Comentario"
	];
}
