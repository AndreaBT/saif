<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = 'equipos';
    protected $primaryKey   = 'IdEquipo';
    protected $fillable     = [
        "Nombre",
        "Marca",
        "Modelo",
        "Imei",
        "Serie",
        "Placa",
        "Color",
        "Telefono",
        "NumeroEconomico",
        "TipoEquipo",
        "Imagen",
        "Anio",
        "Asignado"
    ];
}
