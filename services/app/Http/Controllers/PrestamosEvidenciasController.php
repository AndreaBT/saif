<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\PrestamoEvidencia;
use Illuminate\Http\Request;

use Exception;

class PrestamosEvidenciasController extends Controller
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

			$search = '';
			if (!empty($request->get('TxtBusqueda'))) {
				$search = $request->get('TxtBusqueda');
			}

			$multiWhere = array();

			if (!empty($request->get('IdPrestamo'))) {
				$multiWhere[] = array("IdPrestamo", '=', $request->get('IdPrestamo'));
			}

			if (!empty($request->get('IdUsuario'))) {
				$multiWhere[] = array("IdUsuario", '=', $request->get('IdUsuario'));
			}

			$prestamoEvidenciaFindAll = PrestamoEvidencia::where(function ($query) use ($search) {
				$query->where('Folio', 'like', '%' . $search . '%');
			})->where($multiWhere)
				->paginate(
					$request->query("Entrada"),
					"*",
					"page",
					$request->query("Pag")
				);

			$data['prestamoevidencia'] = $prestamoEvidenciaFindAll;

			return response([
				"status" 	=> true,
				"message"	=> "Prestamos Evidencia encontradas.",
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
	public function findOne($IdPrestamoEvidencia)
	{
		try {

			$prestamoEvidenciaFindOne = PrestamoEvidencia::find($IdPrestamoEvidencia);

			if (!empty($prestamoEvidenciaFindOne)) {

				$data['prestamoevidencia'] = $prestamoEvidenciaFindOne;

				return response([
					"status"  => true,
					"message" => "Prestamo Evidencia encontrada.",
					"data"    => $data
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo Evidencia no encontrada.',
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
				'TipoEvidencia',
				'Evidencia',
				'Comentario',
				'Anio'
			);

			$validation = $this->addPrestamoEvidenciaValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamoEvidenciaAdd = new PrestamoEvidencia($params);

			if (!$prestamoEvidenciaAdd->save()) return response([
				"status" => false,
				"message"   => "Prestamo Evidencia no creada."
			], 500);

			$data['prestamoevidencia'] = $prestamoEvidenciaAdd;

			return response([
				"status" => true,
				"message"   => "Prestamo Evidencia creada.",
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
	public function update(Request $request, int $IdPrestamosEvidencia)
	{
		try {

			$params = $request->only(
				'IdPrestamo',
				'IdUsuario',
				'TipoEvidencia',
				'Evidencia',
				'Comentario',
				'Anio'
			);

			$validation = $this->addPrestamoEvidenciaValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamoEvidenciaUpdate = PrestamoEvidencia::find($IdPrestamosEvidencia);

			if (!empty($prestamoEvidenciaUpdate)) {

				$prestamoEvidenciaUpdate->IdPrestamo = 		$params['IdPrestamo'];
				$prestamoEvidenciaUpdate->IdUsuario = 		$params['IdUsuario'];
				$prestamoEvidenciaUpdate->TipoEvidencia = 	$params['TipoEvidencia'];
				$prestamoEvidenciaUpdate->Evidencia =		$params['Evidencia'];
				$prestamoEvidenciaUpdate->Comentario = 		$params['Comentario'];
				$prestamoEvidenciaUpdate->Anio = 			$params['Anio'];


				if (!$prestamoEvidenciaUpdate->save()) return response([
					"status"  => false,
					"message" => 'Prestamo Evidencia no actualizada.'
				], 500);

				$data['prestamoevidencia'] = $prestamoEvidenciaUpdate;

				return response([
					"status" => true,
					"message" => "Prestamo Evidencia actualizada.",
					"data" => $data
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Prestamo Evidencia no encontrada.',
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
	public function delete($IdPrestamosEvidencia)
	{
		try {

			$respose = PrestamoEvidencia::find($IdPrestamosEvidencia);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Prestamo Evidencia eliminada.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Prestamo Evidencia no pudo ser eliminada.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo Evidencia no encontrada.',
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

	public function addPrestamoEvidenciaValidator($params = [], $IdPrestamoEvidencia = 0)
	{
		$required = [
			"IdPrestamo"    => "required|numeric",
			"IdUsuario"       => "required|numeric",
			"TipoEvidencia"    => "required|string",
			"Evidencia"         => "required|string",
			"Comentario"    => "required|string",
			"Anio"    => "required|string"

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
