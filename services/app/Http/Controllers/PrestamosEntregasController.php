<?php

namespace App\Http\Controllers;

use App\Custom\CalculaMontosPrestamo;
use App\Custom\FilesManager;
use App\Custom\Funciones;
use App\Custom\ResponseManager;
use App\Models\Prestamo;
use App\Models\PrestamoEvidencia;
use App\Models\PrestamoxComentario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Facades\JWTAuth;

class PrestamosEntregasController extends Controller {
    private $location            = 'PrestamosEntregasController';
    private $rutaPrestamoEntrega = 'imgprestamoentrega';

    public function PrestamoEntregaApp(Request $request) {

        try {

            $session    = JWTAuth::authenticate();
            $params     = $request->only('IdPrestamo','IdCliente','MontoEntregado','ComentariosEntrega','ImgPagare','ImgHojaReferencia','ImgTerminosCondiciones','ImgReciboDinero','ImgIne',);
            $validation = $this->PrestamosEntregaValidator($params);

            if ($validation->fails()) return response([
                "message" => "parametros invalidos",
                "error"   => $validation->errors()
            ], 400);

            $Prestamo = Prestamo::find($params['IdPrestamo']);

            if(!empty($Prestamo)) {

                $Prestamo->IdEntrego      = $session->IdUsuario;
                $Prestamo->Estatus        = 'Entregado';
                $Prestamo->EstatusEntrega = 'Realizada';
                $Prestamo->FechaEntrega   = date('Y-m-d H:i:s');

                if( floatval($Prestamo->MontoSolicitud) == floatval($params['MontoEntregado']) ) {

                    $Prestamo->MontoEntregado = $params['MontoEntregado'];

                } else if (floatval($params['MontoEntregado']) > 0) {

                    $calculo = CalculaMontosPrestamo::calulaMontos($params['MontoEntregado']);
                    if( $calculo['status'] ) {
                        $Prestamo->NumPagos              = $calculo['data']['diasPrestamo'] ;
                        $Prestamo->MontoDiario           = $calculo['data']['montoDiario'];
                        $Prestamo->TotalDevolverPrestamo = $calculo['data']['montoTotalDevolver'];
                        $Prestamo->TotalMultas           = 0;
                        $Prestamo->TotalGlobal           = $calculo['data']['montoTotalDevolver'];
                        $Prestamo->NumPagoActual         = 0;
                        $Prestamo->MontoEntregado        = $params['MontoEntregado'];
                    }
                }

                if (!$Prestamo->save()) {
                    return response([
                        'status'  => false,
                        'message' => 'Error en actualizar prestamos'
                    ], 500);
                }

            }

            $newArray = array (
                'IdPrestamosPago' => 0,
                'IdPrestamo'      => $params['IdPrestamo'],
                'TipoPrestamo'    => 'Entrega',
                'Comentario'      => $params['ComentariosEntrega'],
                'Fecha'		      => date("Y-m-d"),
            );

            $newPrestamoComentario = new PrestamoxComentario();

            if (!$newPrestamoComentario->insert($newArray)) return response([
                "status"  => false,
                "message" => "Error en Prestamos Comentarios"
            ], 500);

            $ImgPagare = '';
            if ($request->hasFile('ImgPagare')) {
                $Archivo   = FilesManager::UploadFileStorage($request, '/'.$this->rutaPrestamoEntrega,'ImgPagare');
                $ImgPagare = $Archivo['status'] ? $Archivo['HashName'] : '';

                $list[] = array(
                    'IdPrestamo'    => $params['IdPrestamo'],
                    'IdUsuario'     => $session->IdUsuario,
                    'Evidencia'     => $ImgPagare,
                    'TipoEvidencia' => 'ImgPagare',
                    'Etapa'         => 'Entrega',
                    'Anio'          => date('Y'),
                );

            }

            $ImgHojaReferencia = '';
            if ($request->hasFile('ImgHojaReferencia')) {
                $Archivo           = FilesManager::UploadFileStorage($request, '/'.$this->rutaPrestamoEntrega,'ImgHojaReferencia');
                $ImgHojaReferencia = $Archivo['status'] ? $Archivo['HashName'] : '';

                $list[] = array(
                    'IdPrestamo'    => $params['IdPrestamo'],
                    'IdUsuario'     => $session->IdUsuario,
                    'Evidencia'     => $ImgHojaReferencia,
                    'TipoEvidencia' => 'ImgHojaReferencia',
                    'Etapa'         => 'Entrega',
                    'Anio'          => date('Y'),
                );

            }

            $ImgTerminosCondiciones = '';
            if ($request->hasFile('ImgTerminosCondiciones')) {
                $Archivo                = FilesManager::UploadFileStorage($request, '/'.$this->rutaPrestamoEntrega,'ImgTerminosCondiciones');
                $ImgTerminosCondiciones = $Archivo['status'] ? $Archivo['HashName'] : '';

                $list[] = array(
                    'IdPrestamo'    => $params['IdPrestamo'],
                    'IdUsuario'     => $session->IdUsuario,
                    'Evidencia'     => $ImgTerminosCondiciones,
                    'TipoEvidencia' => 'ImgTerminosCondiciones',
                    'Etapa'         => 'Entrega',
                    'Anio'          => date('Y'),
                );

            }

            $ImgReciboDinero = '';
            if ($request->hasFile('ImgReciboDinero')) {
                $Archivo         = FilesManager::UploadFileStorage($request, '/'.$this->rutaPrestamoEntrega,'ImgReciboDinero');
                $ImgReciboDinero = $Archivo['status'] ? $Archivo['HashName'] : '';

                $list[] = array(
                    'IdPrestamo'    => $params['IdPrestamo'],
                    'IdUsuario'     => $session->IdUsuario,
                    'Evidencia'     => $ImgReciboDinero,
                    'TipoEvidencia' => 'ImgReciboDinero',
                    'Etapa'         => 'Entrega',
                    'Anio'          => date('Y'),
                );

            }

            if($request->hasFile('ImgIne')) {
                foreach($request->file('ImgIne') as $element) {
                    $archivo = FilesManager::UploadMultiThumbnailImages($element, '/'.$this->rutaPrestamoEntrega);
                    $ImgIne  = $archivo['status'] ? $archivo['HashName'] : '';

                    $newArray[] = array();
                    $newArray['IdPrestamo']    = $params['IdPrestamo'];
                    $newArray['IdUsuario']     = $session->IdUsuario;
                    $newArray['Evidencia']	   = $ImgIne;
                    $newArray['TipoEvidencia'] = 'ImgIne';
                    $newArray['Etapa']         = 'Entrega';
                    $newArray['Anio']          = date('Y');
                    $IneEvidencia = new PrestamoEvidencia($newArray);

                    if (!$IneEvidencia->save()){
                        return false;
                    }
                }
            }

            foreach($list as $element) {
                $newPrestamoEvidencia = new PrestamoEvidencia($element);
                if (!$newPrestamoEvidencia->save()) {
                    return response([
                        'status'  => false,
                        'message' => 'Error en Prestamo Evidencia'
                    ], 500);
                }

            }

            return response([
                "status"   => true,
                "message"  => false,
                "RutaFile" => URL::to('/').Storage::url($this->rutaPrestamoEntrega.'/'),
            ],200);

        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception,$this->location,'PrestamoEntregaApp');
        }

    }

    public function PrestamosEntregaValidator($params = []) {
        $required = [
            "IdPrestamo"         => "required|numeric",
            "IdCliente"          => "required|numeric",
            "MontoEntregado"     => "required|string",
            "ComentariosEntrega" => "required|string",
        ];

        return validator($params, $required, [
            'required'  => 'El campo es requerido.',
            'string'    => 'El campo es requerido',
        ]);
    }


}
