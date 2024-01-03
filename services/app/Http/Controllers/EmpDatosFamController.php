<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpDatosFam;
use App\Custom\ResponseManager;
use Exception;

class EmpDatosFamController extends Controller
{
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODAS LOS EmpDatosFam DEL SISTEMA
    * METHOD: GET
    */

    public function findAll(Request $req){
        try{
            $EmpDatosFam = EmpDatosFam::get();
            $search='';
            $multiWhere = array();

            $EmpDatosFam = EmpDatosFam::where(function ($query) use ($search) {
                $query->where('Nombre', 'like', '%' . $search. '%');

            })->where($multiWhere)
                ->paginate(
                    $req -> query("Entrada"),
                    "*",
                    "page",
                    $req -> query("Pag")
                );

            if (!empty($EmpDatosFam)) {
                
                $data['EmpDatosFam'] = $EmpDatosFam;

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
    * API PARA EL LISTADO DE UN EmpDatosFam DEL SISTEMA
    * METHOD: GET
    */

    public function findOne($IdEmpdatosFam){
        try {
            $EmpDatosFam = EmpDatosFam::find($IdEmpdatosFam);

            if (!empty($EmpDatosFam)) {
                
                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $EmpDatosFam
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
    * API PARA AGREGAR UN EmpDatosFam AL SISTEMA
    * METHOD: POST
    */

    public function add(Request $request){
        try{
            $params = $request->only(
                "IdEmpleado",
                "Nombre",
                "Domicilio",
                "Telefono",
                "TipoDeDato"
            );
            $validation = $this->EmpDatosFamValidator($params);

            if($validation->fails())return response([
                "message"   => "parametros incorrectos", 
                "errors"    => $validation->errors()
            ],400);
            
            $addEmpDatosFam = new EmpDatosFam($params);

            if(!$addEmpDatosFam->save())return response([
                "status"  => false,
                "message" => 'No se pudo insertar el registro',
            ],500);

            $data['EmpDatosFams'] = $addEmpDatosFam;

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
    * API PARA ACTUALIZAR UN EmpDatosFam AL SISTEMA
    * METHOD: POST
    */

    public function update(int $IdEmpdatosFam, Request $request){
        try {

			$params = $request->only(
                "IdEmpleado",
                "Nombre",
                "Domicilio",
                "Telefono",
                "TipoDeDato"
            );
            $validation = $this->upEmpDatosFamValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$EmpDatosFamUpdate = EmpDatosFam::find($IdEmpdatosFam);

			if (!empty($EmpDatosFamUpdate)) {

				$EmpDatosFamUpdate->IdEmpleado   = $params['IdEmpleado'];
				$EmpDatosFamUpdate->Nombre       = $params['Nombre'];
				$EmpDatosFamUpdate->Domicilio    = $params['Domicilio'];
				$EmpDatosFamUpdate->Telefono     = $params['Telefono'];
				$EmpDatosFamUpdate->Colonia      = $params['Colonia'];
				$EmpDatosFamUpdate->TipoDeDato   = $params['TipoDeDato'];

				if (!$EmpDatosFamUpdate->save()) return response([
					"status"  => false,
					"message" => 'EmpDatosFam no actualizada.'
				], 500);

				$data['EmpDatosFam'] = $EmpDatosFamUpdate;

				return response([
					"status" => true,
					"message" => "EmpDatosFam actualizada.",
					"data" => $data
				], 201);
			} else {

				return response([
					"status"  => true,
					"message" => 'EmpDatosFam no encontrada.',
				], 400);
			}
		} catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    /*
    * API PARA ELIMINAR UN EmpDatosFam AL SISTEMA
    * METHOD: DELETE
    */

    public function delete($IdEmpdatosFam){
        try{
            $response = EmpDatosFam::find($IdEmpdatosFam);

            if(!empty($response)){


                if ($response->delete()) {

					return response([
						"status"  => true,
						"message" => 'Empleado eliminado.'
					], 200);
					
				} else {

					return response([
						"status"  => false,
						"message" => 'La empleado no pudo ser eliminado .',
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

    public function EmpDatosFamValidator($params = [])
	{
		$required = [
			"IdEmpleado"     => "required|numeric",
            "Nombre"         => "required|string",
            "Domicilio"      => "required|string",
			"Telefono"       => "required|string"

		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.'
		]);
	}

    public function upEmpDatosFamValidator($params = [])
	{
		$required = [
			"IdEmpleado"     => "numeric",
            "Nombre"         => "string",
            "Domicilio"      => "string",
			"Telefono"       => "string",
			"TipoDeDato"     => "string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
