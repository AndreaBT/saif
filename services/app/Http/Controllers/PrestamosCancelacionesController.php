<?php

namespace App\Http\Controllers;

use App\Custom\FilesManager;
use App\Custom\ResponseManager;
use App\Models\Prestamo;
use App\Models\PrestamoxCancelacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Facades\JWTAuth;

class PrestamosCancelacionesController extends Controller
{
    private $location                = 'PrestamosCancelacionesController';
    private $rutaPrestamoCancelacion = 'imgprestamocancelacion';

    public function findOne(Request $request) {
        try {
            $params = $request->only(
                'Id',
                'TipoCancelacion'
            );

            if(empty($params['Id'])){
                return response([
                   'status'     => true,
                   'message'    => 'parametros incompletos',
                ],400);
            }

            $multiWhere = array();
            if( !empty($params['TipoCancelacion']) ) {
                $multiWhere[] = array('TipoCancelacion','=',$params['TipoCancelacion']);
            }

            $PrestamoCancelacion = PrestamoxCancelacion::query()
                ->where($multiWhere)
                ->where('IdPrestamoxCancelacion','=', $params['Id'])
                ->first();

            if( !empty($PrestamoCancelacion) ) {
                return response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $PrestamoCancelacion,
                    'rutaImagen' => URL::to('/') . Storage::url($this->rutaPrestamoCancelacion."/") ,
                ],200);
            }else {
                return response([
                    'status'    => false,
                    'message'   => 'Registro no encontrado',
                    'data'      => ''
                ],400);
            }

        }catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception,$this->location,'findOne');
        }
    }

    /*
     * API PARA FUNCION DE CANCELAR PRESTAMO
     * METHOD: POST
    */
    public function PrestamosCancelacionApp(Request $request) {
        try {

            $session = JWTAuth::authenticate();
            $params  = $request->only(
                "IdPrestamo",
                "MotivoCancelacion",
                "Imagen1",
                "Imagen2",
                "TipoCancelacion");


            $validation = $this->PrestamosCancelacionValidador($params);

            if ($validation->fails()) return response([
                "message" => "parametros invalidos",
                "errors"  => $validation->errors()
            ], 400);

            $Imagen1 = '';
            if ($request->hasFile('Imagen1')) {
                $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaPrestamoCancelacion,'Imagen1');
                $Imagen1 = $Archivo['status'] ? $Archivo['HashName'] : '';
            }


            $Imagen2 = '';
            if ($request->hasFile('Imagen2')) {
                $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaPrestamoCancelacion,'Imagen2');
                $Imagen2 = $Archivo['status'] ? $Archivo['HashName'] : '';
            }

            $newArray = array (
                'IdPrestamo'		=> $params['IdPrestamo'],
                'IdUsuario'			=> $session->IdUsuario,
                'Imagen1'			=> $Imagen1,
                'Imagen2'			=> $Imagen2,
                'MotivoCancelacion'	=> $params['MotivoCancelacion'],
                'TipoCancelacion'	=> $params['TipoCancelacion'],
            );

            $newCancelacion = new PrestamoxCancelacion($newArray);

            if (!$newCancelacion->save()){
                return response([
                    'status' => false,
                    'message' => 'Error en cancelacion'
                ],500);
            }


            $Prestamo = Prestamo::find($params['IdPrestamo']);
            if(!empty($Prestamo)) {

                switch ($params['TipoCancelacion']) {

                    ## CANCELACION DE TIPO 1 - REPRESENTA UNA CANCELACION DE ENTREGA.
                    ## CONYEVA A GENERAR UN REGISTRO QUE CONTENGA UN MOTIVO ASI COMO UN MAXIMO DE 2 FOTOGRAFIAS DE EVIDENCIA
                    ## UNICAMENTE SE CAMBIA EL ESTATUS DE ENTREGA DEL PRESTAMO Y SE GENERA REGISTRO DE ESTE TIPO DE CANCELACION.
                    case 1:

                        // ACTUALIZAMOS LOS CAMPOS DE IDPRESTAMOXCANCELACION Y ESTATUS EstatusEntrega HACIENDO UN UPDATE A LA TABLA DE PRESTAMOS

                        $Prestamo->IdPrestamoxCancelacion = $newCancelacion->IdPrestamoxCancelacion;
                        $Prestamo->EstatusEntrega         = 'Cancelada';

                        if (!$Prestamo->save()) {
                            return response([
                                'status'  => false,
                                'message' => 'Error en actualizar cancelacion de prestamo'
                            ], 500);
                        }

                        break;

                    ## CANCELACION DE TIPO 2 - REPRESENTA UNA CANCELACION TOTAL DEL PRESTAMO.
                    ## CONYEVA A GENERAR UN REGISTRO QUE CONTENGA UN MOTIVO ASI COMO UN MAXIMO DE 2 FOTOGRAFIAS DE EVIDENCIA
                    ## SE CAMBIA EL ESTATUS DE ENTREGA DEL PRESTAMO, ESTATUS PROPIO DEL PRESTAMO Y SE GENERA REGISTRO DE ESTE TIPO DE CANCELACION.
                    case 2:
                        $Prestamo->IdPrestamoxCancelacion = $newCancelacion->IdPrestamoxCancelacion;
                        $Prestamo->Estatus                = 'Cancelado';
                        $Prestamo->EstatusEntrega         = 'Cancelada';

                        if (!$Prestamo->save()) {
                            return response([
                                'status'  => false,
                                'message' => 'Error en actualizar cancelacion de prestamo'
                            ], 500);
                        }

                        break;
                }
            }

            return response([
                "status"   => true,
                "message"  => false,
                "RutaFile" => URL::to('/').Storage::url($this->rutaPrestamoCancelacion.'/'),
                "data"     => $newCancelacion
            ],200);

        } catch (\Exception $exception){
            return ResponseManager::setErrorServerResponse($exception,$this->location,'PrestamosCancelacionApp');
        }

    }

    // VALIDACION PARA PRESTAMOS CANCELADOS
    public function PrestamosCancelacionValidador($CancelacionData = []) {
        $required = [
            "IdPrestamo" 			=> "required|numeric",
            "MotivoCancelacion"     => "required|string",
            "TipoCancelacion"       => "required|numeric",
        ];

        return validator($CancelacionData, $required, [
            'required'  => 'El campo es requerido.',
            'string'    => 'El campo es requerido.',
        ]);
    }
}
