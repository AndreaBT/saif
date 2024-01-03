<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Custom\ResponseManager;
use Exception;

use App\Custom\FilesManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class EmpresasController extends Controller
{
    private $RutaFoto = 'imgempresa';
    /***************************FUNCIONES CRUD**************************** */

    /*
    * API PARA EL LISTADO DE TODAS LAS EMPRESAS DEL SISTEMA
    * METHOD: GET
    */
    public function findAll(Request $req){
        try {

            $search='';
            $multiWhere = array();

            //!Busqueda por coincidencias.
            $Empresa = Empresa::where(function ($query) use ($search) {
                $query->where('NombreComercial', 'like', '%' . $search. '%')
                    ->orWhere('RazonSocial', 'like', '%' . $search. '%');

            })->where($multiWhere)
                ->paginate(
                    $req -> query("Entrada"),
                    "*",
                    "page",
                    $req -> query("Pag")
                );

            if (!empty($Empresa)) {

                $data['empresas'] = $Empresa;

                return response([
                    "status" => true,
                    "message" => "Empresa encontradas.",
                    "data"    => $data,
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
    * API PARA EL LISTADO DE UNA EMPRESAS DEL SISTEMA
    * METHOD: GET
    */

    public function findOne($IdEmpresa){
        try {
            $Empresa = Empresa::find($IdEmpresa);

            if (!empty($Empresa)) {

                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $Empresa,
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
    * API PARA AGREGAR EMPRESAS AL SISTEMA
    * METHOD: POST
    */

    public function add(Request $request){
        try{
            $params = $request->only(
                'NombreComercial',
                'RazonSocial',
                'Rfc',
                'Calle',
                'NoInt',
                'NoExt',
                'Colonia',
                'Cp',
                'Referencias',
                'Telefono'
            );
            $validation = $this->empresasValidator($params);

            if($validation->fails())return response([
                "message"   => "parametros incorrectos",
                "errors"    => $validation->errors()
            ],400);

            $addEmpresa = new Empresa($params);

            if(!$addEmpresa->save())return response([
                "status"  => false,
                "message" => 'No se pudo insertar el registro',
            ],500);

            $data['empresas'] = $addEmpresa;

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
    * API PARA MODIFICAR INFORMACIÓN DE UNA EMPRESAS EN ESPECIFICO
    * METHOD: PUT
    */


    public function update(Request $request)
	{
		try {

			$params = $request->only(
                'IdEmpresa',
                'NombreComercial',
                'RazonSocial',
                'Rfc',
                'Calle',
                'NoInt',
                'NoExt',
                'Colonia',
                'Cp',
                'Referencias',
                'Telefono',
                'Imagen'
            );
            $validation = $this->upEmpresaValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$empresaUpdate = Empresa::find($params['IdEmpresa']);

			if (!empty($empresaUpdate)) {

				$empresaUpdate->NombreComercial = $params['NombreComercial'];
				$empresaUpdate->RazonSocial     = $params['RazonSocial'];
				$empresaUpdate->Rfc             = $params['Rfc'];
				$empresaUpdate->Calle           = $params['Calle'];
				$empresaUpdate->NoInt           = $params['NoInt'];
				$empresaUpdate->NoExt           = $params['NoExt'];
				$empresaUpdate->Colonia         = $params['Colonia'];
				$empresaUpdate->Cp              = $params['Cp'];
				$empresaUpdate->Referencias     = $params['Referencias'];
				$empresaUpdate->Telefono        = $params['Telefono'];

                if ($request->hasFile('Imagen')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->RutaFoto,'Imagen');
                    $empresaUpdate->Imagen = $Archivo['status'] ? $Archivo['HashName'] : '';
                }



				if (!$empresaUpdate->save()) return response([
					"status"  => false,
					"message" => 'Empresa no actualizada.'
				], 500);

				$data['empresa'] = $empresaUpdate;

				return response([
					"status" => true,
					"message" => "Empresa actualizada.",
					"data" => $data
				], 201);
			} else {

				return response([
					"status"  => true,
					"message" => 'Empresa no encontrada.',
				], 400);
			}
		} catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }

	}

    /*
    * API PARA ELIMINAR UNA EMPRESA EN ESPECIFICO
    * METHOD: DELETE
    */

    public function delete($IdEmpresa){
        try{
            $response = Empresa::find($IdEmpresa);

            if(!empty($response)){
                if ($response->delete()) {

					return response([
						"status"  => true,
						"message" => 'Empresa eliminada.'
					], 200);

				} else {

					return response([
						"status"  => false,
						"message" => 'La Empresa no pudo ser eliminada .',
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

    public function empresasValidator($params = [], $IdEmpresa = 0)
	{
		$required = [
			"NombreComercial"   => "required|string",
            "RazonSocial"       => "required|string",
            "Rfc"               => "required|string",
			"Calle"             => "required|string",
			"NoExt"             => "required|string",
			"Colonia"           => "required|string",
			"Cp"                => "required|string",
			"Referencias"       => "required|string",
			"Telefono"          => "required|string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}

    public function upEmpresaValidator($params = [], $IdEmpresa = 0)
	{
		$required = [
			"NombreComercial"         => "string",
            "RazonSocial"             => "string",
            "Rfc"                     => "string",
			"Calle"                   => "string",
            "NoExt"                   => "string",
			"Colonia"                 => "string",
			"Cp"                      => "string",
			"Referencias"             => "string",
			"Telefono"                => "string",
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}

}
