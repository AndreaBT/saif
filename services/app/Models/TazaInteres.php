<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TazaInteres extends Model
{
    use HasFactory;

    protected $table = 'tazainteres';
	protected $primaryKey   = 'IdTazainteres';
	protected $fillable = [
		'Nombre',
        'Porcentaje'
	];
}
