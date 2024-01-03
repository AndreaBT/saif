<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpleadosRefPersonales;
use App\Custom\ResponseManager;
use Exception;

class EmpleadosRefPersonalesController extends Controller
{
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODAS LOS EmpleadosRefPersonales DEL SISTEMA
    * METHOD: GET
    */

    public function findAll(Request $req){
        try{
            $EmpleadosRefPersonales = EmpleadosRefPersonales::get();
            $search='';
            $multiWhere = array();

            $EmpleadosRefPersonales = EmpleadosRefPersonales::where(function ($query) use ($search) {
                $query->where('NombreRef', 'like', '%' . $search. '%');

            })->where($multiWhere)
                ->paginate(
                    $req -> query("Entrada"),
                    "*",
                    "page",
                    $req -> query("Pag")
                );

            if (!empty($EmpleadosRefPersonales)) {
                
                $data['EmpleadosRefPersonales'] = $EmpleadosRefPersonales;

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
    * API PARA EL LISTADO DE UN EmpleadosRefPersonales DEL SISTEMA
    * METHOD: GET
    */

    public function findOne($IdEmpRefPer){
        try {
            $EmpleadosRefPersonales = EmpleadosRefPersonales::find($IdEmpRefPer);

            if (!empty($EmpleadosRefPersonales)) {
                
                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $EmpleadosRefPersonales
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
    * API PARA AGREGAR UN EmpleadosRefPersonales AL SISTEMA
    * METHOD: POST
    */

    public function add(Request $request){
        try{
            $params = $request->only(
                "IdEmpleado",
                "NombreRef",
                "TelefonoRef"
            );
            $validation = $this->EmpDatosRefValidator($params);

            if($validation->fails())return response([
                "message"   => "parametros incorrectos", 
                "errors"    => $validation->errors()
            ],400);
            
            $addEmpDatosRef = new EmpleadosRefPersonales($params);

            if(!$addEmpDatosRef->save())return response([
                "status"  => false,
                "message" => 'No se pudo insertar el registro',
            ],500);

            $data['EmpDatosRef'] = $addEmpDatosRef;

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
    * API PARA ACTUALIZAR UN EmpleadosRefPersonales AL SISTEMA
    * METHOD: POST
    */

    public function update(int $IdEmpRefPer, Request $request){
        try {

			$params = $request->only(
                "IdEmpleado",
                "NombreRef",
                "TelefonoRef"
            );
            $validation = $this->upEmpDatosRefValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$EmpDatosRefUpdate = EmpleadosRefPersonales::find($IdEmpRefPer);

			if (!empty($EmpDatosRefUpdate)) {

				$EmpDatosRefUpdate->IdEmpleado   = $params['IdEmpleado'];
				$EmpDatosRefUpdate->Nombre       = $params['Nombre'];
				$EmpDatosRefUpdate->Telefono     = $params['Telefono'];

				if (!$EmpDatosRefUpdate->save()) return response([
					"status"  => false,
					"message" => 'EmpDatosFam no actualizada.'
				], 500);

				$data['EmpDatosRef'] = $EmpDatosRefUpdate;

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
    * API PARA ELIMINAR UN EmpleadosRefPersonales AL SISTEMA
    * METHOD: DELETE
    */  

    public function delete($IdEmpRefPer){
        try{
            $response = EmpleadosRefPersonales::find($IdEmpRefPer);

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

    public function EmpDatosRefValidator($params = [])
	{
		$required = [
			"IdEmpleado"     => "required|numeric",
            "NombreRef"         => "required|string",
			"TelefonoRef"       => "required|string"

		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.'
		]);
	}

    public function upEmpDatosRefValidator($params = [])
	{
		$required = [
			"IdEmpleado"     => "numeric",
            "NombreRef"         => "string",
			"TelefonoRef"       => "string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
