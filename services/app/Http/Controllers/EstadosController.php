<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\Estado;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use Exception;

class EstadosController extends Controller
{
	/*
	 * Funciones CRUD
	 *
	 */

	/*
     * API PARA LISTADO DE TODOS LOS ESTADOS
     * METHOD: GET
     */
	public function findAll(Request $request)
	{
		try {

			$sesion = JWTAuth::authenticate();

			$multiwhere = array();

			if ($sesion->IdPais != null) {
				$multiwhere[] = array("IdPais", '=', $sesion->IdPais);
			}

            $estado = Estado::where($multiwhere)->get();

			return response([
				"status" 	=> true,
				"message"	=> "Success",
				"data"		=> $estado,
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE ESTADOS ESPECÍFICOS
     * METHOD: GET
     */
	public function findOne($IdEstado)
	{
		try {

			$estado = Estado::find($IdEstado);

			if (!empty($estado)) {

				return response([
					"status"  => true,
					"message" => "Estado encontrado.",
					"data"    => $estado
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Estado no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {

			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA AGREGAR ESTADOS 
     * METHOD: POST
     */
	public function add(Request $request)
	{
		try {

			$params = $request->only(
				"IdPais",
				"Nombre"
			);

			$validation = $this->addEstadoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$estado = new Estado($params);

			if (!$estado->save()) return response([
				"status" => false,
				"message"   => "Estado no creado."
			], 500);

			$data['estado'] = $estado;

			return response([
				"status" => true,
				"message"   => "Estado creado.",
				"data"           => $data
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UN ESTADO
     * METHOD: PUT
     */
	public function update(Request $request, int $IdEstado)
	{
		try {

			$params = $request->only(
				"IdPais",
				"Nombre"
			);

			$validation = $this->addEstadoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$estado = Estado::find($IdEstado);

			if (!empty($Estado)) {

				$estado->IdPais = $params["IdPais"];
				$estado->Nombre =	$params["Nombre"];

				if (!$estado->save()) return response([
					"status"  => false,
					"message" => 'Estado no actualizado.'
				], 500);

				$data['estado'] = $estado;

				return response([
					"status" => true,
					"message" => "Estado actualizado.",
					"data" => $data
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Estado no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA ELIMINAR UN ESTADO
     * METHOD: DELETE
     */
	public function delete($IdEstado)
	{
		try {

			$respose = Estado::find($IdEstado);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Estado eliminado.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Estado no pudo ser eliminado.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Estado no encontrado.',
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

	public function addEstadoValidator($params = [], $IdEstado = 0)
	{
		$required = [
			"IdPais" 	=> "required|numeric",
			"Nombre"	=> "required|string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
