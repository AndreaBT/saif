<?php

namespace App\Http\Controllers;

use App\Custom\BitacoraManager;
use App\Custom\ResponseManager;
use App\Models\BitacoraEquipo;
use App\Models\Equipo;
use http\Env\Response;
use Illuminate\Http\Request;

use App\Custom\FilesManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class EquiposController extends Controller
{
    private $location = 'EquiposController';
    private $RutaFoto = 'imgequipos';
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODAS LAS EVIDENCIAS DEL SISTEMA
    * METHOD: GET
    */

    public function findAll(Request $req){
        try{
            $search = '';
            $multiWhere = array();
            if(!empty($req->get('TxtBusqueda'))) {
                $search = $req->get('TxtBusqueda');
            }

            if(!empty($req->get('TipoEquipo'))) {
                $multiWhere[] = array('TipoEquipo','=',$req->get('TipoEquipo'));
            }

            if(!empty($req->get('Asignado'))) {
                $multiWhere[] = array('Asignado','=',$req->get('Asignado'));
            }

            $Equipo = Equipo::where(function ($query) use ($search) {
                $query->where('Nombre', 'like', '%' . $search. '%')
                    ->orWhere('Modelo', 'like', '%' . $search. '%')
                    ->orWhere('Serie', 'like', '%' . $search. '%')
                    ->orWhere('Placa', 'like', '%' . $search. '%');
            })->where($multiWhere)
                ->paginate(
                    $req -> query("Entrada"),
                    "*",
                    "page",
                    $req -> query("Pag")
                );

            if (!empty($Equipo)) {
                return response([
                    "status"    => true,
                    "message"   => "Datos encontrados.",
                    "data"      => $Equipo,
                    "rutaFile"  => URL::to('/').Storage::url($this->RutaFoto.'/'),
                ],200);
            } else {
                return response([
                    "status"  => false,
                    "message" => 'Sin registros',
                ], 404);
            }
        }catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    /*
    * API PARA EL LISTADO DE UN EVIDENCIAS DEL SISTEMA
    * METHOD: GET
    */

    public function findOne($IdEquipo){
        try {
            $Equipo = Equipo::find($IdEquipo);

            if (!empty($Equipo)) {

                return response([
                    "status"    => true,
                    "message"   => "success",
                    "data"      => $Equipo,
                    "rutaFile"  => URL::to('/').Storage::url($this->RutaFoto.'/'),
                ],200);
            } else {
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 404);
            }
        }catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    /*
    * API PARA AGREGAR UN EVIDENCIAS AL SISTEMA
    * METHOD: POST
    */

    public function add(Request $request){
        try{
            $session = JWTAuth::authenticate();
            $params = $request->only(
                "IdEquipo",
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
            );
            $validation = $this->EquiposValidador($params);

            if($validation->fails())return response([
                "message"   => "parametros incorrectos",
                "errors"    => $validation->errors()
            ],400);

            $Equipo = Equipo::find($params['IdEquipo']);

            if (!empty($Equipo))
            {

                ## GENERACION DE BITACORA DE TIPO MODIFICACION PARA EQUIPOS
                $msg = $this->makeTextBitacora($Equipo,$params);
                BitacoraManager::creaBitacoraEquipo($session,$Equipo->IdEquipo,0,'',$msg);


                if ($request->hasFile('Imagen')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->RutaFoto,'Imagen');
                    $Equipo->Imagen = $Archivo['status'] ? $Archivo['HashName'] : '';
                }

                $Equipo->TipoEquipo      = $params['TipoEquipo'];
                $Equipo->Nombre          = $params['Nombre'];
				$Equipo->Marca           = $params['Marca'];
				$Equipo->Modelo          = $params['Modelo'];
				$Equipo->Color           = $params['Color'];
                $Equipo->Asignado        = $params['Asignado'];
                $Equipo->Serie           = ($params['TipoEquipo'] == 1) ? $params['Serie'] : '---';
                $Equipo->Placa           = ($params['TipoEquipo'] == 1) ? $params['Placa'] : '---';
                $Equipo->NumeroEconomico = ($params['TipoEquipo'] == 1) ? $params['NumeroEconomico'] : '---';
                $Equipo->Telefono        = ($params['TipoEquipo'] == 2) ? $params['Telefono'] : '---';
                $Equipo->Imei            = ($params['TipoEquipo'] == 2) ? $params['Imei'] : '---';

            } else {
                #FILE_MANEGER
                if ($request->hasFile('Imagen')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->RutaFoto,'Imagen');
                    $params['Imagen'] = $Archivo['status'] ? $Archivo['HashName'] : '';//OrigiName
                }else {
                    $params['Imagen'] = '';
                }

                // SETER DATA
                if($params['TipoEquipo'] == 1) {
                    $params['Telefono'] = '---';
                    $params['Imei']     = '---';

                }else if($params['TipoEquipo'] == 2){
                    $params['Serie']           = '---';
                    $params['Placa']           = '---';
                    $params['NumeroEconomico'] = '---';
                }
                $params['Anio'] = date('Y-m-d');

                $Equipo = new Equipo($params);

            }

            if(!$Equipo->save())return response([
                "status"  => false,
                "message" => 'No se pudo insertar el registro',
            ],500);

            ## GENERACION DE BITACORA DE TIPO MODIFICACION PARA EQUIPOS
            if($params['IdEquipo'] == 0){
                $msg = 'Creo el equipo';
                BitacoraManager::creaBitacoraEquipo($session,$Equipo->IdEquipo,0,'',$msg);
            }


            $data['Equipo'] = $Equipo;

            return response([
                "status"  => true,
                "message" => false,
                "data"    => $data
            ], 201);

        }catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }

    }


    /*
    * API PARA ELIMINAR UN EVIDENCIAS AL SISTEMA
    * METHOD: DELETE
    */

    public function delete($IdEquipo){
        try{
            $response = Equipo::find($IdEquipo);

            if(!empty($response)){


                if ($response->delete()) {

					return response([
						"status"  => true,
						"message" => 'Equipo eliminado.'
					], 200);

				} else {

					return response([
						"status"  => false,
						"message" => 'El Equipo no pudo ser eliminada .',
					], 500);
				}

            }else{
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 404);
            }

        }catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    /************************************** Funciones ESPECIALES *********************************/

    public function GetTipoDeEquipos(){
        try {
			$array = array();
            $arraydatosEquipo = array();

			$empleadoEquipos = DB::table('equipos')
			->select(
				'equipos.IdEquipo',
				'equipos.Nombre',
				'equipos.Modelo',
				'equipos.Serie',
				'equipos.Placa',
				'equipos.Telefono',
			)
			->where('equipos.TipoEquipo', '=', '1')
			->get();
			array_push($arraydatosEquipo, $empleadoEquipos);
			$data['empleadoEquipos'] = $empleadoEquipos;





			return response([
				"status"    => true,
				"message"   => "success",
				"datosEquiVehiculo" 	=>$empleadoEquipos,
                "data"      => $data,
			]);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception);
		}
    }

    /*
     * API RETORNA UN LISTADO CON LOS REGISTROS DE LA BITACORA DE CADA EQUIPO
     * METHOD: GET
     */
    public function getBitacoraEquipo(Request $request){
        try {
            if(empty($request->get('IdEquipo'))) {
                return response([
                    'status' => false,
                    'message' => 'IdEquipo es un campo obligatorio'
                ], 400);
            }

            $multiWhere     = array();
            $multiWhere[]   = array('IdEquipo', '=', $request->get('IdEquipo'));

            if(!empty($request->get('TipoBitacora'))){
                $multiWhere[] = array('TipoBitacora', '=' ,$request->get('TipoBitacora'));
            }

            $bitacora = BitacoraEquipo::query()
                ->where($multiWhere)
                ->orderBy('IdBitacoraEquipo','DESC')
                ->paginate(
                    $request->query("Entrada"),
                    "*",
                    "page",
                    $request->query("Pag")
                );

            if(!empty($bitacora)) {
                return response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $bitacora
                ],200);

            }else {
                return response([
                    'status'    => false,
                    'message'   => 'Success',
                    'data'      => ''
                ],200);
            }



        }catch (Exception $exception) {
            ResponseManager::setErrorServerResponse($exception,$this->location,'getBitacoraEquipo');
        }
    }

    /*
     * API RETORNA UN LISTADO CON LOS REGISTROS DE LA BITACORA DE CADA EQUIPO
     * METHOD: GET
     */
    public function findOneBitacora(int $IdBitacoraEquipo){
        try {
            if(empty($IdBitacoraEquipo)) {
                return response([
                    'status' => false,
                    'message' => 'IdBitacoraEquipo es un campo obligatorio'
                ], 400);
            }
            $bitacora = BitacoraEquipo::query()
                ->with(array('afectado','equipoMod'))
                ->find($IdBitacoraEquipo);


            if(!empty($bitacora)) {

                return response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $bitacora
                ],200);

            }else {
                return response([
                    'status'    => false,
                    'message'   => 'Success',
                    'data'      => ''
                ],200);
            }



        }catch (Exception $exception) {
            ResponseManager::setErrorServerResponse($exception,$this->location,'getBitacoraEquipo');
        }
    }

    /*
     * FUNCION
     * GENERA EL TEXTO DE LA BITACORA DE EQUIPOS
     */
    public function makeTextBitacora($ObjetoBD,$objetoRequest) {
        try {

            $msg = '';

            if ($ObjetoBD->Nombre != $objetoRequest['Nombre']) {
                $msg .= 'Nombre fue modificado de ' . $ObjetoBD->Nombre . ' a ' . $objetoRequest['Nombre'] . ', ';
            }
            if ($ObjetoBD->Modelo != $objetoRequest['Modelo']) {
                $msg .= 'Modelo fue modificado de ' . $ObjetoBD->Modelo . ' a ' . $objetoRequest['Modelo'] . ', ';
            }

            if ($objetoRequest['TipoEquipo'] == 1) {
                if ($ObjetoBD->Serie != $objetoRequest['Serie']) {
                    $msg .= 'Serie fue modificado de ' . $ObjetoBD->Serie . ' a ' . $objetoRequest['Serie'] . ', ';
                }
                if ($ObjetoBD->Placa != $objetoRequest['Placa']) {
                    $msg .= 'Placa fue modificado de ' . $ObjetoBD->Placa . ' a ' . $objetoRequest['Placa'] . ', ';
                }
            }

            if ($objetoRequest['TipoEquipo'] == 2) {
                if ($ObjetoBD->Telefono != $objetoRequest['Telefono']) {
                    $msg .= 'Telefono fue modificado de ' . $ObjetoBD->Telefono . ' a ' . $objetoRequest['Telefono'] . ', ';
                }
            }

            return $msg;

        }catch (Exception $exception){
            ResponseManager::createLog($exception,$this->location,'makeTextBitacora');
        }
    }

    public function EquiposValidador($params = [])
	{
        if($params['TipoEquipo'] == 1) {
            $required = [
                "Nombre"          => "required|string",
                "Modelo"          => "required|string",
                "Marca"           => "required|string",
                "Color"           => "required|string",
                "Serie"           => "string",
                "Placa"           => "required|string",
                "NumeroEconomico" => "required|string",
                "TipoEquipo"      => "required|string",
            ];
        } else if($params['TipoEquipo'] == 2) {
            $required = [
                "Nombre"     => "required|string",
                "Modelo"     => "required|string",
                "Marca"      => "required|string",
                "Color"      => "required|string",
                "Telefono"   => "required|string",
                "Imei"       => "required|string",
                "TipoEquipo" => "required|string",
            ];
        } else {
            $required = [
                "Nombre"     => "required|string",
                "Marca"      => "required|string",
                "Color"      => "required|string",
                "TipoEquipo" => "required|string",
            ];
        }

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.'
		]);
	}
}
