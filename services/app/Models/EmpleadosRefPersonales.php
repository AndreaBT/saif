<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadosRefPersonales extends Model
{
    use HasFactory;

    protected $table = 'empleadosrefpersonales';
	protected $primaryKey   = 'IdEmpRefPer';
	protected $fillable = [
		'IdEmpleado',
		'NombreRef',
		'TelefonoRef',
	];

	

	public function empleadoBelong() {
        return $this ->belongsToMany( Empleado::class, 'IdEmpleado','IdEmpleado' );
    }

	
}
