<?php

namespace App\Custom;

use App\Models\TazaInteres;

class CalculaMontosPrestamo
{
    public static function calulaMontos($monto = 0): array
    {

        $Taza = TazaInteres::query()->where('Nombre','=','Pagos')->first();

        if(!empty($Taza)) {

            if($Taza->Porcentaje > 0) {

                $diasPrestamo   = 45;                                   ## DIAS EN LOS QUE DEBE PAGARSE EL PRESTAMO.
                $Conversion     = (intval($Taza->Porcentaje) / 100);    ## CONVERSION A PORCENTAJE DE TAZA
                $totalDevolver  = ( (floatval($monto) * $Conversion) + floatval($monto) ); ## MONTO TOTAL A DEVOLVER POR EL CLIENTE

                $montoPagoDiario = ($totalDevolver / $diasPrestamo); ## MONTO DIARIO QUE EL CLIENTE DEBE PAGAR

                $arrInfo = [
                    'diasPrestamo'          => $diasPrestamo,
                    'montoTotalDevolver'    => $totalDevolver,
                    'montoDiario'           => $montoPagoDiario,
                ];

                return [
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $arrInfo
                ];

            }else {
                return [
                    'status'    => false,
                    'message'   => 'El % de taza no pude ser 0',
                    'eCode'     => 2,
                    'data'      => ''
                ];
            }

        }else{
            return [
                'status'    => false,
                'message'   => 'No existe valor de taza',
                'eCode'     => 1,
                'data'      => ''
            ];

        }
    }
}
