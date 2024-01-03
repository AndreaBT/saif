<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpleadosHerramientas;
use App\Custom\ResponseManager;
use Exception;

class EmpleadosHerramientasController extends Controller
{
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODAS LAS HERRAMIENTAS DEL SISTEMA
    * METHOD: GET
    */

    public function findAll(Request $req){
        try{
            $search='';
            $multiWhere = array();

            $EmpleadosHerramientas = EmpleadosHerramientas::where(function ($query) use ($search) {
                $query->where('TipoHerramienta', 'like', '%' . $search. '%');

            })->where($multiWhere)
                ->paginate(
                    $req -> query("Entrada"),
                    "*",
                    "page",
                    $req -> query("Pag")
                );

            if (!empty($EmpleadosHerramientas)) {
                
                $data['EmpleadosHerramientas'] = $EmpleadosHerramientas;

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

    public function findOne($IdEmpleadoHerramienta){
        try {
            $EmpleadosHerramientas = EmpleadosHerramientas::find($IdEmpleadoHerramienta);

            if (!empty($EmpleadosHerramientas)) {
                
                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $EmpleadosHerramientas
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
                "Descripcion",
                "TipoHerramienta",
                "FechaEntrega"
            );
            $validation = $this->EmpDatosHerramientas($params);

            if($validation->fails())return response([
                "message"   => "parametros incorrectos", 
                "errors"    => $validation->errors()
            ],400);
            
            $addEmpleadosHerramientas = new EmpleadosHerramientas($params);

            if(!$addEmpleadosHerramientas->save())return response([
                "status"  => false,
                "message" => 'No se pudo insertar el registro',
            ],500);

            $data['EmpleadosHerramientas'] = $addEmpleadosHerramientas;

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
    * API PARA ACTUALIZAR UNA HERRAMIENTA DEL SISTEMA
    * METHOD: POST
    */

    public function update(int $IdEmpleadoHerramienta, Request $request){
        try {

			$params = $request->only(
                "IdEmpleado",
                "Descripcion",
                "TipoHerramienta",
                "FechaEntrega"
            );
            $validation = $this->upEmpDatosHerramientas($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$EmpleadosHerramientasUpdate = EmpleadosHerramientas::find($IdEmpleadoHerramienta);

			if (!empty($EmpleadosHerramientasUpdate)) {

				$EmpleadosHerramientasUpdate->IdEmpleado        = $params['IdEmpleado'];
				$EmpleadosHerramientasUpdate->Descripcion       = $params['Descripcion'];
				$EmpleadosHerramientasUpdate->TipoHerramienta   = $params['TipoHerramienta'];
                $EmpleadosHerramientasUpdate->FechaEntrega      = $params['FechaEntrega'];

				if (!$EmpleadosHerramientasUpdate->save()) return response([
					"status"  => false,
					"message" => 'Herramienta no actualizada.'
				], 500);

				$data['EmpleadosHerramientas'] = $EmpleadosHerramientasUpdate;

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
    * API PARA ELIMINAR UNA HERRAMIENTA DEL SISTEMA
    * METHOD: DELETE
    */

    public function delete($IdEmpleadoHerramienta){
        try{
            $response = EmpleadosHerramientas::find($IdEmpleadoHerramienta);

            if(!empty($response)){


                if ($response->delete()) {

					return response([
						"status"  => true,
						"message" => 'Herramienta eliminado.'
					], 200);
					
				} else {

					return response([
						"status"  => false,
						"message" => 'La Herramienta no pudo ser eliminada .',
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

    public function EmpDatosHerramientas($params = [])
	{
		$required = [
			"IdEmpleado"     => "required|numeric",
            "Descripcion"         => "required|string",
			"TipoHerramienta"       => "required|string"

		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.'
		]);
	}

    public function upEmpDatosHerramientas($params = [])
	{
		$required = [
			"IdEmpleado"     => "numeric",
            "Descripcion"    => "string",
			"TipoHerramienta"=> "string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
