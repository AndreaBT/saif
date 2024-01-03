<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puesto;
use App\Custom\ResponseManager;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PuestosController extends Controller
{
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODOS LOS PUESTOS DEL SISTEMA
    * METHOD: GET
    */

    public function findAll(Request $req){
        try{
            $search='';
            $multiWhere = array();

            $Puesto = Puesto::where(function ($query) use ($search) {
                $query->where('Nombre', 'like', '%' . $search. '%');

            })->where($multiWhere)
                ->paginate(
                    $req -> query("Entrada"),
                    "*",
                    "page",
                    $req -> query("Pag")
                );

            if (!empty($Puesto)) {
                
                $data['Puesto'] = $Puesto;

                return response([
                    "status" => true,
                    "message" => "Datos encontrados.",
                    "data"    => $data,
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
     * API PARA LA BUSQUEDA DE PUESTOS ESPECIFICOS DEL SISTEMA
     * METHOD: GET
    */

    public function findOne($id) {
        try {
            $Puesto = Puesto::find($id);

            if( !empty($Puesto) ) {

                return response([
                    "status"    => true,
                    "message"   => "success",
                    "data"      => $Puesto,
                ]);

            }else {
                return response([
                    "status"    => false,
                    "message"   => 'Registro no encontrado',
                ],404);
            }

        }catch (Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

     /*
     * API PARA AGREGAR Puestos AL SISTEMA
     * METHOD: POST
     */
    public function add(Request $request) {
        try {
            $creador = JWTAuth::authenticate();

            $params = $request->only(
                "Nombre",   
            );

            $validation = $this->puestoValidator($params,0);

            if ($validation->fails()) return response([
                "message"   => "parametros invalidos",
                "errors"    => $validation->errors()
            ],400);

            #SETER DATA DEFAULT
            $params['IdSucursal']      = $creador->IdSucursal;
           

            $addPuesto = new Puesto($params);

            if(!$addPuesto->save()) return response([
                "status"    => false,
                "message"   => 'Puesto no creado',
            ],500);

           
            $data['Puesto']     = $addPuesto;
           
            return response([
                "status"    => true,
                "message"   => "success",
                "data"      => $data
            ],201);

        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    
    /*
    * API PARA MODIFICAR INFORMACIÓN DE UN PUESTO EN ESPECIFICO
    * METHOD: PUT
    */

    public function update(int $IdPuesto, Request $request){
        try {

			$params = $request->only(
               'Nombre'
            );
            $validation = $this->puestoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$PuestoUpdate = Puesto::find($IdPuesto);

			if (!empty($PuestoUpdate)) {

				$PuestoUpdate->Nombre  = $params['Nombre'];
				

				if (!$PuestoUpdate->save()) return response([
					"status"  => false,
					"message" => 'Puesto no actualizado.'
				], 500);

				$data['Puesto'] = $PuestoUpdate;

				return response([
					"status" => true,
					"message" => "Puesto actualizado.",
					"data" => $data
				], 201);
			} else {

				return response([
					"status"  => true,
					"message" => 'Puesto no encontrado.',
				], 400);
			}
		} catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

      /*
    * API PARA ELIMINAR UN PUESTO EN ESPECIFICO
    * METHOD: DELETE
    */

    public function delete($IdPuesto){
        try{
            $response = Puesto::find($IdPuesto);

            if(!empty($response)){
                if ($response->delete()) {

					return response([
						"status"  => true,
						"message" => 'Puesto eliminado.'
					], 200);
					
				} else {

					return response([
						"status"  => false,
						"message" => 'El puesto no pudo ser eliminado .',
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

    public function puestoValidator($params = [])
	{
		$required = [
			"Nombre"    => "required|string",
            
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.'
		]);
	}

    public function upPuestoValidator($params = [])
	{
		$required = [
			"Nombre"               => "string",
           
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}


    
}
