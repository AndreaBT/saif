<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Custom\ResponseManager;
use Exception;

class EmpleadosController extends Controller
{
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODAS LOS EMPLEADOS DEL SISTEMA
    * METHOD: GET
    */

    public function findAll(Request $req){
        try{

            $search='';
            $multiWhere = array();

            $Empleado = Empleado::where(function ($query) use ($search) {
                $query->where('Rfc', 'like', '%' . $search. '%');
            })->where($multiWhere)
            ->paginate(
                $req -> query("Entrada"),
                "*",
                "page",
                $req -> query("Pag")
            );

            if (!empty($Empleado)) {
                
                $data['Empleados'] = $Empleado;

                return response([
                    "status" => true,
                    "message" => "Empleado encontradas.",
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
    * API PARA EL LISTADO DE UN EMPLEADO DEL SISTEMA
    * METHOD: GET
    */

    public function findOne($IdEmpleado){
        try {
            $Empleado = Empleado::find($IdEmpleado);

            if (!empty($Empleado)) {
                
                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $Empleado
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
    * API PARA AGREGAR UN EMPLEADO AL SISTEMA
    * METHOD: POST
    */

    public function add(Request $request){
        try{
            $params = $request->only(
                "Rfc",
                "Calle",
                "NoInt",
                "NoExt",
                "Colonia",
                "Cp",
                "Referencias",
                "FechaNacimiento",
                "EstadoCivil",
                "FechaAlta",
                "FechaBaja",
                "FechaEntrega",
                "Finiquito",
                "FechaFiniquito"
            );
            $validation = $this->empleadoValidator($params);

            if($validation->fails())return response([
                "message"   => "parametros incorrectos", 
                "errors"    => $validation->errors()
            ],400);
            
            $addEmpleado = new Empleado($params);

            #SETER DATA DEFAULT (MOMENTANIO)
            $params['FechaNacimiento']      = !empty($params['FechaNacimiento']) ? $params['FechaNacimiento'] : null;
            $params['FechaAlta']      = !empty($params['FechaAlta']) ? $params['FechaAlta'] : null;
            $params['FechaBaja']      = !empty($params['FechaBaja']) ? $params['FechaBaja'] : null;
            $params['FechaEntrega']      = !empty($params['FechaEntrega']) ? $params['FechaEntrega'] : null;
            $params['FechaFiniquito']      = !empty($params['FechaFiniquito']) ? $params['FechaFiniquito'] : null;

            if(!$addEmpleado->save())return response([
                "status"  => false,
                "message" => 'No se pudo insertar el registro',
            ],500);

            $data['empleados'] = $addEmpleado;

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
    * API PARA MODIFICAR INFORMACIÓN DE UN EMPLEADO EN ESPECIFICO
    * METHOD: PUT
    */

    public function update(int $IdEmpleado, Request $request){
        try {

			$params = $request->only(
                'Rfc',
                'Calle',
                'NoInt',
                'NoExt',
                'Colonia',
                'Cp',
                'Referencias',
                'FechaNacimiento',
                'EstadoCivil',
                'FechaAlta',
                'FechaBaja',
                'FechaEntrega',
                'FechaAlta',
                'Finiquito',
                'FechaFiniquito'
            );
            $validation = $this->upEmpleadoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$empleadoUpdate = Empleado::find($IdEmpleado);

			if (!empty($empleadoUpdate)) {

				$empleadoUpdate->Rfc            = $params['Rfc'];
				$empleadoUpdate->Calle          = $params['Calle'];
				$empleadoUpdate->NoInt          = $params['NoInt'];
				$empleadoUpdate->NoExt          = $params['NoExt'];
				$empleadoUpdate->Colonia        = $params['Colonia'];
				$empleadoUpdate->Cp             = $params['Cp'];
				$empleadoUpdate->Referencias    = $params['Referencias'];
				$empleadoUpdate->FechaNacimiento= $params['FechaNacimiento'];
				$empleadoUpdate->EstadoCivil    = $params['EstadoCivil'];
				$empleadoUpdate->FechaAlta      = $params['FechaAlta'];
                $empleadoUpdate->FechaBaja      = $params['FechaBaja'];
                $empleadoUpdate->FechaEntrega   = $params['FechaEntrega'];
                $empleadoUpdate->Finiquito      = $params['Finiquito'];
                $empleadoUpdate->FechaFiniquito = $params['FechaFiniquito'];

				if (!$empleadoUpdate->save()) return response([
					"status"  => false,
					"message" => 'Empleado no actualizada.'
				], 500);

				$data['Empleado'] = $empleadoUpdate;

				return response([
					"status" => true,
					"message" => "Empleado actualizada.",
					"data" => $data
				], 201);
			} else {
				return response([
					"status"  => true,
					"message" => 'Empleado no encontrada.',
				], 400);
			}
		} catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

     /*
    * API PARA ELIMINAR UN EMPlEADO EN ESPECIFICO
    * METHOD: DELETE
    */

    public function delete($IdEmpleado){
        try{
            $response = Empleado::find($IdEmpleado);

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

    public function empleadoValidator($params = [])
	{
		$required = [
			"Finiquito"         => "required|string",
            "Calle"             => "required|string",
            "NoExt"             => "required|string",
			"Cp"                => "required|string",
			"EstadoCivil"       => "required|string",
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.'
		]);
	}

    public function upEmpleadoValidator($params = [])
	{
		$required = [
			"Rfc"               => "string",
            "Calle"             => "string",
            "NoExt"             => "string",
			"Colonia"           => "string",
			"Cp"                => "string",
			"Referencias"       => "string",
			"EstadoCivil"       => "string",
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
