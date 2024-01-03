<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\PrestamoMonto;
use Illuminate\Http\Request;

use Exception;

class PrestamosMontosController extends Controller
{
	/*
	 * Funciones CRUD
	 *
	*/

	/*
     * API PARA LISTADO DE TODAS LAS MONTOS DE CREDITOS DEL SISTEMA
     * METHOD: GET
	*/
	public function findAll(Request $request)
	{
		try {
			if ($request->simple > 0) {
                $prestamoMontoFindAll = PrestamoMonto::get();
            } else {
				$multiwhere = array();

                if(!empty($request->get('TxtBusqueda'))) {
                    $multiwhere[] = array('Monto','like','%' . $request->get('TxtBusqueda') . '%');
                }

                $prestamoMontoFindAll = PrestamoMonto::where($multiwhere)->paginate(
                    $request->query("Entrada"),
                    "*",
                    "page",
                    $request->query("Pag")
                );
			}

			return response([
				"status" 	=> true,
				"message"	=> "Prestamos Montos encontrados.",
				"data"		=> $prestamoMontoFindAll,
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE MONTOS DE CREDITOS ESPECÍFICAS DEL SISTEMA
     * METHOD: GET
	*/
	public function findOne($IdPrestamoMonto)
	{
		try {
			$prestamoMontoFindOne = PrestamoMonto::find($IdPrestamoMonto);

			if (!empty($prestamoMontoFindOne)) {
				return response([
					"status"  => true,
					"message" => "success",
					"data"    => $prestamoMontoFindOne
				], 200);
			} else {
				return response([
					"status"  => false,
					"message" => 'Registro no encontrado',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA AGREGAR MONTOS DE CREDITOS AL SISTEMA
     * METHOD: POST
	*/
	public function add(Request $request)
	{
		try {
			$params = $request->only(
				'Monto'
			);

			$validation = $this->addPrestamoMontoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamoMontoAdd = new PrestamoMonto($params);

			if (!$prestamoMontoAdd->save()) return response([
				"status" 	=> false,
				"message"   => "Prestamo Monto no creado."
			], 500);

			return response([
				"status" 	=> true,
				"message"   => "Prestamo Monto creado.",
				"data"		=> $prestamoMontoAdd
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE MONTOS DE CREDITOS ESPECÍFICOS DEL SISTEMA
     * METHOD: PUT
	*/
	public function update(Request $request, int $IdPrestamoMonto)
	{
		try {
			$params = $request->only(
				'Monto'
			);

			$validation = $this->addPrestamoMontoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamoMontoUpdate = PrestamoMonto::find($IdPrestamoMonto);

			if (!empty($prestamoMontoUpdate)) {

				$prestamoMontoUpdate->Monto = $params['Monto'];

				if (!$prestamoMontoUpdate->save()) return response([
					"status"  => false,
					"message" => 'Prestamo Monto no actualizado.'
				], 500);

				return response([
					"status" => true,
					"message" => "Prestamo Monto actualizado.",
					"data" => $prestamoMontoUpdate
				], 200);
			} else {
				return response([
					"status"  => true,
					"message" => 'Prestamo Monto no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA ELIMINAR UN MONTO DE CREDITO ESPECÍFICA DEL SISTEMA
     * METHOD: DELETE
	*/
	public function delete($IdPrestamoMonto)
	{
		try {
			$respose = PrestamoMonto::find($IdPrestamoMonto);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Prestamo Monto eliminado.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Prestamo Monto no pudo ser eliminado.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo Monto no encontrada.',
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

	public function addPrestamoMontoValidator($params = [], $IdPrestamoPago = 0)
	{
		$required = [
			"Monto"	=> "required|numeric"
		];

		return validator($params, $required, 
			['required'  => 'El campo es requerido.']
		);
	}
}