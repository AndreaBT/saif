<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraEquipo extends Model
{
    use HasFactory;

    protected $table        = 'bitacoraequipos';
    protected $primaryKey   = 'IdBitacoraEquipo';
    protected $fillable     = [
        'IdRealizo',
        'Realizo',
        'IdEquipo',
        'IdAfectado',
        'TipoBitacora',
        'FechaEvento',
        'Descripcion',
    ];

    public function afectado(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, "IdUsuario",'IdAfectado')
            ->select(array('IdUsuario','NombreCompleto'));
    }

    public function equipoMod(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Equipo::class, "IdEquipo",'IdEquipo');
    }


}

