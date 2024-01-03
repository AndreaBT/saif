<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\Municipio;
use Illuminate\Http\Request;

use Exception;

class MunicipiosController extends Controller
{
	/*
	 * Funciones CRUD
	 *
	*/

	/*
     * API PARA LISTADO DE TODOS LOS MUNICIPIOS
     * METHOD: GET
	*/
	public function findAll(Request $request)
	{
		try {
			$multiwhere = array();

			if (!empty($request->get('IdEstado'))) {
				$multiwhere[] = array("IdEstado", '=', $request->get('IdEstado'));
			}

            $municipio = Municipio::where($multiwhere)->get();

			return response([
				"status" 	=> true,
				"message"	=> "Success",
				"data"		=> $municipio,
			], 200);

		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE MUNICIPIOS ESPECÍFICOS
     * METHOD: GET
	*/
	public function findOne($IdMunicipio)
	{
		try {
			$municipio = Municipio::find($IdMunicipio);

			if (!empty($municipio)) {
				return response([
					"status"  => true,
					"message" => "Municipio encontrado.",
					"data"    => $municipio
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Municipio no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA AGREGAR MUNICIPIOS
     * METHOD: POST
	*/
	public function add(Request $request)
	{
		try {
			$params = $request->only(
				"IdEstado",
				"Nombre"
			);

			$validation = $this->addMunicipioValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$municipio = new Municipio($params);

			if (!$municipio->save()) return response([
				"status" => false,
				"message"   => "Muncipio no creado."
			], 500);

			return response([
				"status" 	=> true,
				"message"   => "Muncipio creado.",
				"data"		=> $municipio
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UN MUNICIPIO
     * METHOD: PUT
	*/
	public function update(Request $request, int $IdMunicipio)
	{
		try {
			$params = $request->only(
				"IdEstado",
				"Nombre"
			);

			$validation = $this->addMunicipioValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$municipio = Municipio::find($IdMunicipio);

			if (!empty($Municipio))
			{
				$municipio->IdEstado 	= $params['IdEstado'];
				$municipio->Nombre 	= $params['Nombre'];

				if (!$municipio->save()) return response([
					"status"  => false,
					"message" => 'Municipio no actualizado.'
				], 500);

				return response([
					"status" => true,
					"message" => "Municipio actualizado.",
					"data" => $municipio
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Municipio no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA ELIMINAR UN MUNICIPIO
     * METHOD: DELETE
	*/
	public function delete($IdMunicipio)
	{
		try {
			$respose = Municipio::find($IdMunicipio);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Municipio eliminado.'
					], 200);
				} else {
					return response([
						"status"  => false,
						"message" => 'Municipio no pudo ser eliminado.',
					], 500);
				}
			}
			else
			{
				return response([
					"status"  => false,
					"message" => 'Municipio no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     ********************** FUNCIONES DE VALIDACIÓN *********************************
     *
	*/
	public function addMunicipioValidator($params = [], $IdMunicipio = 0)
	{
		$required = [
			"IdEstado" 		=> "required|numeric",
			"Nombre" 		=> "required|string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
