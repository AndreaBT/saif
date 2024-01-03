<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use Exception;


class SucursalesController extends Controller
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
		$sesion = JWTAuth::authenticate();
		
		try {
			if ($request->simple > 0)
			{
				//print_r($sesion);

				$multiWhere = array();
				$multiWhere[] = array("IdEmpresa", '=', $sesion->IdEmpresa);
				
                $sucursalFindAll = Sucursal::where($multiWhere)->get();
            } else {

				$search = '';
				if (!empty($request->get('txtBusqueda'))) {
					$search = $request->get('txtBusqueda');
				}

				$multiWhere = array();

				if (!empty($request->get('IdEmpresa'))) {
					$multiWhere[] = array("IdEmpresa", '=', $request->get('IdEmpresa'));
				}
				if (!empty($request->get('IdEstado'))) {
					$multiWhere[] = array("IdEstado", '=', $request->get('IdEstado'));
				}
				if (!empty($request->get('IdMunicipio'))) {
					$multiWhere[] = array("IdMunicipio", '=', $request->get('IdMunicipio'));
				}

				$sucursalFindAll = Sucursal::where(function ($query) use ($search) {
					$query->where('Nombre', 'like', '%' . $search . '%');
				})->where($multiWhere)->paginate(
					$request->query("Entrada"),
					"*",
					"page",
					$request->query("Pag")
				);
			}

			return response([
				"status" 	=> true,
				"message"	=> "Sucursales encontradas.",
				"data"		=> $sucursalFindAll,
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE SUCURSALES ESPECÍFICAS DEL SISTEMA
     * METHOD: GET
     */
	public function findOne($IdSucursal)
	{
		try {

			$sucursalFindOne = Sucursal::find($IdSucursal);

			if (!empty($sucursalFindOne)) {

				return response([
					"status"  => true,
					"message" => "Sucursal encontrada.",
					"data"    => $sucursalFindOne
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Sucursal no encontrada.',
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
				'IdEstado',
				'IdMunicipio',
				'Nombre',
				'Calle',
				'NoInt',
				'NoExt',
				'Colonia',
				'Cp',
				'Referencias',
				'Telefono',
				'Latitud',
				'Longitud'
			);

			$params["IdEmpresa"] = $sesion->IdEmpresa;

			$validation = $this->addSucursalValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$sucursalAdd = new Sucursal($params);

			if (!$sucursalAdd->save()) return response([
				"status" => false,
				"message"   => "Sucursal no creada."
			], 500);

			$data['sucursal'] = $sucursalAdd;

			return response([
				"status" => true,
				"message"   => "Sucursal creada.",
				"data"           => $data
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UNA SUCURSAL ESPECÍFICA DEL SISTEMA
     * METHOD: PUT
     */
	public function update(Request $request, int $IdSucursal)
	{
		try {

			$params = $request->only(
				'IdEstado',
				'IdMunicipio',
				'Nombre',
				'Calle',
				'NoInt',
				'NoExt',
				'Colonia',
				'Cp',
				'Referencias',
				'Telefono',
				'Latitud',
				'Longitud'
			);

			$validation = $this->addSucursalValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$Sucursal = Sucursal::find($IdSucursal);

			if (!empty($Sucursal)) {

				$Sucursal->IdEstado = 	$params['IdEstado'];
				$Sucursal->IdMunicipio = 	$params['IdMunicipio'];
				$Sucursal->Nombre =		$params['Nombre'];
				$Sucursal->Calle = 		$params['Calle'];
				$Sucursal->NoInt = 		$params['NoInt'];
				$Sucursal->NoExt = 		$params['NoExt'];
				$Sucursal->Colonia = 		$params['Colonia'];
				$Sucursal->Cp = 			$params['Cp'];
				$Sucursal->Referencias = 	$params['Referencias'];
				$Sucursal->Telefono = 	$params['Telefono'];
				$Sucursal->Latitud = 		$params['Latitud'];
				$Sucursal->Longitud = 	$params['Longitud'];

				if (!$Sucursal->save()) return response([
					"status"  => false,
					"message" => 'Sucursal no actualizada.'
				], 500);

				$data['sucursal'] = $Sucursal;

				return response([
					"status" => true,
					"message" => "Sucursal actualizada.",
					"data" => $data
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Sucursal no encontrada.',
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
	public function delete($IdSucursal)
	{
		try {

			$respose = Sucursal::find($IdSucursal);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Sucursal eliminada.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'La sucursal no pudo ser eliminada .',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Sucursal no encontrada.',
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

	public function addSucursalValidator($params = [], $IdSucursal = 0)
	{
		$required = [
			"IdEstado"    => "required|numeric",
			"IdMunicipio"    => "required|numeric",
			"Nombre"         => "required|string",
			"Calle"    => "required|string",
			"NoExt"    => "required|string",
			"Colonia"       => "required|string",
			"Cp"       => "required|string",
			"Referencias"    => "required|string",
			"Telefono"         => "required|string",
			//"Latitud"    => "required|string",
			//"Longitud"    => "required|string"
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			// 'min'       => 'El campo mínimo es de 8 carácteres.',
			// 'same'      => 'Las contraseñas no coinciden.',
			// 'unique'    => 'Este nombre de usuario ya ha sido utilizado en otra cuenta.',
			'string'    => 'El campo es requerido.',
		]);
	}
}
