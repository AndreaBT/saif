<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Custom\ResponseManager;
use App\Models\Ruta;
use Illuminate\Http\Request;

use Exception;

class RutasController extends Controller
{
	/*
	 * Funciones CRUD
	 *
	 */

	/*
     * API PARA LISTADO DE TODAS LAS SUCURSALES DEL SISTEMA
     * METHOD: GET
     */
	public function findAll(Request $request)
	{
		try
		{
			if ($request->simple > 0)
			{
				$multiWhere = array();
				if (!empty($request->get('IdSucursal'))) {
					$multiWhere[] = array("IdSucursal", '=', $request->get('IdSucursal'));
				}
				
                $rutaFindAll = Ruta::where($multiWhere)->get();
            } else {
				$search = '';
				if (!empty($request->get('TxtBusqueda'))) {
					$search = $request->get('TxtBusqueda');
				}

				$multiWhere = array();

				if (!empty($request->get('IdPais'))) {
					$multiWhere[] = array("IdPais", '=', $request->get('IdPais'));
				}
				if (!empty($request->get('IdEstado'))) {
					$multiWhere[] = array("IdEstado", '=', $request->get('IdEstado'));
				}
				if (!empty($request->get('IdSucursal'))) {
					$multiWhere[] = array("IdSucursal", '=', $request->get('IdSucursal'));
				}

				$rutaFindAll = Ruta::where(function ($query) use ($search) {
					$query->where('NombreRuta', 'like', '%' . $search . '%');
				})->where($multiWhere)->paginate(
					$request->query("Entrada"),
					"*",
					"page",
					$request->query("Pag")
				);
			}

			return response([
				"status" 	=> true,
				"message"	=> "Rutas encontradas.",
				"data"		=> $rutaFindAll,
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE SUCURSALES ESPECÍFICAS DEL SISTEMA
     * METHOD: GET
     */
	public function findOne($IdRuta)
	{
		try {

			$rutaFindOne = Ruta::find($IdRuta);

			if (!empty($rutaFindOne))
			{
				return response([
					"status"  => true,
					"message" => "Ruta encontrada.",
					"data"    => $rutaFindOne
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Ruta no encontrada.',
				], 404);
			}
		} catch (Exception $exception) {

			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA AGREGAR SUCURSALES AL SISTEMA
     * METHOD: POST
     */
	public function add(Request $request)
	{
		try {
			$sesion = JWTAuth::authenticate();
			$params = $request->only(
				'NombreRuta',
				'IdPais',
				'IdEstado',
				'IdSucursal'
			);

			$validation = $this->addRutaValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$params['IdPais'] = $sesion->IdPais;
			$params['IdEstado'] = $sesion->IdEstado;

			$rutaAdd = new Ruta($params);

			if (!$rutaAdd->save()) return response([
				"status" => false,
				"message"   => "Ruta no creada."
			], 500);

			$data['ruta'] = $rutaAdd;

			return response([
				"status" 	=> true,
				"message"   => "Ruta creada.",
				"data"		=> $data
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UNA SUCURSAL ESPECÍFICA DEL SISTEMA
     * METHOD: PUT
     */
	public function update(Request $request, int $IdRuta)
	{
		try {

			$params = $request->only(
				'NombreRuta',
				'IdPais',
				'IdEstado',
				'IdSucursal',
			);

			$validation = $this->addRutaValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$rutaUpdate = Ruta::find($IdRuta);

			if (!empty($rutaUpdate)) {

				$rutaUpdate->NombreRuta = 		$params['NombreRuta'];
				$rutaUpdate->IdPais = 			$params['IdPais'];
				$rutaUpdate->IdEstado = 		$params['IdEstado'];
				$rutaUpdate->IdSucursal =		$params['IdSucursal'];

				if (!$rutaUpdate->save()) return response([
					"status"  => false,
					"message" => 'Ruta no actualizada.'
				], 500);

				$data['ruta'] = $rutaUpdate;

				return response([
					"status" => true,
					"message" => "Ruta actualizada.",
					"data" => $data
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Ruta no encontrada.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA ELIMINAR UNA SUCURSAL ESPECÍFICA DEL SISTEMA
     * METHOD: DELETE
     */
	public function delete($IdRuta)
	{
		try {

			$respose = Ruta::find($IdRuta);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Ruta eliminada.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Ruta no pudo ser eliminada.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Ruta no encontrada.',
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

	public function addRutaValidator($params = [], $IdRuta = 0)
	{
		$required = [
			"NombreRuta"    => "required|string",
			/* "IdPais"       	=> "required|numeric",
			"IdEstado"		=> "required|numeric", */
			"IdSucursal"    => "required|numeric|min:1", 
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'min'       => 'Debe seleccionar una opción.',
			// 'same'      => 'Las contraseñas no coinciden.',
			// 'unique'    => 'Este nombre de usuario ya ha sido utilizado en otra cuenta.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
