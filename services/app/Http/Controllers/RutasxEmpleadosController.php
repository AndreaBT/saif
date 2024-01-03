<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\RutaxUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class RutasxEmpleadosController extends Controller
{
    protected $location = 'RutasxEmpleadosController';

	/*
	 * Funciones CRUD
	 *
	 */

	/*
     * API PARA LISTADO DE TODAS RUTAS USUARIOS
     * METHOD: GET
     */
	public function findAll(Request $request)
	{
		try {

			$multiWhere = array();

			if (!empty($request->get('IdRuta'))) {
				$multiWhere[] = array("IdRuta", '=', $request->get('IdRuta'));
			}

			if (!empty($request->get('IdUsuario'))) {
				$multiWhere[] = array("IdUsuario", '=', $request->get('IdUsuario'));
			}

			$rutaxUsuario = RutaxUsuario::where(function ($query){
				$query
				->join('users', 'users.IdUsuario', '=', 'rutasxusuarios.IdUsuario');
			})->where($multiWhere)->paginate(
				$request->query("Entrada"),
				"*",
				"page",
				$request->query("Pag")
			);

			$data['rutaxusuario'] = $rutaxUsuario;

			return response([
				"status" 	=> true,
				"message"	=> "Ruta Usuario encontrada.",
				"data"		=> $data,
			], 200);

			/*if (empty($rutaxUsuario)) {
				return response([
					"status" 	=> true,
					"message"	=> "Ruta Usuario no encontrada.",
					"data"		=> $data,
				], 404);
			}*/
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'findAll');
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE RUTA USUARIO ESPECÍFICA
     * METHOD: GET
     */
	public function findOne($IdRutaxUsuario)
	{
		try {

			$rutaxUsuario = RutaxUsuario::find($IdRutaxUsuario);

			if (!empty($rutaxUsuario)) {

				$data['rutaxusuario'] = $rutaxUsuario;

				return response([
					"status"  => true,
					"message" => "Ruta Usuario encontrada.",
					"data"    => $data
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Ruta Usuario no encontrada.',
				], 404);
			}
		} catch (Exception $exception) {

			return ResponseManager::setErrorServerResponse($exception, $this->location,'findOne');
		}
	}

	/*
     * API PARA AGREGAR RUTAS X USUARIO AL SISTEMA
     * METHOD: POST
     */
	public function add(Request $request)
	{
		try
		{
			$params = $request->only(
				'IdRuta',
				'arreglo',
				'FechaAsignacion',
			);

			/*$validation = $this->addRutaEmpleadoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);
			*/

			$user = RutaxUsuario::where('IdRuta', $params['IdRuta'])->delete();

			$rutaxUsuario = '';
			foreach($params['arreglo'] as $elemento)
			{
				$newArray = array();
				$newArray['IdRuta'] = $params['IdRuta'];
				$newArray['IdUsuario'] = $elemento['IdUsuario'];
				$newArray['FechaAsignacion'] = date('Y-m-d');
				$rutaxUsuario = new RutaxUsuario($newArray);

				if (!$rutaxUsuario->save()) return response([
					"status" => false,
					"message"   => "Ruta Usuario no creada."
				], 500);
			}

			return response([
				"status" 	=> true,
				"message"   => "Ruta Usuario creada.",
				"data"		=> $rutaxUsuario
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'add');
		}
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UNA RUTA USUARIO ESPECÍFICA DEL SISTEMA
     * METHOD: PUT
     */
	public function update(Request $request, int $IdRutaxUsuario)
	{
		try {

			$params = $request->only(
				'IdRuta',
				'IdUsuario',
				'FechaAsignacion',
			);

			$validation = $this->addRutaEmpleadoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$rutaxUsuario = RutaxUsuario::find($IdRutaxUsuario);

			if (!empty($rutaxUsuario)) {

				$rutaxUsuario->IdRuta = 			$params['IdRuta'];
				$rutaxUsuario->IdUsuario = 			$params['IdUsuario'];
				$rutaxUsuario->FechaAsignacion =	$params['FechaAsignacion'];

				if (!$rutaxUsuario->save()) return response([
					"status"  => false,
					"message" => 'Ruta Usuario no actualizada.'
				], 500);

				$data['rutaempleado'] = $rutaxUsuario;

				return response([
					"status" => true,
					"message" => "Ruta Usuario actualizada.",
					"data" => $data
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Ruta Usuario no encontrada.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'update');
		}
	}

	/*
     * API PARA ELIMINAR UNA RUTA X USUARIO ESPECÍFICA DEL SISTEMA
     * METHOD: DELETE
     */
	public function delete($IdRutaxUsuario)
	{
		try {

			$respose = RutaxUsuario::find($IdRutaxUsuario);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Ruta Usuario eliminada.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Ruta Usuario no pudo ser eliminada.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Ruta Usuario no encontrada.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'delete');
		}
	}

	/*
     * API PARA JOIN DE USUARIO NOMMBRE A RUTAS X USUARIO
     * METHOD: GET
	*/
	public function findAllInner(Request $request)
	{
		try {
			$IdRuta = $request->get('IdRuta');

			$rutasxusuarios = RutaxUsuario::query()
			->join('users', 'users.IdUsuario', '=', 'rutasxusuarios.IdUsuario')
			->select(
				'rutasxusuarios.IdRutaxUsuario',
				'users.NombreCompleto',
				'users.IdUsuario'
			)
			->where('rutasxusuarios.IdRuta', '=', $IdRuta)
			->whereIn('users.IdPerfil',array(2,4))
			->get();

			return response([
				"status"    => true,
				"message"   => "success",
				"data"      => $rutasxusuarios,
			]);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'findAllInner');
		}
	}


    /*
     * API PARA JOIN DE USUARIO NOMMBRE A RUTAS X USUARIO
     * METHOD: GET
	*/
    public function findMyRoutes(Request $request) {
        try {

            $Usuario = JWTAuth::authenticate();

            $rutaUsuarios = RutaxUsuario::query()
                ->where('rutasxusuarios.IdUsuario','=',$Usuario->IdUsuario)
                ->join('rutas','rutas.IdRuta','=','rutasxusuarios.IdRuta')
                ->get(array('rutasxusuarios.IdUsuario','rutasxusuarios.IdRuta','rutas.IdRuta','rutas.NombreRuta'));

            if(!empty($rutaUsuarios)){
                return response([
                    "status"    => true,
                    "message"   => "success",
                    "data"      => $rutaUsuarios,
                ]);
            }

        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception, $this->location,'findMyRoutes');
        }
    }

	/*
     ********************** FUNCIONES DE VALIDACIÓN *********************************
     *
     */

	public function addRutaEmpleadoValidator($params = [], $IdRutaxUsuario = 0)
	{
		$required = [
			"IdRuta"   			=> "required|numeric",
			"IdUsuario"       	=> "required|numeric",
			"FechaAsignacion"   => "required|string"

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
