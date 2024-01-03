<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestamo extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $primaryKey = 'IdPrestamo';
    protected $table = 'prestamos';
	protected $fillable = [
		"IdCliente",
		"IdSucursal",
		"IdRuta",
        "Creador",
        "IdCobratario",
		"IdAutorizo",
        "IdEntrego",
		"IdPrestamoxCancelacion",
        "IdRechazo",

        "Folio",
        "Origen",

		"FechaAutorizacion",
		"FechaEntrega",
		"FechaLiquidacion",
		"FechaRechazo",

		"MontoSolicitud",
		"MontoEntregado",

		"Estatus",
		"EstatusEntrega",

		"Observaciones",
		"PrestamoMotRechazo",

        "MontoDiario",
        "TazaInteres",
		"TotalDevolverPrestamo",
		"NumPagos",
		"TotalMultas",
		"TotalGlobal",
		"NumPagoActual",
		"DiasTranscurridos"
	];

	public function sucursal(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Sucursal::class, "IdSucursal",'IdSucursal')->select(array('IdSucursal','Nombre'));
    }

    public function ruta(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Ruta::class, "IdRuta",'IdRuta')->select(array('IdRuta','NombreRuta'));
    }
}
