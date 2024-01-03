<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = 'empleados';
    protected $primaryKey   = 'IdEmpleado';
    protected $fillable     = [
        "Rfc",
        "Calle",
        "NoInt",
        "NoExt",
        "Colonia",
        "Cp",
        "Referencias",
        "FechaNacimiento",
        "EstadoCivil",
        "FechaAlta",
        "FechaBaja",
        "FechaEntrega",
        "Finiquito",
        "FechaFiniquito",
        "Genero",
        "Nacionalidad"
    ];

    public function usuarios(){
        return $this->hasOne(Empleado::class, "IdEmpleado",'IdEmpleado');
    }

    public function refere() {
        return $this ->hasMany( EmpleadosRefPersonales::class, "IdEmpleado",'IdEmpleado' );
    }

    
}
