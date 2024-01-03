<?php

namespace App\Http\Controllers;

use App\Custom\Funciones;
use App\Custom\ResponseManager;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\EntregadoresxPrestamos;
use App\Models\Prestamo;
use App\Models\PrestamoxCancelacion;
use App\Models\PrestamoPago;
use Illuminate\Http\Request;
use App\Custom\FilesManager;
use App\Models\PrestamoxComentario;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

use Exception;

class PrestamosController extends Controller
{
    private $rutaFoto = 'imgclientes';
    private $rutaEvidencia = 'imgClientesEvidencias';

	/*
	 * Funciones CRUD
	 *
	 */

	/*
     * API PARA LISTADO DE TODOS LOS PRÉSTAMOS
     * METHOD: GET
     */
	public function findAll(Request $request)
	{
		try {

			// $search = '';
			// if (!empty($request->get('TxtBusqueda'))) {
			// 	$search = $request->get('TxtBusqueda');
			// }

			$multiWhere = array();

			if (!empty($request->get('IdCliente'))) {
				$multiWhere[] = array("IdCliente", '=', $request->get('IdCliente'));
			}

			if (!empty($request->get('IdSucursal'))) {
				$multiWhere[] = array("IdSucursal", '=', $request->get('IdSucursal'));
			}
			if (!empty($request->get('IdRuta'))) {
				$multiWhere[] = array("IdRuta", '=', $request->get('IdRuta'));
			}

			if (!empty($request->get('IdAutorizo'))) {
				$multiWhere[] = array("IdAutorizo", '=', $request->get('IdAutorizo'));
			}
			if (!empty($request->get('IdEntrego'))) {
				$multiWhere[] = array("IdEntrego", '=', $request->get('IdEntrego'));
			}

			if (!empty($request->get('IdCobratario'))) {
				$multiWhere[] = array("IdCobratario", '=', $request->get('IdCobratario'));
			}

			$prestamo = Prestamo::where($multiWhere)->paginate(
				$request->query("Entrada"),
				"*",
				"page",
				$request->query("Pag")
			);

			$data['prestamo'] = $prestamo;

			return response([
				"status" 	=> true,
				"message"	=> "Prestamos encontrados.",
				"data"		=> $data,
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE UN PRÉSTAMO ESPECÍFICAS DEL SISTEMA
     * METHOD: GET
     */
	public function findOne($IdPrestamo)
	{
		try {

			$prestamo = Prestamo::find($IdPrestamo);

			if (!empty($prestamo)) {

				$data['prestamo'] = $prestamo;

				return response([
					"status"  => true,
					"message" => "Prestamo encontrado.",
					"data"    => $data
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {

			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA PRÉSTAMOS AL SISTEMA
     * METHOD: POST
     */
	public function add(Request $request)
	{
		try {

			$sesion = JWTAuth::authenticate();

			$params = $request->only(
				"IdCliente",
				"IdRuta",
				"IdAutorizo",
				"IdSucursal",
				"Creador",
				"FechaAutorizacion",
				"FechaEntrega",
				"FechaLiquidacion",
				"IdMontoSolicitud",
				"MontoSolicitud",
				"MontoAutorizado",
				"AdeudoTotal",
				"Estatus",
				"MontoRechazo",
				"Observaciones",
			);

			$params["FechaAutorizacion"] = date('Y-m-d');
			$params["FechaEntrega"] = date('Y-m-d');
			$params["FechaLiquidacion"] = date('Y-m-d');

			$params["IdCobratario"] = $sesion->Creador;
			$params["IdValidador"] = $sesion->IdUsuario;

			$validation = $this->addPrestamoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamo = new Prestamo($params);

			if (!$prestamo->save()) return response([
				"status" => false,
				"message"   => "Prestamo no creado."
			], 500);

			$data['prestamo'] = $prestamo;

			return response([
				"status" => true,
				"message"   => "Prestamo creado.",
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
	public function update(Request $request, int $IdPrestamo)
	{
		try {


			$sesion = JWTAuth::authenticate();

			$params = $request->only(
				"IdCliente",
				"IdSucursal",
				"IdRuta",
				"Creador",
				"IdAutorizo",
				"FechaAutorizacion",
				"FechaEntrega",
				"FechaLiquidacion",
				"MontoSolicitud",
				"MontoAutorizado",
				"AdeudoTotal",
				"Estatus",
				"Observaciones",
				"MontoRechazo"
			);

			$validation = $this->addPrestamoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$prestamoUpdate = Prestamo::find($IdPrestamo);

			if (!empty($prestamoUpdate)) {

				$prestamoUpdate->IdCliente 		   = $params['IdCliente'];
				$prestamoUpdate->IdSucursal 	   = $params['IdSucursal'];
				$prestamoUpdate->IdRuta 		   = $params['IdRuta'];
				$prestamoUpdate->Creador 		   = $params['Creador'];
				$prestamoUpdate->IdAutorizo 	   = $params['IdAutorizo'];
				$prestamoUpdate->FechaAutorizacion = $params['FechaAutorizacion'];
				$prestamoUpdate->FechaEntrega 	   = $params['FechaEntrega'];
				$prestamoUpdate->FechaLiquidacion  = $params['FechaLiquidacion'];
				$prestamoUpdate->MontoSolicitud    = $params['MontoSolicitud'];
				$prestamoUpdate->MontoEntregado    = $params['MontoAutorizado'];
				$prestamoUpdate->Estatus 		   = $params['Estatus'];
				$prestamoUpdate->Observaciones 	   = $params['Observaciones'];

				if (!$prestamoUpdate->save()) return response([
					"status"  => false,
					"message" => 'Prestamo no actualizado.'
				], 500);

				$data['prestamo'] = $prestamoUpdate;

				return response([
					"status"  => true,
					"message" => "Prestamo actualizado.",
					"data"    => $data
				], 200);

			} else {

				return response([
					"status"  => true,
					"message" => 'Prestamo no encontrado.',
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
	public function delete($IdPrestamo)
	{
		try {

			$respose = Prestamo::find($IdPrestamo);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Prestamo eliminado.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Prestamo no pudo ser eliminado.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Prestamo no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}
    /*
     ********************** FUNCIONES Especial *********************************
     *
     */
    public function getPrestamosEntregador(Request $request) {
        try {
            $usrSesion = JWTAuth::authenticate();

            $multiWhere = array();
            if (!empty($request->get('TxtBusqueda'))) {
                $multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
            }

            if($usrSesion->IdPerfil == 3) {
                $ExPrestamo = EntregadoresxPrestamos::query()
                    ->join('prestamos','entregadoresxprestamos.IdPrestamo','=','prestamos.IdPrestamo')
                    ->join('clientes','prestamos.IdCliente','=','clientes.IdCliente')
                    ->where($multiWhere)
                    ->where('prestamos.Estatus','=','Asignado')
                    ->where('prestamos.EstatusEntrega','=','Pendiente')
                    ->where('entregadoresxprestamos.IdUsuario','=',$usrSesion->IdUsuario)
                    ->get();

                $ArrPrestamos = array();
                if(!empty($ExPrestamo)) {

                    foreach ($ExPrestamo as $element) {
                        $prestamo = Prestamo::query()
                            ->with(array('sucursal','ruta'))
                            ->find($element->IdPrestamo);

                        if(!empty($prestamo))
                        {
                            $cliente = Cliente::query()
                                ->with('evidencias')
                                ->find($element->IdCliente);

                            $prestamo->Cliente = $cliente;
                        }

                        $ArrPrestamos[] = $prestamo;
                    }

                    return response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => $ArrPrestamos,
                        'RutaCliente'     => URL::to('/') . Storage::url($this->rutaFoto."/"),
                        'RutaEvidencia'   => URL::to('/') . Storage::url($this->rutaEvidencia."/")
                    ],200);

                } else {
                    return response([
                        'status'    => true,
                        'message'   => 'Success',
                        'data'      => $ArrPrestamos,
                        'RutaCliente'     => URL::to('/') . Storage::url($this->rutaFoto."/"),
                        'RutaEvidencia'   => URL::to('/') . Storage::url($this->rutaEvidencia."/")
                    ],200);
                }

            } else {
                return response([
                    'status'    	=> false,
                    'message'   	=> 'Usuario sin acceso',
                    'data'      	=> json_encode(array()),
                    'RutaCliente'   => URL::to('/') . Storage::url($this->rutaFoto."/"),
                    'RutaEvidencia' => URL::to('/') . Storage::url($this->rutaEvidencia."/")
                ],400);
            }

        }catch (Exception $exception){
            ResponseManager::setErrorServerResponse($exception,'PrestamosController','getPrestamosEntregador',true);
        }
    }

	/*
     * API PARA PRÉSTAMOS DE CLIENTES ACTIVOS AL SISTEMA
     * METHOD: POST
     */
	public function addPrestamoClienteActivo(Request $request)
	{
		try {

			$sesion = JWTAuth::authenticate();

			$params = $request->only(
				"IdCliente",
				"IdRuta",
				"IdAutorizo",
				"IdSucursal",
				"Creador",
				"IdCobratario",
				"FechaAutorizacion",
				"FechaEntrega",
				"FechaLiquidacion",
				"IdMontoSolicitud",
				"MontoSolicitud",
				"MontoAutorizado",
				"AdeudoTotal",
				"Estatus",
				"MontoRechazo",
				"Observaciones",
				"MontoDiario",
				"TotalDevolverPrestamo",
				"NumPagoActual",
				"TotalGlobal"
			);

			$params["Estatus"] = "Pendiente";
			$params['Creador'] = $sesion->IdUsuario;


			$validation = $this->addPrestamoValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

            #BUSQUEDA Y ASIGNACION DE FOLIO
            $folio = Funciones::getMeFolio('PRESTAMO');

            if($folio['status']){
                $params['Folio'] = $folio['data']['Serie'].'-'.$folio['data']['Numero'].'-'.date('y');
            }

			$prestamo = new Prestamo($params);

			if (!$prestamo->save()) return response([
				"status" => false,
				"message"   => "Prestamo no creado."
			], 500);

            #ACTUALIZACION DE FOLIO
            $folio = Funciones::updateFolio($folio['data']['IdFolio'],$folio['data']['Numero']);

			$data['prestamo'] = $prestamo;

			return response([
				"status" => true,
				"message"   => "Prestamo creado.",
				"data"           => $data
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LISTADO DE TODOS LOS CLIENTES CON PRESTAMOS
     * METHOD: GET
     */

	/*public function GetPrestamosClientesAll(Request $request){
		try {

			$array=array();
			$search = '';
			$IdUsuario='';
			$multiWhere = array();
			if (!empty($request->get('TxtBusqueda'))) {
				$multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
			}

			// if (!empty($request->get('IdUsuario'))) {
			// 	$multiWhere[] = array("entregadoresxprestamos.IdUsuario", '=', $request->get('IdUsuario'));
			// }
			$PreAutorizado = DB::table('clientes')
			->join('prestamos', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
			->select(
				'clientes.IdCliente',
				'clientes.NombreCompleto',
				'clientes.Telefono',
				'prestamos.IdPrestamo',
				'prestamos.IdCliente',
				'prestamos.Estatus',
				'prestamos.MontoSolicitud',

			)
			->where('prestamos.Estatus','=','Pendiente')
			->where('clientes.Estatus','=','Activo')
			->where('clientes.Prospecto','=','0')
			->where($multiWhere)
			->paginate(
					$request->query("Entrada"),
					"*",
					"page",
					$request->query("Pag")
				);



			array_push($array,$PreAutorizado);
			$data['PreAutorizados']=$PreAutorizado;


			return response([
				"status"    => true,
				"message"   => "success",
				"data"      => $data,
			]);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}*/

	/*
     * API PARA AUTORIZAR PRÉSTAMOS -preautorizados DE CLIENTES ACTIVOS AL SISTEMA
     * METHOD: PUT
     */

	public function AuthorizePrestamo(int $IdPrestamo, Request $request){
		try {

			$sesion = JWTAuth::authenticate();

			$params = $request->only(
				"Estatus"
			);

			$prestamoUpdate = Prestamo::find($IdPrestamo);

			if (!empty($prestamoUpdate)) {


				$prestamoUpdate->Estatus = "PreAutorizado";


				if (!$prestamoUpdate->save()) return response([
					"status"  => false,
					"message" => 'Prestamo no Autorizado.'
				], 500);

				$data['prestamo'] = $prestamoUpdate;

				return response([
					"status" => true,
					"message" => "Prestamo Autorizado.",
					"data" => $data
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Prestamo no encontrado.',
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

	public function addPrestamoValidator($params = [], $IdPrestamo = 0)
	{
		$required = [
			"IdCliente" 		=> "required|numeric",
			"IdRuta" 			=> "required|numeric",
			"IdSucursal"        => "required|numeric",
			"Creador"        => "required|numeric",
			"IdAutorizo" 		=> "required|numeric",
			// "FechaAutorizacion" => "required|string",
			// "FechaEntrega" 		=> "required|string",
			// "FechaLiquidacion" 	=> "required|string",
			"IdMontoSolicitud" 	=> "required||numeric",
			// "MontoAutorizado" 	=> "required|numeric",
			// "MontoRechazo" 		=> "required|numeric",
			// "AdeudoTotal" 		=> "required|numeric",
			"Estatus" 			=> "required|string",
			// "Observaciones" 	=> "required|string",

		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			// 'min'       => 'El campo mínimo es de 8 carácteres.',
			// 'same'      => 'Las contraseñas no coinciden.',
			// 'unique'    => 'Este nombre de usuario ya ha sido utilizado en otra cuenta.',
			'string'    => 'El campo es requerido.',
		]);
	}

	public function MotivoRechazoValidador($params = [])
	{
		$required = [
			"MotivoRechazo" 		=> "required",
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.'
		]);
	}


	/*
     * API PARA LISTAR LOS PRESTAMOS QUE TIENE ASIGNADO UN USUARIO COBRATARIO (PARA USAR EN BAJA DE EMPLEADO).
     * METHOD: GET
    */
	public function getPrestamosxUsuario(Request $request)
	{
		try
		{
			$where = array(
				array('prestamos.IdCobratario','=',$request->get('IdUsuario')),
			);

			$Prestamos = Prestamo::query()
			->join('clientes as cl','cl.IdCliente','=','prestamos.IdCliente')
			->where($where)->get();

			return response([
				"status" 	=> true,
				"message"	=> "Prestamos encontrados.",
				"data"		=> $Prestamos,
			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA FUNCION PARA REASIGNAR LOS PRESTAMOS CUANDO SE DA DE BAJA A UN EMPLEADO
     * METHOD: POST
    */
	public function reasignarPrestamosUsuario(Request $request)
	{
		try
		{
			$params = $request->only(
				'IdPerfil',
				'IdUsuario',
				'IdCobratario',
				'FechaBaja',
				'Prestamos',
			);

			$validation = $this->DarDeBajaValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$User = User::find($params['IdUsuario']);

			// SI EL USUARIO EXISTE CAMBIAMOS AL COBRATARIO DEL PRESTAMO
			if(!empty($User))
			{
				$band = 0;
				foreach($params['Prestamos'] as $elemento)
				{
					$Prestamos = Prestamo::find($elemento['IdPrestamo']);

					if(!empty($Prestamos)){
						$Prestamos->IdCobratario = $params['IdCobratario'];
						if(!$Prestamos->save()){
							$band++;
						}
					}
				}

				if($band>0){
					return response([
						"status"  => false,
						"message" => 'Algunos Prestamos no fueron asignados.'
					], 500);
				}
				else
				{
					// SI LOS PRESTAMOS SE CAMBIARON CORRECTAMENTE, ELIMINAMOS AL EMPLEADO Y AL USUARIO
					$Empleado = Empleado::find($User->IdEmpleado);
					if(!empty($Empleado))
					{
						$Empleado->FechaBaja = date('Y-m-d',strtotime($params['FechaBaja']));
						if($Empleado->save()){
							$Empleado->delete();
						}
					}
					$UserBaja = $User->delete();

					return response([
						"status" 	=> true,
						"message"	=> "Empleado eliminado.",
						"data"		=> ''
					], 201);
				}
			}
			else
			{
                return response([
                    "status"    => false,
                    "message"   => 'Registro no encontrado',
                ],404);
            }
		}
		catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	// VALIDACION PARA DAR DE BAJA AL USUARIO-EMPLEADO
	public function DarDeBajaValidator($params = [])
	{
		$required = [
			"IdUsuario"	=> "required|numeric",
			"FechaBaja" => "required|string",
		];

		// SI ES UN GESTOR VALIDAMOS QUE ENVIE EL ID AL QUE SE LE VAN ASIGNAR SUS PRESTAMOS
		if($params['IdPerfil']==4){
			$required+= [
				"IdCobratario"	=> "required|numeric|min:1",
			];
		}

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'min'		=> 'Debe seleccionar una opción.',
			'string'    => 'El campo es requerido.',
		]);
	}



}
