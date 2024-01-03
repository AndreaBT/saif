<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpDatosFam extends Model
{
    use HasFactory;

    protected $table = 'empleadosdatosfamiliares';
	protected $primaryKey   = 'IdEmpdatosFam';
	protected $fillable = [
		'IdEmpleado',
		'NombreFam',
		'CalleFam',
		'TelefonoFam',
		'TipoDeDato',
		'NoIntFam',
		'NoExtFam',
		
	];

	public function empdatosfam(){
        return $this->hasMany(Empleado::class, "IdEmpleado",'IdEmpleado');
    }
}
