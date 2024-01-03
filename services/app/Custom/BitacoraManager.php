<?php

namespace App\Custom;

use App\Models\BitacoraEquipo;

class BitacoraManager
{
    public static function creaBitacoraEquipo($Realizo, $IdEquipo, $IdAfectado, $TipoBitacora = '', $mensaje): bool
    {

        $Arr = array(
            'IdRealizo'     => $Realizo->IdUsuario,
            'Realizo'       => $Realizo->NombreCompleto,
            'IdEquipo'      => $IdEquipo,
            'IdAfectado'    => $IdAfectado,
            'TipoBitacora'  => ($TipoBitacora === 'A') ? 'Asignacion' : 'Modificacion',
            'FechaEvento'   => date('Y-m-d H:i:s'),
            'Descripcion'   => $mensaje,
        );


        $Bitacora = BitacoraEquipo::query()
            ->insert($Arr);

        return !empty($Bitacora);
    }
}
