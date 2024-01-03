<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\PrestamoPago;
use Illuminate\Http\Request;

use Exception;

class PrestamosPagosController extends Controller
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
		try {

			$multiWhere = array();

			if (!empty($request->get('IdPrestamo'))) {
				$multiWhere[] = array("IdPrestamo", '=', $request->get('IdPrestamo'));
			}

			if (!empty($request->get('IdUsuario'))) {
				$multiWhere[] = array("IdUsuario", '=', $request->get('IdUsuario'));
			}

			if (!empty($request->get('IdCliente'))) {
				$multiWhere[] = array("IdCliente", '=', $request->get('IdCliente'));
			}

			if (!empty($request->get('TxtBusqueda'))) {
				$multiWhere[] = array('Folio', 'like', '%' . $request->get('TxtBusqueda') . '%');
			}

			$prestamoPagoFindAll = PrestamoPago::where($multiWhere)->paginate(
				$request->query("Entrada"),
				"*",
				"page",
				$request->query("Pag")
			);

			$data['prestamopago'] = $prestamoPagoFindAll;

			return response([
				"status" 	=> true,
				"message"	=> "Prestamos Pagos encontrados.",
				"data"		=> $data,
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE SUCURSALES ESPECÍFICAS DEL SISTEMA
     * METHOD: GET
     */
	public function findOne($IdPrestamoPago)
	{
		try {

			$prestamoPagoFindOne = PrestamoPago::find($IdPrestamoPago);

			if (!empty($prestamoPagoFindOne)) {

				$data['prestamopago'] = $prestamoPagoFindOne;

				return response([
					"status"  => true,
					"message" => "Prestamo Pago encontrado.",
					"data"    => $data
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo Pago no encontrado.',
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

			$params = $request->only(
				'IdPrestamo',
				'IdUsuario',
				'IdCliente',
				'FechaPago',
				'HoraPago',
				'Monto',
				'NumeroPago',
				'NumeroAbono',
				'Folio'
			);

			$validation = $this->addPrestamoPagoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamoPagoAdd = new PrestamoPago($params);

			if (!$prestamoPagoAdd->save()) return response([
				"status" => false,
				"message"   => "Prestamo Pago no creado."
			], 500);

			$data['prestamopago'] = $prestamoPagoAdd;

			return response([
				"status" => true,
				"message"   => "Prestamo Pago creado.",
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
	public function update(Request $request, int $IdPrestamoPago)
	{
		try {

			$params = $request->only(
				'IdPrestamo',
				'IdUsuario',
				'IdCliente',
				'FechaPago',
				'HoraPago',
				'Monto',
				'NumeroPago',
				'NumeroAbono',
				'Folio'
			);

			$validation = $this->addPrestamoPagoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamoPagoUpdate = PrestamoPago::find($IdPrestamoPago);

			if (!empty($prestamoPagoUpdate)) {

				$prestamoPagoUpdate->IdPrestamo = 		$params['IdPrestamo'];
				$prestamoPagoUpdate->IdUsuario = 		$params['IdUsuario'];
				$prestamoPagoUpdate->IdCliente = 		$params['IdCliente'];
				$prestamoPagoUpdate->FechaPago =		$params['FechaPago'];
				$prestamoPagoUpdate->HoraPago = 		$params['HoraPago'];
				$prestamoPagoUpdate->Monto = 			$params['Monto'];
				$prestamoPagoUpdate->NumeroPago = 		$params['NumeroPago'];
				$prestamoPagoUpdate->NumeroAbono = 		$params['NumeroAbono'];
				$prestamoPagoUpdate->Folio = 			$params['Folio'];

				if (!$prestamoPagoUpdate->save()) return response([
					"status"  => false,
					"message" => 'Prestamo Pago no actualizado.'
				], 500);

				$data['prestamopago'] = $prestamoPagoUpdate;

				return response([
					"status" => true,
					"message" => "Prestamo Pago actualizado.",
					"data" => $data
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Prestamo Pago no encontrado.',
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
	public function delete($IdPrestamoPago)
	{
		try {

			$respose = PrestamoPago::find($IdPrestamoPago);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Prestamo Pago eliminado.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Prestamo Pago no pudo ser eliminado.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo Pago no encontrada.',
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

	public function addPrestamoPagoValidator($params = [], $IdPrestamoPago = 0)
	{
		$required = [
			"IdPrestamo"    => "required|numeric",
			"IdUsuario"       => "required|numeric",
			"IdCliente"    => "required|numeric",
			"FechaPago"         => "required|string",
			"HoraPago"    => "required|string",
			"Monto"    => "required|numeric",
			"NumeroPago"    => "required|numeric",
			"NumeroAbono"    => "required|numeric",
			"Folio"    => "required|string"

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
