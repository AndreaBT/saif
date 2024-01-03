<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\ClienteEvidencia;
use Illuminate\Http\Request;

use App\Custom\FilesManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Exception;

class ClientesEvidenciasController extends Controller
{
	/**
	 * RAÍZ:/imgclientes
	 * EMPRESA: /1
	 * SUCURSAL: /2
	 * AÑO: /2022
	 * CLIENTE: /1
	 */
	private $rutaFoto = 'imgclientes';
	/*
	 * Funciones CRUD
	 *
	 */

	/*
     * API PARA LISTADO DE TODAS LAS EVIDENCIAS DE CLIENTES
     * METHOD: GET
     */
	public function findAll(Request $request)
	{
		try {

			$multiWhere = array();

			if (!empty($request->get('IdCliente'))) {
				$multiWhere[] = array("IdCliente", '=', $request->get('IdCliente'));
			}

			$clienteEvidenciaFindAll = ClienteEvidencia::where($multiWhere)->paginate(
				$request->query("Entrada"),
				"*",
				"page",
				$request->query("Pag")
			);

			$data['clienteevidencia'] = $clienteEvidenciaFindAll;

			return response([
				"status" 	=> true,
				"message"	=> "Cliente Evidencias encontradas.",
				"data"		=> $data,
				"rutaFoto"  => URL::to('/') . Storage::url("imgclientes/1/2/2022/1"),
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE EVIDENCIAS DE CLIENTES ESPECÍFICAS
     * METHOD: GET
     */
	public function findOne($IdClienteEvidencia)
	{
		try {

			$clienteEvidenciaFindOne = ClienteEvidencia::find($IdClienteEvidencia);

			if (!empty($clienteEvidenciaFindOne)) {

				$data['clienteevidencia'] = $clienteEvidenciaFindOne;

				return response([
					"status"  => true,
					"message" => "Cliente Evidencia encontrada.",
					"data"    => $data
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo Evidencia no encontrada.',
					"rutaFoto"  => URL::to('/') . Storage::url("imgclientes/1/2/2022/1"),
				], 404);
			}
		} catch (Exception $exception) {

			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA AGREGAR EVIDENCIAS CLIENTES
     * METHOD: POST
     */
	public function add(Request $request)
	{
		try {

			$params = $request->only(
				"IdCliente",
				'ListaINEArray',
				'ListaDomicilioArray',
				'ListaEmpresaArray',
				'INE1',
				'INE2',
				'Domicilio1',
				'Domicilio2',
				'Empresa1',
				'Empresa2',
				'Empresa3',
				'Empresa4',
				"origin"
			);

			//   $validation = $this->addClienteEvidenciaValidator($params);

			//   if ($validation->fails()) return response([
			// 	"message"   => "Parámetros inválidos.",
			// 	"errors"    => $validation->errors()
			//   ], 400);

			#FILE_MANEGER
			if ($request->hasFile('INE1')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'INE1');
				$params['INE1'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['INE1'] = '';
			}
			if ($request->hasFile('INE2')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'INE2');
				$params['INE2'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['INE2'] = '';
			}
			if ($request->hasFile('Domicilio1')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Domicilio1');
				$params['Domicilio1'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['Domicilio1'] = '';
			}
			if ($request->hasFile('Domicilio2')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Domicilio2');
				$params['Domicilio2'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['Domicilio2'] = '';
			}
			if ($request->hasFile('Empresa1')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa1');
				$params['Empresa1'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['Empresa1'] = '';
			}
			if ($request->hasFile('Empresa2')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa2');
				$params['Empresa2'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['Empresa2'] = '';
			}
			if ($request->hasFile('Empresa3')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa3');
				$params['Empresa3'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['Empresa3'] = '';
			}
			if ($request->hasFile('Empresa4')) {
				$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa4');
				$params['Empresa4'] = $Archivo['status'] ? $Archivo['HashName'] : '';
			}else {
				$params['Empresa4'] = '';
			}
			

			$clienteEvidencia = new ClienteEvidencia();
			$arrayEvidencia = array();
			//INE
			$listaINE = json_decode($params['ListaINEArray'], true);
			foreach ($listaINE as $position => $element) {
				if($position == 0){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['INE1'],
						'Observaciones' => $element['Observaciones'],
					);
				}
				if($position == 1){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['INE2'],
						'Observaciones' => $element['Observaciones'],
					);
				}
			}
			//DOMICILIO
			$listaDomicilio = json_decode($params['ListaDomicilioArray'], true);
			foreach ($listaDomicilio as $position => $element) {
				if($position == 0){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['Domicilio1'],
						'Observaciones' => $element['Observaciones'],
					);
				}
				if($position == 1){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['Domicilio2'],
						'Observaciones' => $element['Observaciones'],
					);
				}
			}
			//EMPRESA
			$listaEmpresa = json_decode($params['ListaEmpresaArray'], true);
			foreach ($listaEmpresa as $position => $element) {
				if($position == 0){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['Empresa1'],
						'Observaciones' => $element['Observaciones'],
					);
				}
				if($position == 1){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['Empresa2'],
						'Observaciones' => $element['Observaciones'],
					);
				}
				if($position == 2){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['Empresa3'],
						'Observaciones' => $element['Observaciones'],
					);
				}
				if($position == 3){
					$arrayEvidencia[] = array(
						'IdCliente'   	=> $element['IdCliente'],
						'Evidencia'   	=> $params['Empresa4'],
						'Observaciones' => $element['Observaciones'],
					);
				}
			}

			if (!$clienteEvidencia->insert($arrayEvidencia))
				return response([
					"status"  => true,
					"message" => "Cliente Evidencia no creada.",
					"data"    => $arrayEvidencia
				], 201);

			return response([
				"status"  => true,
				"message" => "Cliente Evidencia creada.",
				"data"    => $arrayEvidencia,
				"origin" => $params['origin']
			], 201);
			
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UNA SUCURSAL ESPECÍFICA DEL SISTEMA
     * METHOD: PUT
     */
	public function update(Request $request)
	{
		//try {

			$params = $request->only(
				"IdCliente",
				'ListaINEArray',
				'ListaDomicilioArray',
				'ListaEmpresaArray',
				'INE1',
				'INE2',
				'Domicilio1',
				'Domicilio2',
				'Empresa1',
				'Empresa2',
				'Empresa3',
				'Empresa4',
			);

			// $validation = $this->addClienteEvidenciaValidator($params);

			// if ($validation->fails()) return response([
			// 	"message"   => "Parámetros inválidos.",
			// 	"errors"    => $validation->errors()
			// ], 400);

			


			$existe = DB::table('clientesevidencias')
			->select('*')->where('IdCliente', "=", 1)->get();

			$clienteEvidencia = new ClienteEvidencia();
		

			if (!empty($existe))
			{
					#FILE_MANEGER
				if ($request->hasFile('INE1')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'INE1');
					$params['INE1'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['INE1'] = '';
				}
				if ($request->hasFile('INE2')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'INE2');
					$params['INE2'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['INE2'] = '';
				}
				if ($request->hasFile('Domicilio1')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Domicilio1');
					$params['Domicilio1'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['Domicilio1'] = '';
				}
				if ($request->hasFile('Domicilio2')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Domicilio2');
					$params['Domicilio2'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['Domicilio2'] = '';
				}
				if ($request->hasFile('Empresa1')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa1');
					$params['Empresa1'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['Empresa1'] = '';
				}
				if ($request->hasFile('Empresa2')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa2');
					$params['Empresa2'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['Empresa2'] = '';
				}
				if ($request->hasFile('Empresa3')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa3');
					$params['Empresa3'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['Empresa3'] = '';
				}
				if ($request->hasFile('Empresa4')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Empresa4');
					$params['Empresa4'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				}
				else{
					$params['Empresa4'] = '';
				}

				$arrayEvidencia = array();
				
				foreach ($existe as $position => $element)
				{
					if($position == 0)
					{
						$Evidencia = ClienteEvidencia::find($element->IdClienteEvidencia);
						$Evidencia->Observaciones = $element->Observaciones;

						if($params['INE1'] == ""){
							$Evidencia->Evidencia = $element->Evidencia;
						}
						else
						{
							$Evidencia->Evidencia = $params['INE1'];
						}

						if(!$Evidencia->save())return response([
							"status"  => false,
							"message" => 'No se pudo insertar el registro',
						],500);

						/* if($params['INE1'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['INE1'],
								'Observaciones' => $element->Observaciones,
							);
						} */
					}
					
					/* if($position == 1){
						if($params['INE2'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['INE2'],
								'Observaciones' => $element->Observaciones,
							);
						}
					}
					if($position == 2){
						if($params['Domicilio1'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['Domicilio1'],
								'Observaciones' => $element->Observaciones,
							);
						}
					}
					if($position == 3){
						if($params['Domicilio2'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['Domicilio2'],
								'Observaciones' => $element->Observaciones,
							);
						}
					}
					if($position == 4){
						if($params['Empresa1'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['Empresa1'],
								'Observaciones' => $element->Observaciones,
							);
						}
					}
					if($position == 5){
						if($params['Empresa2'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['Empresa2'],
								'Observaciones' => $element->Observaciones,
							);
						}
					}
					if($position == 6){
						if($params['Empresa3'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['Empresa3'],
								'Observaciones' => $element->Observaciones,
							);
						}
					}
					if($position == 7){
						if($params['Empresa4'] == ""){
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $element->Evidencia,
								'Observaciones' => $element->Observaciones,
							);
						}else{
							$arrayEvidencia[] = array(
								'IdClienteEvidencia' => $element->IdClienteEvidencia,
								'IdCliente'   	=> $element->IdCliente,
								'Evidencia'   	=> $params['Empresa4'],
								'Observaciones' => $element->Observaciones,
							);
						}
					} */
				}

				/* if (!$clienteEvidencia->update($arrayEvidencia))
				return response([
					"status"  => true,
					"message" => "Cliente Evidencia no actualizado.",
					"data"    => $arrayEvidencia
				], 201);

				return response([
					"status"  => true,
					"message" => "Cliente Evidencia actualizado.",
					"data"    => $arrayEvidencia
				], 201); */
			} /* else {

				return response([
					"status"  => true,
					"message" => 'Cliente Evidencia no encontrada.',
				], 404);
			} */
		/* } catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		} */
	}

	/*
     * API PARA ELIMINAR UNA SUCURSAL ESPECÍFICA DEL SISTEMA
     * METHOD: DELETE
     */
	public function delete($IdClienteEvidencia)
	{
		try {

			$respose = ClienteEvidencia::find($IdClienteEvidencia);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Cliente Evidencia eliminada.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Cliente Evidencia no pudo ser eliminada.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Cliente Evidencia no encontrada.',
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

	public function addClienteEvidenciaValidator($params = [], $IdClienteEvidencia = 0)
	{
		$required = [
			// "IdCliente"    => "required|numeric",

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
