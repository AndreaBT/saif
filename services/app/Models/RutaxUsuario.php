<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaxUsuario extends Model
{
	use HasFactory;

	protected $primaryKey = 'IdRutaxUsuario';
	protected $table = 'rutasxusuarios';
	protected $fillable = [
		'IdRuta',
		'IdUsuario',
		'FechaAsignacion'
	];

    public function usrGestores(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, "IdUsuario",'IdUsuario')
            ->where('users.IdPerfil','=',4);
    }

}
