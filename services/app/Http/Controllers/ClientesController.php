<?php

namespace App\Http\Controllers;

use App\Custom\CalculaMontosPrestamo;
use App\Custom\Funciones;
use App\Custom\ResponseManager;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\ClienteEvidencia;
use App\Custom\FilesManager;
use App\Models\EntregadoresxPrestamos;
use App\Models\ClientesxCobratario;
use App\Models\TazaInteres;

use Exception;

class ClientesController extends Controller
{
	private $rutaFoto = 'imgclientes';
	private $rutaEvidencia = 'imgClientesEvidencias';
	private $location = 'ClientesController';
	/*
	 * Funciones CRUD
	 *
	 */

	/*
     * API PARA LISTADO DE TODOS LOS CLIENTES
     * METHOD: GET
     */
	public function findAll(Request $request)
	{
		try {

			$search = '';
			$multiWhere = array();
			$relations = array();
            $estatusPendiente = '';

			if ( !empty($request->get('TxtBusqueda')) ) {
				// $search = $request->get('TxtBusqueda');
				$multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
			}

            if ( !empty($request->get('Estatus')) ) {
                $multiWhere[] = array("clientes.Estatus", "=", $request->get('Estatus'));
            }

            if ( !empty($request->get('EstatusPrestamo')) ) {
                $multiWhere[] = array("prestamos.Estatus", "=", $request->get('EstatusPrestamo'));

            }

			$clienteFindAll = Cliente::query()
                ->join('prestamos','clientes.IdCliente','=','prestamos.IdCliente')
                ->where($multiWhere)
                ->paginate(
					$request->query("Entrada"),
					array('clientes.IdCliente','clientes.IdCliente','clientes.NombreCompleto','clientes.Telefono',
                        'clientes.Estatus','clientes.Prospecto','clientes.MotivoRechazo','clientes.FechaRechazo','clientes.Origen',
                        'prestamos.IdPrestamo','prestamos.IdCobratario','prestamos.MontoSolicitud','prestamos.PrestamoMotRechazo as motoRechazoP',),
					"page",
					$request->query("Pag")
				);

			return response([
				"status" 	=> true,
				"message"	=> "Clientes encontrados.",
				"data"		=> $clienteFindAll,
				"rutaFoto"  => URL::to('/') . Storage::url("imgclientes/"),

			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'findAll');
		}
	}



    public function findProspectosPrestamos(Request $request) {
        try {

            $search = '';
            $multiWhere = array();

            if ( !empty($request->get('TxtBusqueda')) ) {
                // $search = $request->get('TxtBusqueda');
                $multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
            }

            if ( !empty($request->get('Estatus')) ) {
                $multiWhere[] = array("clientes.Estatus", "=", $request->get('Estatus'));
            }

            if ( !empty($request->get('EstatusPrestamo')) ) {
                $multiWhere[] = array("prestamos.Estatus", "=", $request->get('EstatusPrestamo'));
            }

            if ( !empty($request->get('isCliente')) ) {
                $multiWhere[] = array("clientes.Prospecto", "=", '0');
            }

            if ( !empty($request->get('isProspecto')) ) {
                $multiWhere[] = array("clientes.Prospecto", "=", '1');
            }

            if ( !empty($request->get('IdCliente')) ) {
                $multiWhere[] = array("clientes.IdCliente", "=", $request->get('IdCliente'));
            }


            $prospectosPrestamos = Cliente::query()
                ->join('prestamos','clientes.IdCliente','=','prestamos.IdCliente')
                ->where($multiWhere)
                ->orderBy('prestamos.created_at','DESC')
                ->paginate(
                    $request->query("Entrada"),
                    array('clientes.IdCliente','clientes.NombreCompleto','clientes.Telefono',
                        'clientes.Estatus','clientes.Prospecto','clientes.MotivoRechazo','clientes.FechaRechazo','clientes.Origen',
                        'prestamos.IdPrestamo','prestamos.IdCliente as PIdCliente','prestamos.IdCobratario','prestamos.MontoSolicitud','prestamos.PrestamoMotRechazo',
                        'prestamos.Estatus as EstatusP','prestamos.Folio','prestamos.created_at as FechaPrestamo',
                        'prestamos.FechaRechazo as FRechazoPrestamo','prestamos.EstatusEntrega','prestamos.IdPrestamoxCancelacion'),
                    "page",
                    $request->query("Pag")
                );

            return response([
                "status" 	=> true,
                "message"	=> "Prospectos encontrados.",
                "data"		=> $prospectosPrestamos,
                "rutaFoto"  => URL::to('/') . Storage::url("imgclientes/"),

            ], 200);
        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception, $this->location,'findProspectosPrestamos');
        }
    }

	/*
     * API PARA LA BÚSQUEDA DE CLIENTES ESPECÍFICOS
     * METHOD: GET
     */
	public function findOne($IdCliente)
	{
		try {

			$clienteFindOne = Cliente::find($IdCliente);

			if (!empty($clienteFindOne))
			{
				$clienteFindOne->UrlImg = ($clienteFindOne->UrlImg == '') ? 'picture.png' : $clienteFindOne->UrlImg;

				// OBTENEMOS EL PRESTAMO ACTIVO DEL CLIENTE PROSPECTO
				$wheres = array(
					array('IdCliente', 	'=', $clienteFindOne->IdCliente),
					array('Estatus', 	'=', 'Pendiente')
				);
				$Prestamo = Prestamo::where($wheres)->first();

				// OBTENEMOS LAS EVIDENCIAS DEL INE
				$wheres2 = array(
					array('IdCliente','=',$clienteFindOne->IdCliente),
					array('TipoEvidencia','=','Ine')
				);

				// OBTENEMOS LAS EVIDENCIAS DEL DOMICILIO
				$wheres3 = array(
					array('IdCliente','=',$clienteFindOne->IdCliente),
					array('TipoEvidencia','=','Domicilio')
				);

				// OBTENEMOS LAS EVIDENCIAS DE LA EMPRESA
				$wheres4 = array(
					array('IdCliente','=',$clienteFindOne->IdCliente),
					array('TipoEvidencia','=','Empresa')
				);

				$EvidenciaIne = ClienteEvidencia::where($wheres2)->get();
				$EvidenciaDom = ClienteEvidencia::where($wheres3)->get();
				$EvidenciaEmp = ClienteEvidencia::where($wheres4)->get();

				$data['Cliente'] = $clienteFindOne;
				$data['Prestamo'] = $Prestamo;
				$data['EvidenciaIne'] = $EvidenciaIne;
				$data['EvidenciaDom'] = $EvidenciaDom;
				$data['EvidenciaEmp'] = $EvidenciaEmp;

				return response([
					"status"  	=> true,
					"message" 	=> "Cliente encontrado.",
					"data"    	=> $data,
					"RutaFile"  => URL::to('/') . Storage::url($this->rutaFoto."/"),
					"RutaEvid"	=> URL::to('/') . Storage::url($this->rutaEvidencia."/"),
				], 200);
			} else {
				return response([
					"status"  => false,
					"message" => 'Cliente no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE CLIENTES ACTIVOS ESPECÍFICOS
     * METHOD: GET
     */

	public function findOneClienteActivo($IdCliente)
	{
		try {

			$clienteFindOne = Cliente::find($IdCliente);

			if (!empty($clienteFindOne))
			{
				$clienteFindOne->UrlImg = ($clienteFindOne->UrlImg == '') ? 'picture.png' : $clienteFindOne->UrlImg;

				// OBTENEMOS EL PRESTAMO ACTIVO DEL CLIENTE PROSPECTO
				$wheres = array(
					array('IdCliente', 	'=', $clienteFindOne->IdCliente),
					array('Estatus', 	'=', 'Activo')
				);

				$data['Cliente'] = $clienteFindOne;


				return response([
					"status"  	=> true,
					"message" 	=> "Cliente encontrado.",
					"data"    	=> $data,
					"RutaFile"  => URL::to('/') . Storage::url($this->rutaFoto."/"),
				], 200);
			} else {
				return response([
					"status"  => false,
					"message" => 'Cliente no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE PRESTMAOS DE CLIENTES ACTIVOS ESPECÍFICOS
     * METHOD: GET
     */

	public function findOndePrestamoCliente( $IdCliente){
		try {

			$array=array();
			// $multiWhere = array();
			// if (!empty($request->get('TxtBusqueda'))) {
			// 	$multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
			// }

			$clienteFindAll =  DB::table('clientes')
			->join('prestamos', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
			->select(
				'clientes.IdCliente',
				'prestamos.MontoSolicitud',
				'prestamos.created_at',
				'prestamos.Estatus',
				'prestamos.IdRuta',
				'prestamos.Creador',
				'prestamos.IdSucursal',
				'prestamos.IdCobratario'

			)->where('clientes.Estatus','=','Activo')
			->where('prestamos.IdCliente','=',$IdCliente)
			->get();

				$data['cliente'] = $clienteFindAll;

			return response([
				"status" 	=> true,
				"message"	=> "Clientes encontrados.",
				"data"		=> $data,
				"rutaFoto"  => URL::to('/') . Storage::url("imgclientes/"),

			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	public function findOneRutaCliente($IdCliente){
		try {

			$array=array();
			$clienteFindAll =  DB::table('clientes')
			->join('rutas', 'rutas.IdRuta', '=', 'clientes.IdRuta')
			->join('sucursales', 'sucursales.IdSucursal', '=', 'rutas.IdSucursal')
			->join('prestamos', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
			->join('users', 'users.IdUsuario', '=', 'prestamos.IdCobratario')
			->select(
				'rutas.NombreRuta',
				'sucursales.Nombre',
				'users.NombreCompleto'

			)->where('clientes.Estatus','=','Activo')
			->where('clientes.IdCliente','=',$IdCliente)
			->get();

				$data['cliente'] = $clienteFindAll;

			return response([
				"status" 	=> true,
				"message"	=> "Clientes encontrados.",
				"data"		=> $data,
				"rutaFoto"  => URL::to('/') . Storage::url("imgclientes/"),

			], 200);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA AGREGAR CLIENTES
     * METHOD: POST
     */
	public function add(Request $request)
	{
		try {

			$params = $request->only(
				'IdCliente',
				'Nombre',
				'ApellidoPat',
				'ApellidoMat',
				'FechaNacimiento',
				'Correo',
				'Telefono',
				'DescripcionEmpresa',
				'Calle',
				'NoInt',
				'NoExt',
				'Colonia',
				'Cp',
				'Referencias',
				'IdEstado',
				'IdMunicipio',
				'Latitud',
				'Longitud',
				'IdRuta',
                'Origen'
				// 'Prospecto',
				// 'Estatus',

			);

			$paramsPrestamo = $request->only(
				'Origen',
				'IdPrestamo',
				'IdCliente',
				'IdSucursal',
				'IdRuta',
				'IdCobratario',
				'Creador',
				'MontoSolicitud',
				// 'IdMontoSolicitud',
				'Estatus',
				'Observaciones',
				'EstatusCancelacion'
			);

			$paramsEvidencias = $request->only(
				'ListaINEArray',
				'ListaDomicilioArray',
				'ListaEmpresaArray',
			);

			// VALIDAMOS LOS DATOS DEL CLIENTE
			$validation = $this->addClienteValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			// VALIDAMOS LOS DATOS DEL PRESTAMO
			$validation = $this->addPrestamoValidator($paramsPrestamo);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$params["FechaNacimiento"] = date('Y-m-d',strtotime($params["FechaNacimiento"]));
			$params['NombreCompleto'] = $params['Nombre'].' '.$params['ApellidoPat'].' '.$params['ApellidoMat'];

			$Cliente = Cliente::find($params['IdCliente']);

			#FILE_MANEGER
			if ($request->hasFile('Imagen')) {
				$archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Imagen');
				$params['Imagen'] = $archivo['status'] ? $archivo['OrigiName'] : '';
				$params['UrlImg'] = $archivo['status'] ? $archivo['HashName'] : '';
			} else {
				$params['Imagen'] = '';
				$params['UrlImg'] = '';
			}

			// SI ENCUENTRA ALGUN DATO ACTUALIZARA USUARIO DE LO CONTRARIO LO INSERTARA
			if(!empty($Cliente))
			{
				$Cliente->Nombre 			= $params['Nombre'];
				$Cliente->ApellidoPat 		= $params['ApellidoPat'];
				$Cliente->ApellidoMat 		= $params['ApellidoMat'];
				$Cliente->FechaNacimiento 	= $params['FechaNacimiento'];
				$Cliente->Telefono 			= $params['Telefono'];
				$Cliente->DescripcionEmpresa= $params['DescripcionEmpresa'];
				$Cliente->NombreCompleto	= $params['NombreCompleto'];
				$Cliente->Calle 			= $params['Calle'];
				$Cliente->NoInt 			= ( !empty($params['NoInt']) ) ? $params['NoInt'] : '';
				$Cliente->NoExt 			= $params['NoExt'];
				$Cliente->Colonia 			= $params['Colonia'];
				$Cliente->Cp 				= $params['Cp'];
				$Cliente->Referencias 		= $params['Referencias'];
				$Cliente->IdEstado 			= $params['IdEstado'];
				$Cliente->IdMunicipio 		= $params['IdMunicipio'];
				$Cliente->Latitud 			= $params['Latitud'];
				$Cliente->Longitud 			= $params['Longitud'];
				$Cliente->IdRuta 			= $params['IdRuta'];

				if($params['Imagen']!=''){$Cliente->Imagen = $params['Imagen'];}
				if($params['UrlImg']!=''){$Cliente->UrlImg = $params['UrlImg'];}
			}
			else
			{
				$Cliente = new Cliente($params);
			}

			// GUARDAMOS AL CLIENTE
			if (!$Cliente->save()) return response([
				"status" 	=> false,
				"message"	=> "Cliente no guardado."
			], 500);

			// MANDAMOS A GUARDAR EL PRESTAMO
			$IdCliente = $Cliente->IdCliente;
			$GuardarPrestamo = $this->addPrestamo($paramsPrestamo,$IdCliente);

			if (!$GuardarPrestamo) return response([
				"status" 	=> false,
				"message"   => "Prestamo no guardado."
			], 500);

			// MANDAMOS A GUARDAR LAS EVIDENCIAS
			$GuardarEvidencias = $this->addEvidencias($request,$IdCliente);

			if (!$GuardarEvidencias) return response([
				"status" 	=> false,
				"message"   => "Evidencias no guardadas."
			], 500);

			$data['cliente'] = $Cliente;

			return response([
				"status" 	=> true,
				"message"   => "Cliente creado.",
				"data"		=> $data
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA ACTUALIZAR CLIENTES ACTIVOS
     * METHOD: POST
     */

	public function UpdateClienteActivo(Request $request)
	{
		try {

			$params = $request->only(
				'IdCliente',
				'Nombre',
				'ApellidoPat',
				'ApellidoMat',
				'FechaNacimiento',
				'Correo',
				'Telefono',
				'DescripcionEmpresa',
				'Calle',
				'NoInt',
				'NoExt',
				'Colonia',
				'Cp',
				'Referencias',
				'IdEstado',
				'IdMunicipio',
				'Latitud',
				'Longitud',
				// 'Prospecto',
				// 'Estatus',

			);

			// VALIDAMOS LOS DATOS DEL CLIENTE
			$validation = $this->addClienteValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			// VALIDAMOS LOS DATOS DEL PRESTAMO

			$params["FechaNacimiento"] = date('Y-m-d',strtotime($params["FechaNacimiento"]));
			$params['NombreCompleto'] = $params['Nombre'].' '.$params['ApellidoPat'].' '.$params['ApellidoMat'];

			$Cliente = Cliente::find($params['IdCliente']);

			#FILE_MANEGER
			if ($request->hasFile('Imagen')) {
				$archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Imagen');
				$params['Imagen'] = $archivo['status'] ? $archivo['OrigiName'] : '';
				$params['UrlImg'] = $archivo['status'] ? $archivo['HashName'] : '';
			} else {
				$params['Imagen'] = '';
				$params['UrlImg'] = '';
			}

			// SI ENCUENTRA ALGUN DATO ACTUALIZARA USUARIO DE LO CONTRARIO LO INSERTARA
			if(!empty($Cliente))
			{
				$Cliente->Nombre 			= $params['Nombre'];
				$Cliente->ApellidoPat 		= $params['ApellidoPat'];
				$Cliente->ApellidoMat 		= $params['ApellidoMat'];
				$Cliente->FechaNacimiento 	= $params['FechaNacimiento'];
				$Cliente->Telefono 			= $params['Telefono'];
				$Cliente->DescripcionEmpresa= $params['DescripcionEmpresa'];
				$Cliente->NombreCompleto	= $params['NombreCompleto'];
				$Cliente->Calle 			= $params['Calle'];
				$Cliente->NoInt 			= $params['NoInt'];
				$Cliente->NoExt 			= $params['NoExt'];
				$Cliente->Colonia 			= $params['Colonia'];
				$Cliente->Cp 				= $params['Cp'];
				$Cliente->Referencias 		= $params['Referencias'];
				$Cliente->IdEstado 			= $params['IdEstado'];
				$Cliente->IdMunicipio 		= $params['IdMunicipio'];
				$Cliente->Latitud 			= $params['Latitud'];
				$Cliente->Longitud 			= $params['Longitud'];

				if($params['Imagen']!=''){$Cliente->Imagen = $params['Imagen'];}
				if($params['UrlImg']!=''){$Cliente->UrlImg = $params['UrlImg'];}
			}
			else
			{
				$Cliente = new Cliente($params);
			}

			// GUARDAMOS AL CLIENTE
			if (!$Cliente->save()) return response([
				"status" 	=> false,
				"message"	=> "Cliente no guardado."
			], 500);

			// MANDAMOS A GUARDAR EL PRESTAMO
			$IdCliente = $Cliente->IdCliente;

			$data['cliente'] = $Cliente;

			return response([
				"status" 	=> true,
				"message"   => "Cliente creado.",
				"data"		=> $data
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * FUNCION PARA AGREGAR O MODIFICAR INFORMACIÓN DE UN PRESTAMO
     * METHOD: POST
	*/
	public function addPrestamo($params,$IdCliente)
	{
		$sesion = JWTAuth::authenticate();
		$Prestamo = Prestamo::find($params['IdPrestamo']);
		$params['IdCliente'] = $IdCliente;
		$params['Creador'] = $sesion->IdUsuario; //cambiar IdEmpleado a Creador en BD

		if($params['Origen'] == 'app')
		{
			$params['IdSucursal']   = $sesion->IdSucursal;
			$params['IdCobratario'] = $sesion->IdUsuario;
			// $params['IdRuta'] = 1;
		}

        // SI RECIBE ID DEL PRESTAMO ACTUALIZA, SINO INSERTA
        $IdFolio = 0;
        if (!empty($Prestamo))
        {
            $Prestamo->IdSucursal 		  = $params['IdSucursal'];
            $Prestamo->IdRuta 			  = !empty($params['IdRuta']) ? $params['IdRuta'] : 1;
            $Prestamo->IdCobratario		  = $params['IdCobratario'];
            $Prestamo->MontoSolicitud 	  = $params['MontoSolicitud'];
            $Prestamo->Observaciones 	  = $params['Observaciones'];
            $Prestamo->Origen 	          = $params['Origen'];

            if (!$Prestamo->save()){
                return false;
            }else{
                return true;
            }


        } else {
            $folio = Funciones::getMeFolio('PRESTAMO');

            if($folio['status']){
                $params['Folio'] = $folio['data']['Serie'].'-'.$folio['data']['Numero'].'-'.date('y');
                $IdFolio = $folio['data']['IdFolio'];
            }

            $Prestamo = new Prestamo($params);

            if (!$Prestamo->save()){
                return false;
            }else{
                $folio = Funciones::updateFolio($folio['data']['IdFolio'],$folio['data']['Numero']);
                return true;
            }
        }

    }

    /*
     * FUNCION PARA AGREGAR O MODIFICAR INFORMACIÓN DE LAS EVIDENCIAS
     * METHOD: POST
    */
    public function addEvidencias($request,$IdCliente)
    {
        //$arrEvidencias1 = json_decode($params['ListaINEArray']);
        //$arrEvidencias2 = json_decode($params['ListaDomicilioArray']);
        //$arrEvidencias3 = json_decode($params['ListaEmpresaArray']);

		$band = 0;

		// INSERTAMOS LAS EVIDENCIAS DEL INE
		if($request->hasFile('FileINE'))
		{
			foreach($request->file('FileINE') as $element)
			{
				$archivo = FilesManager::UploadMultiThumbnailImages($element, '/'.$this->rutaEvidencia);
				$fileName = $archivo['status'] ? $archivo['HashName'] : '';

				$newArray = array();
				$newArray['IdCliente']		= $IdCliente;
				$newArray['Evidencia']		= $fileName;
				$newArray['Observaciones']	= '';
				$newArray['TipoEvidencia']	= 'Ine';
				$ClientEvidencia = new ClienteEvidencia($newArray);

				if (!$ClientEvidencia->save()){
					return false;
				}
			}
		}

		// INSERTAMOS LAS EVIDENCIAS DEL INE
		if($request->hasFile('FileDom'))
		{
			foreach($request->file('FileDom') as $element)
			{
				$archivo = FilesManager::UploadMultiThumbnailImages($element, '/'.$this->rutaEvidencia);
				$fileName = $archivo['status'] ? $archivo['HashName'] : '';

				$newArray = array();
				$newArray['IdCliente']		= $IdCliente;
				$newArray['Evidencia']		= $fileName;
				$newArray['Observaciones']	= '';
				$newArray['TipoEvidencia']	= 'Domicilio';
				$ClientEvidencia = new ClienteEvidencia($newArray);

				if (!$ClientEvidencia->save()){
					return false;
				}
			}
		}

		// INSERTAMOS LAS EVIDENCIAS DE LA EMPRESA
		if($request->hasFile('FileEmp'))
		{
			foreach($request->file('FileEmp') as $element)
			{
				$archivo = FilesManager::UploadMultiThumbnailImages($element, '/'.$this->rutaEvidencia);
				$fileName = $archivo['status'] ? $archivo['HashName'] : '';

				$newArray = array();
				$newArray['IdCliente']		= $IdCliente;
				$newArray['Evidencia']		= $fileName;
				$newArray['Observaciones']	= '';
				$newArray['TipoEvidencia']	= 'Empresa';
				$ClientEvidencia = new ClienteEvidencia($newArray);

				if (!$ClientEvidencia->save()){
					return false;
				}
			}
		}

		return true;
	}

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UN CLIENTE
     * METHOD: PUT
     */
	public function update(Request $request, int $IdCliente)
	{
		try {

			$params = $request->only(
				'Nombre',
				'ApellidoPat',
				'ApellidoMat',
				'FechaNacimiento',
				'Correo',
				'Telefono',
				'DescripcionEmpresa',
				'Calle',
				'NoInt',
				'NoExt',
				'Colonia',
				'Cp',
				'Referencias',
				'IdEstado',
				'IdMunicipio',
				'Latitud',
				'Longitud',
				'Prospecto',
				'Estatus',
			);

			$validation = $this->addClienteValidator($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

			$clienteUpdate = Cliente::find($IdCliente);

			if (!empty($clienteUpdate)) {

				$clienteUpdate->Nombre = 			$params['Nombre'];
				$clienteUpdate->ApellidoPat = 		$params['ApellidoPat'];
				$clienteUpdate->ApellidoMat = 		$params['ApellidoMat'];
				$clienteUpdate->FechaNacimiento = 		$params['FechaNacimiento'];
				$clienteUpdate->Telefono = 			$params['Telefono'];
				$clienteUpdate->DescripcionEmpresa = $params['DescripcionEmpresa'];

				$clienteUpdate->Calle = 			$params['Calle'];
				$clienteUpdate->NoInt = 			$params['NoInt'];
				$clienteUpdate->NoExt = 			$params['NoExt'];
				$clienteUpdate->Colonia = 			$params['Colonia'];
				$clienteUpdate->Cp = 				$params['Cp'];
				$clienteUpdate->Referencias = 		$params['Referencias'];
				$clienteUpdate->IdEstado = 		$params['IdEstado'];
				$clienteUpdate->IdMunicipio = 		$params['IdMunicipio'];
				$clienteUpdate->Latitud = 			$params['Latitud'];
				$clienteUpdate->Longitud = 			$params['Longitud'];

				$clienteUpdate->Prospecto =			$params['Prospecto'];
				$clienteUpdate->Estatus = 			$params['Estatus'];


				#FILE_MANEGER
				if ($request->hasFile('Imagen')) {
					$archivo = FilesManager::UploadFileStorage($request, '/'.$this->rutaFoto, 'Imagen');
					$params['Imagen'] = $archivo['status'] ? $archivo['OrigiName'] : '';
					$params['UrlImg'] = $archivo['status'] ? $archivo['HashName'] : '';
				}

				#SETER DATA DEFAULT
				$clienteUpdate->NombreCompleto = $params['Nombre'] . ' ' . $params['ApellidoPat'] . ' ' . $params['ApellidoMat'];

				if (!$clienteUpdate->save()) return response([
					"status"  => false,
					"message" => 'Cliente no actualizado.'
				], 500);

				$data['cliente'] = $clienteUpdate;

				return response([
					"status" => true,
					"message" => "Cliente actualizado.",
					"data" => $data,
					"rutaFoto"  => URL::to('/')  . $this->rutaFoto
				], 200);
			} else {

				return response([
					"status"  => true,
					"message" => 'Cliente no encontrado.',
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
	public function delete($IdCliente)
	{
		try {

			$respose = Cliente::find($IdCliente);

			if (!empty($respose)) {

				if ($respose->delete()) {

					return response([
						"status"  => true,
						"message" => 'Cliente eliminado.'
					], 200);
				} else {

					return response([
						"status"  => false,
						"message" => 'Cliente no pudo ser eliminado.',
					], 500);
				}
			} else {

				return response([
					"status"  => false,
					"message" => 'Cliente no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}


	/*
     * API PARA CAMBIO DE ESTATUS EN PRESTAMOS - CLIENTES
     * METHOD: PUT
     */
	public function clientesPrestamosCambioEstatus(Request $request){
		try {
			$sesion = JWTAuth::authenticate();

			$params = $request->only(
				'IdPrestamo',
                'IdCliente',
                'MotivoRechazo',
                'Operacion',
			);

            $validation = $this->clienteEtapas($params);

            if ($validation->fails()) return response([
                "message"   => "Parámetros inválidos.",
                "errors"    => $validation->errors()
            ], 400);

            // Encontrando Prestamo
            $Prestamo   = Prestamo::find($params['IdPrestamo']);
            $cliente    = Cliente::find($params['IdCliente']);

            $eCode      = '';
            $message    = '';
            $status     = null;
            $data       = array();

            if (!empty($Prestamo) && !empty($cliente) ) {

                ## POSIBLES OPERACIONES DE CAMBIO DE ESTATUS PARA PROSPECTOS Y CLIENTES.
                switch ($params['Operacion']) {

                    ## ****************** SECTOR DE OPERACIONES DE PROSPECTOS ******************

                    ## ACEPTO PROSPECTO Y PRESTAMO (PREAUTORIZAR PRESTAMO)
                    case 'AceptaAmbos':

                        ## SETTER DATOS PRESTAMO
                        $Prestamo->IdAutorizo        = $sesion->IdUsuario;
                        $Prestamo->FechaAutorizacion = date('Y-m-d h:i:s');
                        $Prestamo->Estatus           = "PreAutorizado";
                        $Prestamo->EstatusEntrega    = "Pendiente";

                        $calculo = CalculaMontosPrestamo::calulaMontos($Prestamo->MontoSolicitud);
                        if( $calculo['status'] ) {
                            $Prestamo->NumPagos                 = $calculo['data']['diasPrestamo'] ;
                            $Prestamo->MontoDiario              = $calculo['data']['montoDiario'];
                            $Prestamo->TotalDevolverPrestamo    = $calculo['data']['montoTotalDevolver'];
                            $Prestamo->TotalMultas              = 0;
                            $Prestamo->TotalGlobal              = $calculo['data']['montoTotalDevolver'];
                            $Prestamo->NumPagoActual            = 1;

                        }

                        //Guardando Prestamo
                        if (!$Prestamo->save()) return response([
                            "status"  => false,
                            "message" => 'Prestamo no actualizado.'
                        ], 500);


                        ## SETTER DATOS PRESTAMO
                        $cliente->Prospecto = 0;
                        $cliente->Estatus   = "Activo";

                        // Guardando al cliente
                        if (!$cliente->save()) return response([
                            "status"  => false,
                            "message" => 'cliente no Autorizado.'
                        ], 500);


                        $array = array(
                            'IdCliente' => $params['IdCliente'],
                            'IdUsuario' => $Prestamo->IdCobratario,
                        );

                        if (!ClientesxCobratario::query()->insert($array)) return response([
                            "status" => false,
                            "message"   => "No se autorizó el prestamo."
                        ], 500);



                        ## SETTERS RESPONSE
                        $data['cliente']    = $cliente;
                        $data['prestamo']   = $Prestamo;
                        $eCode              = 201;
                        $message            = 'Success';
                        $status             =  true;


                        break;

                    ## ACEPTO PROSPECTO Y  RECHAZO PRESTAMO (CLIENTE-ACTIVO  PRESTAMO-RECHAZADO)
                    case 'AceptaProspecto':

                        $validation = $this->MotivoRechazoValidador($params);

                        if ($validation->fails()) return response([
                            "message"   => "Parámetros inválidos.",
                            "errors"    => $validation->errors()
                        ], 400);


                        ## SETTER DATOS PRESTAMO
                        $Prestamo->Estatus              = "Rechazado";
                        $Prestamo->PrestamoMotRechazo   = $params['MotivoRechazo'];
                        $Prestamo->IdRechazo            = $sesion->IdUsuario;
                        $Prestamo->FechaRechazo         = date('Y-m-d h:i:s');

                        if (!$Prestamo->save()) return response([
                            "status"  => false,
                            "message" => 'Prestamo no actualizado.'
                        ], 500);

                        ## SETTER DATOS CLIENTE
                        $cliente->Prospecto =		0;
                        $cliente->Estatus =  "Activo";

                        // Guardando al cliente
                        if (!$cliente->save()) return response([
                            "status"  => false,
                            "message" => 'cliente no Autorizado.'
                        ], 500);

                        $array = array(
                            'IdCliente' => $params['IdCliente'],
                            'IdUsuario' => $sesion->IdUsuario
                        );

                        if (!ClientesxCobratario::query()->insert($array)) return response([
                            "status" => false,
                            "message"   => "No se autorizó el prestamo."
                        ], 500);


                        ## SETTERS RESPONSE
                        $data['cliente']    = $cliente;
                        $data['prestamo']   = $Prestamo;
                        $eCode              = 201;
                        $message            = 'Prospecto Aceptado';
                        $status             =  true;

                        break;

                    ## RECHAZO PROSPECTO Y PRESTAMO (CLIENTE-RECAHZADO  PRESTAMO-RECHAZADO)
                    case 'RechazoAmbos':

                        $validation = $this->MotivoRechazoValidador($params);

                        if ($validation->fails()) return response([
                            "message"   => "Parámetros inválidos.",
                            "errors"    => $validation->errors()
                        ], 400);


                        ## SETTER CLIENTE
                        $cliente->Prospecto     = 1;
                        $cliente->Estatus       = "Rechazado";
                        $cliente->MotivoRechazo = $params['MotivoRechazo'];
                        $cliente->FechaRechazo  = date('Y-m-d');

                        if (!$cliente->save()) return response([
                            "status"  => false,
                            "message" => 'cliente no Autorizado.'
                        ], 500);

                        ## SETTER PRESTAMO
                        $Prestamo->Estatus              = "Rechazado";
                        $Prestamo->PrestamoMotRechazo   = "Rechazado por motivo del Prospecto";
                        $Prestamo->IdRechazo            = $sesion->IdUsuario;
                        $Prestamo->FechaRechazo         = date('Y-m-d h:i:s');

                        if (!$Prestamo->save()) return response([
                            "status"  => false,
                            "message" => 'Prestamo no actualizado.'
                        ], 500);

                        ## SETTERS RESPONSE
                        $data['cliente']    = $cliente;
                        $data['prestamo']   = $Prestamo;
                        $eCode              = 201;
                        $message            = 'Registros Rechazados';
                        $status             =  true;

                        break;

                    ## REACTIVO PROSPECTO Y PRESTAMO (CLIENTE-PENDIENTE  PRESTAMO-PENDIENTE)
                    case 'ReactivaAmbos':

                        $Prestamo->Estatus = "Pendiente";
                        $Prestamo->PrestamoMotRechazo   = "";
                        $Prestamo->IdRechazo            = 0;
                        $Prestamo->FechaRechazo         = NULL;

                        ##--- ANEXAR BITACORA  de cambios.

                        if (!$Prestamo->save()) return response([
                            "status"  => false,
                            "message" => 'Prestamo no actualizado.'
                        ], 500);

                        $cliente->Prospecto       = 1;
                        $cliente->Estatus         = "Pendiente";
                        $cliente->MotivoRechazo   = "";
                        $cliente->FechaRechazo    = NULL;

                        if (!$cliente->save()) return response([
                            "status"  => false,
                            "message" => 'cliente no Autorizado.'
                        ], 500);


                        ## SETTERS RESPONSE
                        $data['cliente']    = $cliente;
                        $data['prestamo']   = $Prestamo;
                        $eCode              = 201;
                        $message            = 'Success';
                        $status             =  true;

                        break;

                    ## ****************** SECTOR DE PRESTAMOS DE CLIENTES REINCIDENTES DE PRESTAMOS ******************

                    ## ACEPTO PRESTAMO
                    case 'AceptoPrestamo':

                        ## SETTER DATOS PRESTAMO
                        $Prestamo->IdAutorizo        = $sesion->IdUsuario;
                        $Prestamo->FechaAutorizacion = date('Y-m-d h:i:s');
                        $Prestamo->Estatus           = "PreAutorizado";
                        $Prestamo->EstatusEntrega    = "Pendiente";

                        $calculo = CalculaMontosPrestamo::calulaMontos($Prestamo->MontoSolicitud);
                        if( $calculo['status'] ) {
                            $Prestamo->NumPagos                 = $calculo['diasPrestamo'] ;
                            $Prestamo->MontoDiario              = $calculo['montoDiario'];
                            $Prestamo->TotalDevolverPrestamo    = $calculo['montoTotalDevolver'];
                            $Prestamo->TotalMultas              = 0;
                            $Prestamo->TotalGlobal              = $calculo['montoTotalDevolver'];
                            $Prestamo->NumPagoActual            = 1;
                            //$Prestamo->MontoAutorizado = 0;
                        }

                        //Guardando Prestamo
                        if (!$Prestamo->save()) return response([
                            "status"  => false,
                            "message" => 'Prestamo no actualizado.'
                        ], 500);


                        ## SETTERS RESPONSE
                        $data['cliente']    = $cliente;
                        $data['prestamo']   = $Prestamo;
                        $eCode              = 201;
                        $message            = 'Success';
                        $status             =  true;

                        break;

                    ## RECHAZO PRESTAMO
                    case 'RechazoPrestamo':

                        $validation = $this->MotivoRechazoValidador($params);

                        if ($validation->fails()) return response([
                            "message"   => "Parámetros inválidos.",
                            "errors"    => $validation->errors()
                        ], 400);

                        ## SETTER PRESTAMO
                        $Prestamo->Estatus              = "Rechazado";
                        $Prestamo->PrestamoMotRechazo   = $params['MotivoRechazo'];
                        $Prestamo->FechaRechazo         = date('Y-m-d h:i:s');

                        if (!$Prestamo->save()) return response([
                            "status"  => false,
                            "message" => 'Prestamo no actualizado.'
                        ], 500);


                        ## SETTERS RESPONSE
                        $data['cliente']    = $cliente;
                        $data['prestamo']   = $Prestamo;
                        $eCode              = 201;
                        $message            = 'Prestamo Rechazado';
                        $status             =  true;

                        break;

                    ## RECTIVAR PRESTAMO RECHAZADO DEL CLIENTE
                    case 'ReactivaPrestamo':

                        $Prestamo->Estatus              = "Pendiente";
                        $Prestamo->PrestamoMotRechazo   = '';
                        $Prestamo->FechaRechazo         = NULL;

                        if (!$Prestamo->save()) return response([
                            "status"  => false,
                            "message" => 'Prestamo no actualizado.'
                        ], 500);

                        ## SETTERS RESPONSE
                        $data['prestamo']   = $Prestamo;
                        $eCode              = 201;
                        $message            = 'Prestamo Reactivado';
                        $status             =  true;

                        break;

                }

                return response([
                    "status"    => $status,
                    "message"   => $message,
                    "data"      => $data,
                ], $eCode);


            }else {
                return response([
                    "status"  => true,
                    "message" => 'Registro no encontrado',
                    "data"	  =>''
                ], 404);
            }


		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception,"ClientesController","clientesPrestamosCambioEstatus",true);
		}
	}

	/*
     * API PARA ASIGNAR PRESTAMO-INSERT EN TABLA MEDIA ENTREGADORESxPRESTAMO
     * METHOD: PUT-INSERT
    */

	public function AsignarPrestamo(Request $request){
		try
		{
			$params = $request->only(
				"IdUsuario",
				"arreglo",
			);
			$validation = $this->IdUsuarioValidador($params);

			if ($validation->fails()) return response([
				"message"   => "Parámetros inválidos.",
				"errors"    => $validation->errors()
			], 400);

            $entregadorXprestamo    = '';
			$listaPrestamos         = $params['arreglo'];

			foreach($listaPrestamos as $elemento)  {
				$newArray = array(
					'IdCliente'     => $elemento['IdCliente'],
					'IdUsuario'     => $params['IdUsuario'],
					'IdPrestamo'    => $elemento['IdPrestamo']
				);

				$entregadorXprestamo = new EntregadoresxPrestamos();
				if (!$entregadorXprestamo->insert($newArray)) return response([
					"status" => false,
					"message"   => "No se autorizó el prestamo."
				], 500);

				$prestamoUpdate = Prestamo::query()->find($elemento['IdPrestamo']);

				if(!empty($prestamoUpdate)) {
					$prestamoUpdate->Estatus        = "Asignado";

					if (!$prestamoUpdate->save()) return response([
						"status"  => false,
						"message" => 'Prestamo no Autorizado.'
					], 500);

				}
			}

			return response([
				"status" 	=> true,
				"message"   => "Prestamos Asignados",
			], 201);

		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'AsignarPrestamo');
		}
	}

	/*
     * API PARA RETORNAR PRESTAMOS PRE-AUTORIZADOS
     * METHOD: POST
     */

	public function RetornarPrestamoAutorizado(Request $request){
		try
		{
            $params = $request->only(
                "IdUsuario",
                "arreglo",
            );
            $validation = $this->IdUsuarioValidador($params);

            if ($validation->fails()) return response([
                "message"   => "Parámetros inválidos.",
                "errors"    => $validation->errors()
            ], 400);

            $entregador = $params['arreglo'];
            $countDesasignados = 0;

            foreach ($entregador as $elemento) {

                $prestamoUpdate = Prestamo::query()->find($elemento['IdPrestamo']);

                if (!empty($prestamoUpdate)) {
                    $prestamoUpdate->Estatus        = "PreAutorizado";

                    if (!$prestamoUpdate->save()) return response([
                        "status" => false,
                        "message" => 'Prestamo no Autorizado.'
                    ], 500);

                   EntregadoresxPrestamos::query()
                        ->where('IdUsuario', '=', $params['IdUsuario'])
                        ->where('IdPrestamo', '=', $elemento['IdPrestamo'])
                        ->delete();

                    $countDesasignados++;
                }
            }

                return response([
                    "status" 	=> true,
                    "message"   => "Success",
                    "data"		=> $countDesasignados,
                ], 201);


		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location);
		}
	}

	/*
     * API PARA LISTADO DE TODOS LOS PRESTAMOS PRE-AUTORIZADOS
     * METHOD: GET
	 * COMPONER O ELIMINAR API CON REPETIDA EN FUNCION
     */

	public function GetPreAutorizados(Request $request){
		 try {

            $search = '';
            $multiWhere = array();


            if ( !empty($request->get('Estatus')) ) {
                $multiWhere[] = array("clientes.Estatus", "=", $request->get('Estatus'));
            }

            if ( !empty($request->get('EstatusPrestamo')) ) {
                $multiWhere[] = array("prestamos.Estatus", "=", $request->get('EstatusPrestamo'));

            }

            $prospectosPrestamos = Cliente::query()
                ->join('prestamos','clientes.IdCliente','=','prestamos.IdCliente')
                ->where($multiWhere)
                ->get(array('clientes.IdCliente','clientes.IdCliente','clientes.NombreCompleto','clientes.Telefono',
                        'clientes.Estatus','clientes.Prospecto','clientes.MotivoRechazo','clientes.FechaRechazo','clientes.Origen',
                        'prestamos.IdPrestamo','prestamos.IdCobratario','prestamos.MontoSolicitud','prestamos.PrestamoMotRechazo',
                        'prestamos.Estatus as EstatusP','prestamos.Folio','prestamos.created_at as FechaPrestamo','prestamos.FechaRechazo as FRechazoPrestamo')
                );

            return response([
                "status" 	=> true,
                "message"	=> "Prospectos encontrados.",
                "data"		=> $prospectosPrestamos,
                "rutaFoto"  => URL::to('/') . Storage::url("imgclientes/"),

            ], 200);
        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception, $this->location,'findProspectosPrestamos');
        }
	}

	/*
     * API PARA LISTADO DE TODOS LOS CLIENTES POR ASIGNAR
     * METHOD: GET
     */
	public function findAllEntregadoresxPrestamo(Request $request)
	{
		try {
			$IdPrestamo = $request->get('IdPrestamo');

			$entregadoresxprestamos = DB::table('entregadoresxprestamos')
			->join('users', 'users.IdUsuario', '=', 'entregadoresxprestamos.IdUsuario')
			->select(
				'entregadoresxprestamos.Identregadoresxprestamos',
				'users.NombreCompleto',
				'users.IdUsuario'
			)
			->where('entregadoresxprestamos.IdPrestamo', '=', $IdPrestamo)
			->get();

			return response([
				"status"    => true,
				"message"   => "success",
				"data"      => $entregadoresxprestamos,
			]);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LISTADO DE TODOS LOS CLIENTES ASIGNADOS
     * METHOD: GET
     */

    public function getClientesPrestamosAsignados(Request $request){
        try {

            $multiWhere = array();

            if (!empty($request->get('TxtBusqueda'))) {
                $multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
            }


            if ( !empty($request->get('EstatusPrestamo')) ) {
                $multiWhere[] = array("prestamos.Estatus", "=", $request->get('EstatusPrestamo'));

            }

            if (!empty($request->get('IdUsuario'))) {
                $multiWhere[] = array("entregadoresxprestamos.IdUsuario", '=', $request->get('IdUsuario'));
            }



            if(!empty($request->get('isSimple'))){
                $PreAutorizado = Cliente::query()
                    ->join('prestamos', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
                    ->join('entregadoresxprestamos', 'entregadoresxprestamos.IdPrestamo', '=', 'prestamos.IdPrestamo')
                    ->join('users', 'users.IdUsuario', '=', 'entregadoresxprestamos.IdUsuario')
                    //->where('prestamos.Estatus','=','Asignado')
                    ->where("clientes.Estatus", "=", "Activo")
                    ->where($multiWhere)
                    ->get(array('clientes.IdCliente',
                            'clientes.NombreCompleto',
                            'clientes.Telefono',
                            'clientes.Estatus',
                            'clientes.Origen',
                            'prestamos.IdPrestamo',
                            'prestamos.IdCliente',
                            'prestamos.MontoSolicitud',
                            'prestamos.Estatus as EstatusP',
                            'prestamos.Folio',
                            'prestamos.created_at as FechaPrestamo',
                            'users.NombreCompleto as NomEntregador',
                            'entregadoresxprestamos.IdUsuario'
                        )
                    );

            }else {
                $PreAutorizado = Cliente::query()
                    ->join('prestamos', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
                    ->join('entregadoresxprestamos', 'entregadoresxprestamos.IdPrestamo', '=', 'prestamos.IdPrestamo')
                    ->join('users', 'users.IdUsuario', '=', 'entregadoresxprestamos.IdUsuario')
                    //->where('prestamos.Estatus','=','Asignado')
                    ->where("clientes.Estatus", "=", "Activo")
                    ->where($multiWhere)
                    ->paginate(
                        $request->query("Entrada"),
                        array('clientes.IdCliente',
                            'clientes.NombreCompleto',
                            'clientes.Telefono',
                            'clientes.Estatus',
                            'clientes.Origen',
                            'prestamos.IdPrestamo',
                            'prestamos.IdCliente',
                            'prestamos.MontoSolicitud',
                            'prestamos.Estatus as EstatusP',
                            'prestamos.Folio',
                            'prestamos.created_at as FechaPrestamo',
                            'users.NombreCompleto as NomEntregador',
                            'entregadoresxprestamos.IdUsuario'
                        ),
                        "page",
                        $request->query("Pag")
                    );
            }




            return response([
                "status"    => true,
                "message"   => "success",
                "data"      => $PreAutorizado,
            ]);
        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception, $this->location,'GetAsignados');
        }
    }


	/*
     * API PARA LISTADO DE TODOS LOS CLIENTES ACTIVOS
     * METHOD: GET
     */

	public function GetClienteActivo(Request $request){
		try {
			$array=array();
			$multiWhere = array();
			if (!empty($request->get('TxtBusqueda'))) {
				$multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
			}

			$clienteFindAll =  Cliente::query()
                ->where('clientes.Estatus','=','Activo')
			    ->where($multiWhere)
			    ->paginate(
					$request->query("Entrada"),
					array('clientes.IdCliente',
                        'clientes.NombreCompleto',
                        'clientes.Telefono',
                        'clientes.Estatus',
                        'clientes.created_at'
                    ),
					"page",
					$request->query("Pag")
				);

				$data['cliente'] = $clienteFindAll;

			return response([
				"status" 	=> true,
				"message"	=> "Clientes encontrados.",
				"data"		=> $data,
				"rutaFoto"  => URL::to('/') . Storage::url("imgclientes/"),

			], 200);
		}  catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location);
		}
	}

    /*
     * API PARA LISTADO DE TODOS LOS CLIENTES RECAHZADOS
     * METHOD: GET
     */
    /*public function getRechazados(Request $request){
        try {
            $array=array();
            $multiWhere = array();
            if (!empty($request->get('TxtBusqueda'))) {
                $multiWhere[] = array("clientes.NombreCompleto", "like", "%".  $request->get('TxtBusqueda')."%");
            }

            $clienteFindAll =  DB::table('clientes')
            ->join('prestamos', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
            ->select(
                'clientes.IdCliente',
                'clientes.NombreCompleto',
                'clientes.Telefono',
                'prestamos.Estatus',
                'prestamos.MontoSolicitud',
                'prestamos.IdPrestamo',
                'prestamos.PrestamoMotRechazo'

            )
            ->where('clientes.Estatus','=','Activo')
            ->where('prestamos.Estatus','=','Rechazado')
            ->where($multiWhere)
            ->paginate(
                    $request->query("Entrada"),
                    "*",
                    "page",
                    $request->query("Pag")
                );

                $data['cliente'] = $clienteFindAll;

            return response([
                "status" 	=> true,
                "message"	=> "Clientes encontrados.",
                "data"		=> $data,
                "rutaFoto"  => URL::to('/') . Storage::url("imgclientes/"),

            ], 200);
        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception, $this->location);
        }
    }*/

    /*public function getAsignadosxUsu(Request $request){
       try {

           $multiWhere     = array();
           $multiWhere[]   = array("entregadoresxprestamos.IdUsuario", '=', $request->get('IdUsuario'));

           $PreAutorizado = DB::table('clientes')
           ->join('prestamos', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
           ->join('entregadoresxprestamos', 'entregadoresxprestamos.IdPrestamo', '=', 'prestamos.IdPrestamo')
           ->join('users', 'users.IdUsuario', '=', 'entregadoresxprestamos.IdUsuario')
           ->select(
               'clientes.IdCliente',
               'clientes.NombreCompleto',
               'clientes.Telefono',
               'prestamos.IdPrestamo',
               'prestamos.IdCliente',
               'prestamos.Estatus',
               'prestamos.MontoSolicitud',
               'users.Nombre',
               'entregadoresxprestamos.IdUsuario'

           )
           ->where('prestamos.Estatus','=','Asignado')
           ->where($multiWhere)
           ->paginate(
                   $request->query("Entrada"),
                   "*",
                   "page",
                   $request->query("Pag")
               );



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
     ********************** FUNCIONES DE VALIDACIÓN *********************************
     *
     */
	public function addClienteValidator($params = [], $IdCliente = 0)
	{
		$required = [
			"Nombre"   			=> "required|string",
			"ApellidoPat"       => "required|string",
			"ApellidoMat"    	=> "required|string",
			"FechaNacimiento"	=> "required|string",
			//"Correo"    		=> "required|string",
			"Telefono"    		=> "required|numeric",
			"DescripcionEmpresa"=> "required|string",
			"Calle"    			=> "required|string",
			"NoExt"    			=> "required|string",
			"Colonia"       	=> "required|string",
			"Cp"    			=> "required|numeric",
			"IdEstado"    		=> "required|numeric|min:1",
			"IdMunicipio"    	=> "required|numeric|min:1",
			"Latitud"    		=> "required|numeric",
			"Longitud"    		=> "required|numeric",
			// "Prospecto"    		=> "required|string",
			// "Estatus"    		=> "required|string",
		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'min'       => 'Debe seleccionar una opción.',
			// 'same'      => 'Las contraseñas no coinciden.',
			// 'unique'    => 'Este nombre de usuario ya ha sido utilizado en otra cuenta.',
			'numeric'	=> 'El campo debe ser númerico',
			'string'    => 'El campo es requerido.',
		]);
	}

	public function addPrestamoValidator($params = [])
	{
		$required = [
			"IdCliente" 		=> "required|numeric",
			"IdRuta" 			=> "required|numeric|min:1",
			// "IdMontoSolicitud"	=> "required|numeric|min:1",
		];

		if($params['Origen'] !== 'app'){
			$required+= ["IdSucursal" => "required|numeric|min:1"];
			$required+= ["IdCobratario" => "required|numeric|min:1"];
		}

        return validator($params, $required, [
            'required'  => 'El campo es requerido.',
            'min'       => 'Debe seleccionar una opción.',
            'numeric'	=> 'El campo es requerido',
            'string'    => 'El campo es requerido.',
        ]);
    }

    public function MotivoRechazoValidador($params = [])
    {
        $required = [
            "MotivoRechazo"  => "required",
        ];

        return validator($params, $required, [
            'required'  => 'El campo es requerido.'
        ]);
    }

    public function IdUsuarioValidador($params = [])
    {
        $required = [
            "IdUsuario"  => "required|numeric|min:1",
            "arreglo" 	 => "min:1",
        ];

        return validator($params, $required, [
            'required'	=> 'El campo es requerido.',
            'numeric'	=> 'El campo es requerido.',
            'arreglo.min'	    => 'Es necesario seleccionar un elemento como minimo',
            'IdUsuario.min'	    => 'El campo es requerido.',
        ]);
    }

    public function clienteEtapas($params = [])
    {
        $required = [
            "IdPrestamo" => "required|numeric|min:1",
            "IdCliente"  => "required|numeric|min:1",
            "Operacion"  => "required",
        ];

        return validator($params, $required, [
            'required'	=> 'El campo es requerido.',
            'numeric'	=> 'El campo es requerido.',
            'min'	    => 'El Id sebe ser un numero valido',
        ]);
    }
}
