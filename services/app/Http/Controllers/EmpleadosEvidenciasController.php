<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpleadosEvidencias;
use App\Custom\ResponseManager;
use Exception;

class EmpleadosEvidenciasController extends Controller
{
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODAS LAS EVIDENCIAS DEL SISTEMA
    * METHOD: GET
    */

    public function findAll(Request $req){
        try{
            // $EmpleadosEvidencias = EmpleadosEvidencias::get();
            $search='';
            $multiWhere = array();

            if(!empty($req->get('IdEmpleado'))<=0) {
                return response([
                    "status"  => false,
                    "message" => 'Id Empleado no recibido',
                ], 404);

            }

            $EmpleadosEvidencias = EmpleadosEvidencias::get();
                $data['EmpleadosEvidencias'] = $EmpleadosEvidencias;
                
                return response([
                    "status" => true,
                    "message" => "Datos encontrados.",
                    "data"    => $data,
                ],200);
            
            
        }catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    /*
    * API PARA EL LISTADO DE UN EVIDENCIAS DEL SISTEMA
    * METHOD: GET
    */

    public function findOne($IdEmpleadoEvidencia){
        try {
            $EmpleadosEvidencias = EmpleadosEvidencias::find($IdEmpleadoEvidencia);

            if (!empty($EmpleadosEvidencias)) {
                
                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $EmpleadosEvidencias
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
            $params = $request->only(
                "IdEmpleado",
                "Evidencia",
                "Anio"
            );
            $validation = $this->EmpEvidencia($params);

            if($validation->fails())return response([
                "message"   => "parametros incorrectos", 
                "errors"    => $validation->errors()
            ],400);
            
            $addEmpleadosEvidencia = new EmpleadosEvidencias($params);

            if(!$addEmpleadosEvidencia->save())return response([
                "status"  => false,
                "message" => 'No se pudo insertar el registro',
            ],500);

            $data['EmpleadosEvidencia'] = $addEmpleadosEvidencia;

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
    * API PARA ACTUALIZAR UN EVIDENCIAS AL SISTEMA
    * METHOD: PUT
    */

    public function update(int $IdEmpleadoEvidencia, Request $request){
        try {

			$params = $request->only(
                "IdEmpleado",
                "Evidencia",
                "Anio"
            );
            $validation = $this->upEmpEmpEvidencia($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$EmpleadosEvidenciasUpdate = EmpleadosEvidencias::find($IdEmpleadoEvidencia);

			if (!empty($EmpleadosEvidenciasUpdate)) {

				$EmpleadosEvidenciasUpdate->IdEmpleado  = $params['IdEmpleado'];
				$EmpleadosEvidenciasUpdate->Evidencia   = $params['Evidencia'];
				$EmpleadosEvidenciasUpdate->Anio        = $params['Anio'];

				if (!$EmpleadosEvidenciasUpdate->save()) return response([
					"status"  => false,
					"message" => 'Herramienta no actualizada.'
				], 500);

				$data['EmpleadosEvidencias'] = $EmpleadosEvidenciasUpdate;

				return response([
					"status" => true,
					"message" => "Herramienta actualizada.",
					"data" => $data
				], 201);
			} else {

				return response([
					"status"  => true,
					"message" => 'Herramienta no encontrada.',
				], 400);
			}
		} catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    /*
    * API PARA ELIMINAR UNA EVIDENCIA DEL SISTEMA
    * METHOD: DELETE
    */

    public function delete($IdEmpleadoEvidencia){
        try{
            $response = EmpleadosEvidencias::find($IdEmpleadoEvidencia);

            if(!empty($response)){


                if ($response->delete()) {

					return response([
						"status"  => true,
						"message" => 'Eviencia eliminado.'
					], 200);
					
				} else {

					return response([
						"status"  => false,
						"message" => 'La Evidencia no pudo ser eliminada .',
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

    public function EmpEvidencia($params = [])
	{
		$required = [
			"IdEmpleado"  => "required|numeric",
            "Evidencia"   => "required|string",
			"Anio"        => "required|string"

		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.'
		]);
	}

    public function upEmpEmpEvidencia($params = [])
	{
		$required = [
			"IdEmpleado"   => "numeric",
            "Evidencia"    => "string",
			"Anio"         => "string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
