<?php

namespace App\Http\Controllers;

use App\Custom\BitacoraManager;
use App\Custom\FilesManager;
use App\Custom\Funciones;
use App\Custom\ResponseManager;
use App\Custom\MailManager;
use App\Mail\RecuperarPasswordMailable;
use App\Models\RestablecerPassword;
use App\Models\Empleado;
use App\Models\EmpDatosFam;
use App\Models\EmpleadosRefPersonales;
use App\Models\EmpleadosEvidencias;
use App\Models\EquipoxUsuario;
use App\Models\Equipo;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller {
	private $fileLocation = 'UserController';
	private $RutaFile     = 'imgusers';
	private $RutaFoto     = 'imgempleados';
	private $PdfEvidencia = 'pdfevidencia';
	private $pdfhuella 	  = 'pdfhuella';

	/************************************** Funciones CRUD ***********************************/
	/*
     * API PARA LISTADO DE TODOS LOS USUARIOS DEL SISTEMA
     * METHOD: GET
     */
	public function findAll(Request $req) {
		try {
			#BUSQUEDA DE CONINCIDENCIA
			$search = '';
			if (!empty($req->get('TxtBusqueda'))) {
				$search = $req->get('TxtBusqueda');
			}

			$multiWhere = array();


			if (!empty($req->get('IdEmpleado'))) {
				$multiWhere[] = array("IdEmpleado", '=', $req->get('IdEmpleado'));
			}

			if (!empty($req->get('IdPerfil'))) {
				$multiWhere[] = array("IdPerfil", '=', $req->get('IdPerfil'));
			}

			if (!empty($req->get('IdPuesto'))) {
				$multiWhere[] = array("IdPuesto", '=', $req->get('IdPuesto'));
			}

			if (!empty($req->get('onlyUsers'))) {
				$multiWhere[] = array("IdCliente", '=', '0');
			}

			$users = User::query()->where(function ($query) use ($search) {
				$query->where('Nombre', 'like', '%' . $search . '%');
				//->orWhere('NoEmpleado', 'like', '%' . $search . '%');

			})->where($multiWhere)
                ->whereNotIn('IdUsuario', [1,2]) // Retira el Root por defecto
                ->whereNotIn('IdEmpleado',[0]) // Retira a los usuarios de tipo admin1 de cada empresa
                ->paginate(
					$req->query("Entrada"),
					"*",
					"page",
					$req->query("Pag")
				);

			$data['usuarios'] = $users;

            return response([
                "status"   => true,
                "message"  => 'success',
                "data"     => $data,
                "RutaFile" => URL::to('/').Storage::url($this->RutaFile),

            ],200);

        }catch (Exception $exception){
            return ResponseManager::setErrorServerResponse($exception,$this->fileLocation,'findAll');
        }
    }

	/*
     * API PARA LA BUSQUEDA DE USUARIOS ESPECIFICOS DEL SISTEMA
     * METHOD: GET
     */
	public function findOne($id) {
		try {
			$user = User::find($id);

			if (!empty($user)) {
				$user->UrlImg = ($user->UrlImg == '') ? 'picture.png' : $user->UrlImg;

                return response([
                    "status"    => true,
                    "message"   => "success",
                    "data"      => $user,
                    "RutaFile"  => URL::to('/').Storage::url($this->RutaFile.'/'), #http://127.0.0.1:8000/storage/imgprofileuser/
                ]);

            }else {
                return response([
                    "status"    => false,
                    "message"   => 'Registro no encontrado',
                ],404);
            }

        }catch (Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

	/*
     * API PARA AGREGAR USUARIOS AL SISTEMA
     * METHOD: POST
     */
    public function add(Request $request) {
        try {
			$creador = JWTAuth::authenticate();

            $params = $request->only(
                "IdPerfil",                 "Rfc",                  "FechaFiniquito",			"Nacionalidad",
                "IdEstado",                 "NoInt",                "Nombre",					"IdPuesto",
                "IdMunicipio",              "NoExt",                "Correo",					"Genero",
                "ApellidoPat",              "Cp",                  	"DatosFam", 				"Ref",
                "ApellidoMat",				"IdUsuario",			"UsuarioApp",               "Finiquito",
                "Telefono",                 "EstadoCivil",         	"password_confirmation",    "FechaEntrega",
                "username",                 "FechaAlta",            "password",                 "FechaBaja",
				"arrayHerramientas"
            );
			/*
				"IdEmpdatosFam",
				"Referencias",
				"NombreFam",
				"TelefonoFam",
				"CalleFam",
				"NoIntFam",
				"NoExtFam",
				"NombreRef",
				"Anio",
			*/
            $validation = $this->addUserValidator($params,0);

            if($validation->fails()) return response([
                "message"   => "parametros invalidos",
                "errors"    => $validation->errors()
            ],400);

			// VALIDAMOS EL USERNAME DEL USUARIO
			$valido = true;
			$user   = User::where('username','=',$params['username'])->first();

			if($params['IdUsuario'] == 0) {
				// SI EXISTE EL NOMBRE DE USUARIO NO LO DEJAMOS CONTINUAR
				if(!empty($user)){
					$valido = false;
				}
			} else {
				// SI EXISTE EL NOMBRE DE USUARIO Y LOS ID DE USUARIO SON DISTINTOS NO LO DEJA CONTINUAR
				if(!empty($user)){
					if($user->IdUsuario!=$params['IdUsuario']){
						$valido = false;
					}
				}
			}

			if(!$valido) {
				return response([
					"status"  => false,
					"message" => 'El username ya se encuentra registrado en el sistema',
				],500);
			}

			$usuario = User::find($params['IdUsuario']);

			// SI ENCUENTRA EL USUARIO ACTUALIZA LOS DATOS DEL USUARIO
			if(!empty($usuario)) {
				if ($request->hasFile('Imagen')) {
                    $Archivo         = FilesManager::UploadFileStorage($request, '/'.$this->RutaFoto,'Imagen');
                    $usuario->Imagen = $Archivo['status'] ? $Archivo['OrigiName'] : '';
                    $usuario->UrlImg = $Archivo['status'] ? $Archivo['HashName'] : '';
                }

				$IdUsuario			  = $usuario->IdUsuario;
				$IdEmpleado 		  = $usuario->IdEmpleado;
				$usuario->IdPerfil	  = $params['IdPerfil'];
				$usuario->IdPuesto	  = $params['IdPuesto'];
				$usuario->IdEstado	  = $params['IdEstado'];
				$usuario->IdMunicipio = $params['IdMunicipio'];
				$usuario->Nombre	  = $params['Nombre'];
				$usuario->ApellidoPat = $params['ApellidoPat'];
				$usuario->ApellidoMat = $params['ApellidoMat'];
				$usuario->username    = $params['username'];
				$usuario->Correo	  = (empty($params['Correo']) ? '' : $params['Correo']);
				$usuario->Telefono	  = (empty($params['Telefono']) ? '' : $params['Telefono']);
				$usuario->UsuarioApp  = $params['UsuarioApp'];
				// $usuario->Confirmado      = $params['Confirmado'];

				$usuario->NombreCompleto = $params['Nombre'].' '.$params['ApellidoPat'].' '.$params['ApellidoMat'];

				// MANDAMOS A ACTUALIZAR LOS DATOS DEL EMPLEADO
				$Empleado = $this->updateEmpleado($IdEmpleado,$request);

				if(empty($Empleado)) {
					return response([
						"status"  => false,
						"message" => 'No se pudo actualizar el registro',
					], 500);
				}

				// MANDAMOS ELIMINAR LOS DATOS FAMILIARES Y DESPUES A INSERTARLOS DE NUEVO
				$DatosFam 		   = EmpDatosFam::where('IdEmpleado','=',$IdEmpleado)->delete();
				$DatosFamiliares   = json_decode($params['DatosFam'], true);
				$DatosFamArray 	   = array();
				$EmpleadosDatosFam = '';

				// RECIBIMOS LOS DATOS DE LOS FAMILIARES Y LOS PREPARAMOS PARA INSERTAR
				foreach ($DatosFamiliares as $element) {
					$DatosFamArray = array(
						'TipoDeDato'   	=>  $element['TipoDeDato'],
						'NombreFam' 	=>  $element['NombreFam'],
						'TelefonoFam' 	=>  $element['TelefonoFam'],
						'CalleFam' 		=>  $element['CalleFam'],
						'NoIntFam' 		=>  $element['NoIntFam'],
						'NoExtFam' 		=>  $element['NoExtFam'],
						'IdEmpleado'  	=>  $IdEmpleado,
					);

					$EmpleadosDatosFam = new EmpDatosFam();
					if(!$EmpleadosDatosFam->insert($DatosFamArray))return response([
						"status"  => false,
						"message" => 'No se pudo insertar el registro',
					],500);
				}

				// MANDAMOS ELIMINAR LOS DATOS FAMILIARES Y DESPUES A INSERTARLOS DE NUEVO
				$DatosRef 		   = EmpleadosRefPersonales::where('IdEmpleado','=',$IdEmpleado)->delete();
				$DatosRefFam 	   = json_decode($params['Ref'], true);
				$Referencias 	   = array();
				$EmpleadosDatosRef = '';

				// RECIBIMOS LOS DATOS DE LAS REFERENCIAS Y LOS PREPARAMOS PARA INSERTAR
				foreach($DatosRefFam as $element) {
					$Referencias = array(
						'IdEmpleado'  =>  $IdEmpleado,
						'NombreRef'   =>  $element['NombreRef'],
						'TelefonoRef' =>  $element['TelefonoRef']
					);

					$EmpleadosDatosRef = new EmpleadosRefPersonales();
					if(!$EmpleadosDatosRef->insert($Referencias))return response([
						"status"  => false,
						"message" => 'No se pudo insertar el registro',
					],500);
				}

				// EMPLEADOS EVIDENCIAS
				// OBTENEMOS EL ID DE LA EVIDENCIA EN BASE AL ID DEL EMEPLEADO
				$EmpleadoEvid = EmpleadosEvidencias::where('IdEmpleado','=',$IdEmpleado)->first();
				$Evidencia 	  = '';
				$Huella    	  = '';

				if ($request->hasFile('Evidencia')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->PdfEvidencia,'Evidencia');
                    $Evidencia = $Archivo['status'] ? $Archivo['HashName'] : '';
                }

				if ($request->hasFile('Huella')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->pdfhuella,'Huella');
                    $Huella = $Archivo['status'] ? $Archivo['HashName'] : '';
                }

				if(!empty($EmpleadoEvid)) {

					if($Evidencia!='' || $Huella!=''){
						$addEmpEvidencia = EmpleadosEvidencias::find($EmpleadoEvid->IdEmpleadoEvidencia);
						$addEmpEvidencia->IdEmpleadoEvidencia = $EmpleadoEvid->IdEmpleadoEvidencia;

						if($Huella!=''){
							$addEmpEvidencia->Huella = $Huella;
						}
						if($Evidencia!=''){
							$addEmpEvidencia->Evidencia = $Evidencia;
						}

						//$addEmpEvidencia = new EmpleadosEvidencias($params);

						if(!$addEmpEvidencia->save())return response([
							"status"  => false,
							"message" => 'No se pudo insertar el registro',
						],500);
					}

				} else {
					$params['Anio']  		= date('Y');
					$params['Huella'] 		= $Huella;
					$params['Evidencia'] 	= $Evidencia;
					$params['IdEmpleado']	= $IdEmpleado;
					$addEmpEvidencia = new EmpleadosEvidencias($params);

					if(!$addEmpEvidencia->save())return response([
						"status"  => false,
						"message" => 'No se pudo insertar el registro',
					],500);
				}

                // DAMOS DE ALTA LAS HERRAMIENTAS DE TRABAJO DEL EMPLEADO
				$Herramientas = $this->addHerramientas($IdUsuario,$params,$creador,$usuario);

				if (!$Herramientas) return response([
					"status" 	=> false,
					"message"   => "Herramientas no guardadas."
				], 500);

			} else {
				#FILE_MANEGER
				if ($request->hasFile('Imagen')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->RutaFoto,'Imagen');
                    $params['Imagen'] = $Archivo['status'] ? $Archivo['OrigiName'] : '';
                    $params['UrlImg'] = $Archivo['status'] ? $Archivo['HashName'] : '';
                } else {
                    $params['Imagen'] = '';
                    $params['UrlImg'] = '';
                }

				// SE CREA EL EMPLEADO PARA DESPUES INSERTAR AL USUARIO
				$Empleado = $this->addEmpleado($request);

				if(empty($Empleado)) {
					return response([
						"status"  => false,
						"message" => 'No se pudo insertar el registro',
					], 500);
				}

				$IdEmpleado = $Empleado->IdEmpleado;

				// RESETEA VALORES POR DEFECTO PARA EL USUARIO
				$params["password"]       = Hash::make($params['password']);
				$params['NombreCompleto'] = $params['Nombre'].' '.$params['ApellidoPat'].' '.$params['ApellidoMat'];
				$params['Confirmado']     = false;
				$params['IdPais']         = 1;
				$params['Disponible']     = true;
				$params['IdSucursal']     = $creador->IdSucursal;
				$params['IdEmpresa']	  = $creador->IdEmpresa;
				$params['IdEmpleado']     = ($IdEmpleado>0)?$IdEmpleado : 0;

				// GENERAMOS LA INSTANCIA PARA INSERTAR AL USUARIO
				$usuario   = new User($params);

				// RECIBIMOS LOS DATOS DE LOS FAMILIARES Y LOS PREPARAMOS PARA INSERTAR
				$DatosFamiliares   = json_decode($params['DatosFam'], true);
				$DatosFamArray     = array();
				$EmpleadosDatosFam = '';

				foreach($DatosFamiliares as $element)
				{
					$DatosFamArray = array(
						'TipoDeDato'   	=>  $element['TipoDeDato'],
						'NombreFam' 	=>  $element['NombreFam'],
						'TelefonoFam' 	=>  $element['TelefonoFam'],
						'CalleFam' 		=>  $element['CalleFam'],
						'NoIntFam' 		=>  $element['NoIntFam'],
						'NoExtFam' 		=>  $element['NoExtFam'],
						'IdEmpleado'  	=>  $IdEmpleado,
					);

					$EmpleadosDatosFam = new EmpDatosFam();
					if(!$EmpleadosDatosFam->insert($DatosFamArray))return response([
						"status"  => false,
						"message" => 'No se pudo insertar el registro',
					],500);
				}

				// RECIBIMOS LOS DATOS DE LAS REFERENCIAS Y LOS PREPARAMOS PARA INSERTAR
				$DatosRefFam       = json_decode($params['Ref'], true);
				$Referencias       = array();
				$EmpleadosDatosRef = '';

				foreach($DatosRefFam as $element)
				{
					$Referencias = array(
						'IdEmpleado'  =>  $IdEmpleado,
						'NombreRef'   =>  $element['NombreRef'],
						'TelefonoRef' =>  $element['TelefonoRef']
					);

					$EmpleadosDatosRef = new EmpleadosRefPersonales();
					if(!$EmpleadosDatosRef->insert($Referencias))return response([
						"status"  => false,
						"message" => 'No se pudo insertar el registro',
					],500);
				}

				// EMPLEADOS EVIDENCIAS
				$Evidencia = '';
				$Huella    = '';

				if ($request->hasFile('Evidencia')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->PdfEvidencia,'Evidencia');
                    $Evidencia = $Archivo['status'] ? $Archivo['HashName'] : '';
                }

				if ($request->hasFile('Huella')) {
                    $Archivo = FilesManager::UploadFileStorage($request, '/'.$this->pdfhuella,'Huella');
                    $Huella = $Archivo['status'] ? $Archivo['HashName'] : '';
                }

				$params['Anio']  	  = date('Y');
				$params['Huella'] 	  = $Huella;
				$params['Evidencia']  = $Evidencia;
				$params['IdEmpleado'] = $IdEmpleado;
				$addEmpEvidencia 	  = new EmpleadosEvidencias($params);

				if(!$addEmpEvidencia->save())return response([
					"status"  => false,
					"message" => 'No se pudo insertar el registro',
				],500);

			}

            if(!$usuario->save()) return response([
                "status"    => false,
                "message"   => 'Usuario no creado',
            ],500);

			$IdUsuario = $usuario->IdUsuario;

			// DAMOS DE ALTA LAS HERRAMIENTAS DE TRABAJO DEL EMPLEADO
			$Herramientas = $this->addHerramientas($IdUsuario,$params,$creador,$usuario);

			if (!$Herramientas) return response([
				"status" 	=> false,
				"message"   => "Herramientas no guardadas."
			], 500);


            $data['usuario']     = $usuario;
            $data['empleado']    = $Empleado;
            $data['empdatosfam'] = $EmpleadosDatosFam;
            $data['emprefperso'] = $EmpleadosDatosRef;

            return response([
                "status"    => true,
                "message"   => "success",
                "data"      => $data
            ],201);
        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

	/*
     * API PARA MODIFICAR INFORMACIÓN DE UN USUARIO ESPECIFICO DEL SISTEMA
     * METHOD: POST
	*/
	public function update(Request $request) {
		try {
			$params = $request->only(
				"IdUsuario",
				"IdPerfil",
				"IdPuesto",
				"IdEstado",
				"IdMunicipio",
				"Nombre",
				"ApellidoPat",
				"ApellidoMat",
				"Correo",
				"Telefono",
				"UsuarioApp",

				"Rfc",
				"Calle",
				"NoInt",
				"NoExt",
				"Colonia",
				"Cp",
				"Referencias",
			);

			$validation = $this->addUserValidator($params, $params['IdUsuario']);

			if ($validation->fails()) return response([
				"message" => "parametros invalidos",
				"errors" => $validation->errors()
			], 400);

			$Usuario = User::find($params['IdUsuario']);

			if (!empty($Usuario)) {
				$idEmpleado =  $Usuario->IdEmpleado;

				$Usuario->IdPerfil    = $params['IdPerfil'];
				$Usuario->IdPuesto    = $params['IdPuesto'];
				$Usuario->IdEstado    = $params['IdEstado'];
				$Usuario->IdMunicipio = $params['IdMunicipio'];
				$Usuario->Nombre      = $params['Nombre'];
				$Usuario->ApellidoPat = $params['ApellidoPat'];
				$Usuario->ApellidoMat = $params['ApellidoMat'];
				$Usuario->Correo      = (empty($params['Correo']) ? '' : $params['Correo']);
				$Usuario->Telefono    = (empty($params['Telefono']) ? '' : $params['Telefono']);
				$Usuario->UsuarioApp  = $params['UsuarioApp'];
				$Usuario->Confirmado  = $params['Confirmado'];

				#FILE_MANEGER
				if ($request->hasFile('Imagen')) {
					$Archivo = FilesManager::UploadFilePublic($request, $this->RutaFile, 'Imagen', $params['FilePrevious'], 'picture.png');
					$Usuario->Imagen = $Archivo['status'] ? $Archivo['OrigiName'] : '';
					$Usuario->UrlImg = $Archivo['status'] ? $Archivo['HashName'] : '';
				}

				#SETER DATA DEFAULT
				$Usuario->NombreCompleto = $params['Nombre'] . ' ' . $params['ApellidoPat'] . ' ' . $params['ApellidoMat'];

				if (!$Usuario->save()) return response([
					"status" => false,
					"message" => "update_error"
				], 500);

				$Empleado = Empleado::find($idEmpleado);
				if (!empty($Empleado)) {
					$Empleado->Rfc         = $params['Rfc'];
					$Empleado->Calle       = $params['Calle'];
					$Empleado->NoInt       = $params['NoInt'];
					$Empleado->NoExt       = $params['NoExt'];
					$Empleado->Colonia     = $params['Colonia'];
					$Empleado->Cp          = $params['Cp'];
					$Empleado->Referencias = $params['Referencias'];
					if (!$Empleado->save()) return response([
						"status"  => false,
						"message" => "update_error"
					], 500);
				}

				return response([
					"status"   => true,
					"message"  => "success",
					"data"     => $Usuario,
					"RutaFile" => URL::to('/').Storage::url($this->RutaFile.'/'),
				], 200);
			} else {
				return response([
					"status"  => false,
					"message" => "Usuario no encontrado",
					"data"    => ''
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception);
		}
	}

	/*
     * API PARA ELIMINAR UN USUARIO ESPECIFICO DEL SISTEMA
     * METHOD: DELETE
     */
	public function delete($id) {
		try {
			$user = User::find($id);

			if (!empty($user)) {

				if (!in_array($user->IdUsuario, [1, 2])) {

					if (Empleado::find($user->IdEmpleado)->delete()) {

						if ($user->delete()) {
							return response([
								"status" => true,
								"message" => 'Registro Eliminado'
							], 202);
						} else {
							return response([
								"status" => false,
								"message" => 'delete_error',
							], 500);
						}
					} else {
						return response([
							"status" => false,
							"message" => 'delete_error',
						], 500);
					}
				} else {
					return response([
						"status" => false,
						"message" => 'Operacion denegada.',
					], 403);
				}
			} else {
				return response([
					"status" => false,
					"message" => 'Registro no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception);
		}
	}

	/************************************** Funciones ESPECIALES *********************************/

	private function addEmpleado(Request $request) {
		$params = $request->only(
			"Rfc",
			"Calle",
			"NoInt",
			"NoExt", // SON LOS CRUZAMIENTOS
			"Colonia",
			"Cp",
			"Referencias",
			"FechaNacimiento",
			"EstadoCivil",
			"FechaAlta",
			"FechaBaja",
			// "FechaEntrega",
			"Finiquito",
			"FechaFiniquito",
			"Genero",
			"Nacionalidad"
		);

		$params["FechaAlta"]		= date('Y-m-d', strtotime($params["FechaAlta"]));
		/*
		$params['FechaBaja']      	= date('Y-m-d');
		$params['FechaFiniquito']	= date('Y-m-d');
		*/

		$addEmpleado = new Empleado($params);

		if (!$addEmpleado->save()){
			return '';
		}

		return $addEmpleado;
	}

	private function updateEmpleado(int $IdEmpleado, Request $request){

		$params = $request->only(
			'Calle',
			'NoInt',
			'NoExt',
			'Cp',
			'FechaNacimiento',
			'EstadoCivil',
			'FechaAlta',
			'FechaBaja',
			// 'FechaEntrega',
			'Finiquito',
			'FechaFiniquito',
			"Genero",
			'Nacionalidad'
		);

		$empleadoUpdate = Empleado::find($IdEmpleado);

		if (!empty($empleadoUpdate))
		{
			$empleadoUpdate->Calle          = $params['Calle'];
			$empleadoUpdate->NoInt          = $params['NoInt'];
			$empleadoUpdate->NoExt          = $params['NoExt'];
			$empleadoUpdate->Cp             = $params['Cp'];
			$empleadoUpdate->FechaNacimiento= $params['FechaNacimiento'];
			$empleadoUpdate->EstadoCivil    = $params['EstadoCivil'];
			$empleadoUpdate->FechaAlta		= date('Y-m-d', strtotime($params["FechaAlta"]));
			/*$empleadoUpdate->FechaBaja      = date('Y-m-d');
			$empleadoUpdate->Finiquito      = $params['Finiquito'];
			$empleadoUpdate->FechaFiniquito = date('Y-m-d'); */
			$empleadoUpdate->Genero			= $params['Genero'];
			$empleadoUpdate->Nacionalidad 	= $params['Nacionalidad'];

			if (!$empleadoUpdate->save()){
				return '';
			}
			else
			{
				return $empleadoUpdate;
			}
		}
    }

	public function getUserEmpleado($id) {
		try {
			// USUARIOS Y EMPLEADOS
			$userEmpleado = User::
			join('empleados', 'empleados.IdEmpleado', '=', 'users.IdEmpleado')
			// join('equiposxusuarios', 'equiposxusuarios.IdUsuario', '=', 'users.IdUsuario')
			->leftJoin('empleadosevidencias', 'empleadosevidencias.IdEmpleado', '=', 'users.IdEmpleado')
			->select(
				'users.IdUsuario',
				'users.IdEmpleado',
				'users.Nombre',
				'users.ApellidoPat',
				'users.ApellidoMat',
				'users.Correo',
				'users.username',
				'empleados.Calle',
				'users.Imagen',
				'users.UrlImg',
				'users.Telefono',
				'empleados.Cp',
				'empleados.NoInt',
				'empleados.NoExt',
				'empleados.FechaNacimiento',
				'empleados.EstadoCivil',
				'empleados.FechaAlta',
				'users.IdPerfil',
				'users.IdPuesto',
				'users.IdEstado',
				'users.IdMunicipio',
				'users.UsuarioApp',
				'empleados.Nacionalidad',
				'empleados.Genero',
				'empleados.EstadoCivil',
				'empleadosevidencias.Evidencia',
				'empleadosevidencias.Huella'
			)
			->where('users.IdEmpleado', '=', $id)
			->first();

			// LOS DATOS DE LOS FAMILIARES DE LOS EMPLEADOS
			$empleadoFam = EmpDatosFam::where('IdEmpleado', '=', $id)->get();

			// LOS DATOS DE LAS REFERENCIAS DE LOS EMPLEADOS
			$empleadoRef = EmpleadosRefPersonales::where('IdEmpleado', '=', $id)->get();

			// LOS DATOS DE LAS HERRAMIENTAS DE LOS USUARIOS
			$usuarioHerramientas = EquipoxUsuario::where('IdUsuario', '=', $userEmpleado->IdUsuario)->get();

			return response([
				"status"     => true,
				"message"    => "success",
				"data"       => $userEmpleado,
				"datosFam" 	 => $empleadoFam,
				"datosRef"	 => $empleadoRef,
				"datosHer"	 => $usuarioHerramientas,
				"rutaFile"   => URL::to('/').Storage::url($this->RutaFoto.'/'),
				"rutaPDF"  	 => URL::to('/').Storage::url($this->pdfhuella.'/'),
				"rutaPDFevi" => URL::to('/').Storage::url($this->PdfEvidencia.'/'),
			]);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception);
		}
	}

	/*
     * API PARA LISTADO DE TODOS LOS PERFILES CON ID 3
     * METHOD: GET
     */

	public function getUserTipoPerfil(Request $request){
		try {
			//!PARA EL TIPO DE PERFIL DE VALIDADOR
            $multiWhere = array();
            $multiWhereIn = array();
            $multiWhere[] = array('users.IdEmpresa','=',1);

            if( !empty($request->get('IdPerfil')) ) {

                if(!empty($request->get('InIdPerfil'))) {

                    $PerfilGet = User::query()
                        ->join('perfiles','users.IdPerfil','=','perfiles.IdPerfil')
                        ->where($multiWhere)
                        ->whereIn('users.IdPerfil',$request->get('IdPerfil'))
                        ->get(
                            array('users.IdUsuario',
                                'users.IdPerfil',
                                'users.NombreCompleto',
                                'perfiles.Nombre as NomPerfil'
                            )
                        );

                }else {
                    $multiWhere[] = array('users.IdPerfil','=',$request->get('IdPerfil'));

                    $PerfilGet = User::query()
                        ->join('perfiles','users.IdPerfil','=','perfiles.IdPerfil')
                        ->where($multiWhere)
                        ->get(
                            array('users.IdUsuario',
                                'users.IdPerfil',
                                'users.NombreCompleto',
                                'perfiles.Nombre as NomPerfil'
                            )
                        );
                }

            }




			return response([
				"status"    => true,
				"message"   => "success",
				"data"      => $PerfilGet,
			]);

		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->fileLocation,'getUserTipoPerfil');
		}
	}

	public function addHerramientas($IdUsuario,$params,$creador,$usrEmpleado) {
		$herramientas = json_decode($params['arrayHerramientas']);

		foreach($herramientas as $elemento) {

			if($elemento->IdEquipoxUsuario > 0) {
				$EmpleadoHerr = EquipoxUsuario::find($elemento->IdEquipoxUsuario);


                ## GENERACION DE BITACORA DE TIPO ASIGNACION PARA EQUIPOS
                if($elemento->TipoHerramienta == 'Vehiculo' || $elemento->TipoHerramienta == 'Telefono') {


                    if($elemento->IdEquipo == 0){
                        $msg = 'Desasigno '.$elemento->Descripcion.' a '.$usrEmpleado->NombreCompleto;
                        BitacoraManager::creaBitacoraEquipo($creador,$elemento->IdEquipo,$usrEmpleado->IdUsuario,'A',$msg);
                    }

                    if($elemento->IdEquipo > 0 && $EmpleadoHerr->IdEquipo !== $elemento->IdEquipo) {
                        $msg1 = 'Desasigno '.$EmpleadoHerr->Descripcion.' por '.$elemento->Descripcion.' a '.$usrEmpleado->NombreCompleto;
                        BitacoraManager::creaBitacoraEquipo($creador,$EmpleadoHerr->IdEquipo,$usrEmpleado->IdUsuario,'A',$msg1); ## Equipo desasignado
                        BitacoraManager::creaBitacoraEquipo($creador,$elemento->IdEquipo,$usrEmpleado->IdUsuario,'A',$msg1); ## Equipo Asignado
                    }

                }

                $EmpleadoHerr->IdEquipo    = $elemento->IdEquipo;
                $EmpleadoHerr->Descripcion = $elemento->Descripcion;

				if($elemento->FechaEntrega!=''){
					$EmpleadoHerr->FechaEntrega = date('Y-m-d', strtotime($elemento->FechaEntrega));
				}
			} else {

				$newArray = array(
					'IdUsuario'  	  => $IdUsuario,
					'IdEquipo' 	 	  => $elemento->IdEquipo,
					'Descripcion'  	  => $elemento->Descripcion,
					'TipoHerramienta' => $elemento->TipoHerramienta,
				);

                ## GENERACION DE BITACORA DE TIPO ASIGNACION PARA EQUIPOS
                if($elemento->TipoHerramienta == 'Vehiculo' || $elemento->TipoHerramienta == 'Telefono') {
                    $msg = 'Asigno '.$elemento->Descripcion.' a '.$usrEmpleado->NombreCompleto;
                    BitacoraManager::creaBitacoraEquipo($creador,$elemento->IdEquipo,$usrEmpleado->IdUsuario,'A',$msg);
                }

				if($elemento->FechaEntrega!=''){
					$newArray['FechaEntrega'] = date('Y-m-d', strtotime($elemento->FechaEntrega));
				}

				$EmpleadoHerr = new EquipoxUsuario($newArray);
			}

			if ($elemento->IdEquipo > 0) {
				$Equipo = Equipo::find($elemento->IdEquipo);

				if(!empty($Equipo)) {

						$Equipo->IdEquipo = $elemento->IdEquipo;
						$Equipo->Asignado = 'SI';

					if (!$Equipo->save()){
						return false;
					}

				}

			}

			if (!$EmpleadoHerr->save()){
				return false;
			}
		}

		return true;
	}

	/************************************** FUNCIONES DE SEGURIDAD *********************************/

	public function login(Request $res) {

		try {
			$UserCredentials = array("username" => $res->post('Username'), "password" => $res->post('Password'));
            $Origin = !empty($res->post('Origen')) ? $res->post('Origen') : 'web';

            $valid = $this->checkTypeUser($UserCredentials['username'], $Origin);
            if($valid['status'])
            {
                try {

                    if (!$token = JWTAuth::attempt($UserCredentials)) {
                        return response([
                            "status"  => false,
                            "type"    => 9,
                            "message" => "Usuario o Contraseña Incorrectos"
                        ], 400);
                    }

                } catch (JWTException $e) {
                    return response([
                        "status"  => false,
                        "message" => 'could_not_create_token'
                    ], 500);
                }

                $user = User::query()
                    ->with('perfil')
                    // ->with('empleados')
                    ->where('username', $UserCredentials['username'])
                    ->first();

                if ($user == null) {
                    return response([
                        "status"  => false,
                        "type"    => 3,
                        "message" => "Usuario o Contraseña Incorrectos"
                    ], 400);
                }

                ## SETTER LIMPIEZA DE ACENTOS EN LOS NOMBRES
                $user->Nombre           = strtoupper( Funciones::quitarCaracteres($user->Nombre) );
                $user->ApellidoPat      = strtoupper( Funciones::quitarCaracteres($user->ApellidoPat) );
                $user->ApellidoMat      = strtoupper( Funciones::quitarCaracteres($user->ApellidoMat) );
                $user->NombreCompleto   = strtoupper( Funciones::quitarCaracteres($user->NombreCompleto) );

                return response([
                    "message"  => 'Authenticated',
                    "token"    => $token,
                    "usuario"  => $user,
                    "RutaFile" => URL::to('/').Storage::url($this->RutaFile.'/'),
                    "status"   => true,
                ], 200);

            }else {

                $message = '';

                switch ($valid['eCode']) {
                    case 1:
                        $message = 'Este usuario no tiene autorización de acceder';
                        break;
                    case 2:
                        $message = 'Su acceso esta restringido';
                        break;
                    case 3:
                        $message = 'Este usuario no existe';
                        break;
                }
                return response([
                    "status"  => false,
                    "type"    => 4,
                    "message" => $message
                ], 400);

            }

		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}

	}

    private function checkTypeUser($username = '', $origin = ''){
        try {
            $usr = User::query()->where('username',$username)
                ->first();

            if(!empty($usr)) {
                switch ($origin) {
                    case 'web':
                        if (in_array($usr->IdPerfil, [2, 3, 4])) {
                            return [
                                'status' => false,
                                'eCode'  => 1,
                            ];
                        } else {
                            return [
                                'status' => true,
                                'eCode'  => 0,
                            ];
                        }
                        break;

                    case 'app':
                        if (in_array($usr->IdPerfil, [2, 3, 4])) {
                            if($usr->UsuarioApp == 1){
                                return [
                                    'status' => true,
                                    'eCode'  => 0,
                                ];
                            }else {
                                return [
                                    'status' => false,
                                    'eCode'  => 2,
                                ];
                            }

                        } else {
                            return [
                                'status' => false,
                                'eCode'  => 1,
                            ];
                        }
                        break;
                }

            }else {
                return [
                    'status' => false,
                    'eCode'  => 3,
                ];
            }

        }catch (Exception $e){
            ResponseManager::createLog($e,'UserController/checkTypeUser','LOGIN-TYPE');
            return false;
        }
    }

	public function changePassword(Request $request) {
		try {
			// RECIBIMOS LOS DATOS DEL USUARIO QUE ENVIA LA PETICIÓN
			$usr = JWTAuth::authenticate();

			if (!empty($usr)) {

				$data 		= $request->only('Old','New','ConfirmNew',);
				$validation = $this->changePaswordValidator($data);

				if ($validation->fails()) return response([
					'typemsj' => 2,
					"message" => "parametros invalidos",
					"errors"  => $validation->errors()
				], 400);

				if ($this->checkPass($request->get('Old'), $usr->password)) {

					$newUser 		   = User::find($usr->IdUsuario);
					$newUser->password = Hash::make($data['New']);

					if (!$newUser->save()) return response([
						"status"  => false,
						"message" => "update_error"
					], 500);

					return response([
						"status"  => true,
						'typemsj' => 1,
						"message" => 'Reinicie sesión!',
						"Logout"  => true
					], 200);

				} else {
					return response([
						"status"  => false,
						'typemsj' => 3,
						"message" => 'Las credenciales del usuario son invalidas',
					], 401);
				}

			} else {
				return response([
					"status"  => false,
					"message" => 'Usuario no encontrado',
				], 404);
			}

		} catch (\Exception $e) {
			return response([
				"typemsj" => 1,
				"status"  => false,
				"message" => "Internal_error!",
				//"error"   => $e->getMessage(),
			],500);
		}

    }

    private function checkPass(string $pass, $dbHased) {
        return $checkPass = Hash::check($pass,$dbHased);
    }

    public function searchMe() {
		//RECIBIMOS LOS DATOS DEL USUARIO QUE ENVIA LA PETICIÓN
		$usr = JWTAuth::authenticate();

		return response([
			"status"   => true,
			"message"  => "success",
			"RutaFile" => URL::to('/').Storage::url($this->RutaFile.'/'),
			"data"     => $usr
		],200);

	}

	public function updateProfile(Request $request) {

		try {

			$UserData   = $request->only("IdUsuario","Nombre","ApellidoPat","ApellidoMat","Telefono","Correo","Imagen");
			$validation = $this->updateProfileValidator($UserData);

			if ($validation->fails()) return response([
				"typemsj" => 2,
				"message" => "parametros invalidos",
				"errors"  => $validation->errors()
			],400);

			$newUser = User::find($UserData['IdUsuario']);

			if (!empty($newUser)) {

				if ($request->hasFile('Imagen')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->RutaFile,'Imagen');
					$newUser->Imagen = $Archivo['status'] ? $Archivo['HashName'] : '';
				}

				$newUser->IdUsuario   = $UserData['IdUsuario'];
				$newUser->Nombre      = $UserData['Nombre'];
				$newUser->ApellidoPat = $UserData['ApellidoPat'];
				$newUser->ApellidoMat = $UserData['ApellidoMat'];
				$newUser->Telefono    = (empty($UserData['Telefono'])) ? '' : $UserData['Telefono'];
				$newUser->Correo      = $UserData['Correo'];

			} else {

				if ($request->hasFile('Imagen')) {
					$Archivo = FilesManager::UploadFileStorage($request, '/'.$this->RutaFile,'Imagen');
					$params['Imagen'] = $Archivo['status'] ? $Archivo['HashName'] : '';
				} else {
                    $params['Imagen'] = '';
                }

			}

			#CONFIGURAR DATOS POR DEFECTO
			$newUser->NombreCompleto = $UserData['Nombre'] . ' ' . $UserData['ApellidoPat'] . ' ' . $UserData['ApellidoMat'];

			if (!$newUser->save()) return response([
				"typemsj" => 1,
				"status"  => false,
				"message" => "update_error"
			],500);

			return response([
				"typemsj"  => 1,
				"status"   => true,
				"message"  => "Correctamente",
				"RutaFile" => URL::to('/').Storage::url($this->RutaFile.'/'),
				"usuario"  => $newUser
			],200);

		} catch (\Exception $e){
			return response([
				"typemsj" => 1,
				"status"  => false,
				"message" => "Internal_error!",
				"error"   => $e->getMessage(),
			],500);
		}

    }

    public function refresh(Request $request) {

		try {
			$oldToken  = JWTAuth::getToken();
			$refreshed = JWTAuth::refresh($oldToken);
			$request->headers->set('Authorization','');
			$request->headers->set('Authorization',$refreshed);

			return response([
				"typemsj" => 1,
				"status"  => true,
				"message" => "success",
				"data"    => $refreshed
			],200);

		} catch (JWTException $e) {
			return response()->json([
				'status'  => false,
				'typemsj' => "1",
				"message" => 'Token cannot be refreshed, Inicie sesión de nuevo',
				"Logout"  => true,
				"error"   => $e->getMessage()
			],403);
		}

    }

	public function mailPassUser(Request $request) {

        try {

            $oData 		= $request->only('correo');
            $validation = $this->resetPassValidator($oData);

            if ($validation->fails()) return response([
                "typemsj" => 2,
                "message" => "parametros invalidos",
                "errors"  => $validation->errors()
            ], 400);

            if($this->sendMeKey(trim($oData['correo']))) {

                return response([
                    "status"  => true,
                    "message" => "success",
                ],200);

            } else {
                return response([
                    "status"  => false,
                    "message" => 'Usuario incorrecto',
                ],400);

            }

        } catch (\Exception $e) {
            return response([
                "typemsj" => 1,
                "status"  => false,
                "message" => "Internal_error!",
                "error"   => $e->getMessage(),
            ],500);
        }

    }

	private function sendMeKey($correos = '') {
        $correo   = trim($correos);
        $findUser = User::where('correo', '=', $correo)->first();

        if(!empty($findUser)) {
            $Idsubjet 	 = $findUser->IdUsuario;
            $key         = sha1($Idsubjet);
            $currentTime = date('Y-m-d H:i:s');

            //GENERAMOS EL REGISTRO DE CAMBIO DE CONTRASEÑA
            $dataReset = array(
                'IdUsuario'     => $Idsubjet,
                'Correo'        => $correo,
                'MasterKey'     => $key,
                'OperationTime' => $currentTime,
            );

            $NewReset = new RestablecerPassword($dataReset);

            if(!$NewReset->save()) return response([
                "status"  => false,
                "message" => 'Error 747',
            ], 500);

            //GENERAMOS EL LINK(ENLACE) PARA EL USUARIO QUE SOLICITO EL CAMBIO DE CONTRASEÑA
            $siteURL 			 = MailManager::getAdminSystemURL();
            $link 	 			 = $siteURL.'#/recoveryaccount/'.$key.'/'.$Idsubjet.'/'.$NewReset->IdReset; // RUTA LOCAL
            $body 				 = new RecuperarPasswordMailable();
            $body->NomEmpresa    = MailManager::getNameApp();
			$body->siteURLAdmin  = MailManager::getAdminSystemURL();
            $body->Nombre        = $findUser->NombreCompleto;
            $body->Correo        = $correo;
            $body->Link          = $link;
            $body->OperationTime = $currentTime;

            //OBTENEMOS LA CONFIGURACIÓN BASE O ACTUAL DEL SERVICIO DEL CORREO
            if (MailManager::SetConfig('PROVIDER')) {
                Mail::to($correo)->send($body);
                return true;

            } else {
                return false;
            }

        } else {
            return false;
        }

    }

	public function validReset(Request $request) {

        try {
            $oData 		= $request->only('Key','Id','Num');
            $validation = $this->validKeyValidator($oData);

            if ($validation->fails()) return response([
                "typemsj" => 2,
                "message" => "parametros invalidos",
                "errors"  => $validation->errors()
            ], 400);

            $res = $this->checkMyData($oData);

            if ($res['status']) {
                return response([
                    "status"  => true,
                    "message" => "success",
                ],200);

            } else {
                if ($res['Moto'] == 1){
                    return response([
                        "status"  => false,
                        "message" => 'link invalido',
                    ],400);

                } else if ($res['Moto'] == 2) {
                    return response([
                        "status"  => false,
                        "message" => 'link vencido',
                    ], 400);
                }
            }

        } catch (\Exception $e){
            return response([
                "typemsj" => 1,
                "status"  => false,
                "message" => "Internal_error!",
                "error"   => $e->getMessage(),
            ],500);
        }

    }

	private function checkMyData($data = []) {
        $Mky = $data['Key'];
        $Id  = $data['Id'];
        $Num = $data['Num'];

		// VERIFICAMOS EL REGISTRO ENTRANTE CON EL NUMERO DE REGISTRO EN LA TABLA password_resets
        $resetData = RestablecerPassword::find($Num);

        if (!empty($resetData)) {

			// VALIDAMOS QUE EL TOKEN ENTRANTE SEA IGUAL AL IDUSUARIO DEL REGISTRO
            $DBUsr 	   = sha1($resetData->IdUsuario);
            $flagBDusr = ($DBUsr == $Mky)? true : false;

			// VALIDAMOS QUE EL IDUSUARIO ENTRANTE ES IGUAL AL MASTERKEY DEL REGISTRO
            $ReqId 	   = sha1($Id);
            $flagBDKey = ($ReqId == $resetData->MasterKey) ? true : false;

            if ($flagBDusr == true && $flagBDKey == true) {

                // VALIDAMOS EL TIEMPO
                $currentTime = date('Y-m-d H:i:s');
                $MaxOpTime 	 = $this->getMaxOpTime($resetData->OperationTime);

                if ($currentTime > $MaxOpTime) {
                    $resetData->delete();
                    return [
                        "status"  => false,
                        "message" => 'link vencido',
                        "Moto"    => 2,
                        "Maxtime" => $MaxOpTime,
                    ];

                } else {
                    return [
                        "status"  => true,
                        "message" => "success",
                    ];
                }

            } else {
                return [
                    "status"  => false,
                    "message" => 'link invalido',
                    "Moto"    => 1,
                ];
            }

        } else {
            return [
                "status"  => false,
                "Moto"    => 1,
                "message" => 'link invalido',
            ];
        }

    }

	protected function getMaxOpTime($OperTime = '') {
        $Time1 = explode(' ',$OperTime);
        $date  = explode('-',$Time1[0]); // FECHA YYYY-MM-DD
        $hours = explode(':',$Time1[1]); // MINUTOS DE LA FECHA 00:00:00
        return date("Y-m-d H:i:s",mktime($hours[0],$hours[1]+15,$hours[2],$date[1],$date[2],$date[0])); // TIEMPO MAXIMO +15 minutos
    }

	public function changeMyPass(Request $request) {

        try {

            $data 		= $request->only('Key','Id','Num','password','confirmpassword',);
            $validation = $this->changeEPassValidator($data);

            if ($validation->fails()) return response([
                'typemsj' => 2,
                "message" => "parametros invalidos",
                "errors"  => $validation->errors()
            ], 400);

            $DataValid = $this->checkMyData($data);

            if($DataValid['status']) {

                $newUser 		   = User::find($data['Id']);
                $newUser->password = Hash::make($data['password']);

                if (!$newUser->save()) return response([
                    "status"  => false,
                    "message" => "update_error"
                ], 500);

                $Reset = RestablecerPassword::find($data['Num']);

                if(!empty($Reset)){
                    if ($Reset->delete()){

                        return response([
                            "status"  => true,
                            "message" => 'Credenciales actualizadas, Reinicie sesión!',
                        ], 200);

                    }else {

                        return response([
                            "status"  => true,
                            "message" => 'Credenciales actualizadas, Reinicie sesión!',
                            "with"    => 'internal_error!'
                        ], 200);

                    }
                }

			} else {

                if ($DataValid['Moto'] == 1){

					return response([
						"status"  => false,
						"message" => 'link invalido',
					],400);

                } else if ($DataValid['Moto'] == 2) {

                    return response([
                        "status"  => false,
                        "message" => 'link vencido',
					], 400);

				}

			}

        } catch (\Exception $e) {
            return response([
                "typemsj" => 1,
                "status"  => false,
                "message" => "Internal_error!",
                "error"   => $e->getMessage(),
            ],500);
        }

    }

	#********************* FUNCIONES DE VALIDACIÓN SEGURIDAD *********************************

	public function updateProfileValidator($params = []) {
		return validator($params,
		[
			"IdUsuario"   => "required|numeric",
			"Nombre"      => "required|string",
			"ApellidoPat" => "required|string",
			"ApellidoMat" => "required|string",
			"Telefono"    => "required|string",
			"Correo"      => "required|email",
		],
		['required' => 'El campo es requerido.']);
	}

	public function changePaswordValidator($params = []) {
		return validator($params,
			[
				"Old"        => "required",
				"New"        => 'min:6|required_with:ConfirmNew|same:ConfirmNew',
				"ConfirmNew" => 'min:6'
			],
			[
				'required' => 'El campo es requerido.',
				'same'     => 'Las nuevas contraseñas tienen que coincidir',
				'min'      => 'La logitud minima es de 6 carateres',
			]
		);
	}

	public function resetPassValidator($params = []) {
		return validator($params,
			[ "correo"     => "required|email"],
			[
				'required' => 'El campo es requerido.',
				'email'    => 'Escribe un correo valido',
			]
		);
	}

	public function validKeyValidator($params = []) {
		return validator($params,
		[
			"Key" => "required",
			"Id"  => "required",
			"Num" => "required",
		],
		['required' => 'Parametro requerido.',]);
	}

	public function changeEPassValidator($params = []) {
		return validator($params,
			[
				"Key"             => "required",
				"Id"              => "required",
				"Num"             => "required",
				"password"        => "min:6|required_with:confirmpassword|same:confirmpassword",
				"confirmpassword" => "required|min:6",
			],
			[
				'required' => 'Informacion requerida.',
				'same'     => 'Las nuevas contraseñas tienen que coincidir',
				'min'      => 'La logitud minima es de 6 carateres',
			]
		);
	}

	#********************* FUNCIONES DE VALIDACIÓN ESPECIALES *********************************

	public function addUserValidator($params = [], $IdUsuario = 0) {
		$required = [
			"IdEstado"       => "required|numeric|min:1",
			"IdMunicipio"    => "required|numeric|min:1",
			"Nombre"         => "required|string",
			"ApellidoPat"    => "required|string",
			"ApellidoMat"    => "required|string",
			"Telefono"       => "required|string"
		];

		if ($IdUsuario <= 0) {
			$required += [
				"IdPerfil"              => "required|numeric|min:1",
				"IdPuesto"              => "required|numeric|min:1",
				"username"              => "required|string|unique:users,deleted_at,NULL",
				'password'              => 'min:8|required_with:password_confirmation|same:password_confirmation',
				'password_confirmation' => 'min:8'
			];
		}

		if($params['Correo']!=''){
			$required+=['Correo' => 'required|email'];
		}

		return validator($params, $required, [
			'required'  				=> 'El campo es requerido.',
			'min'       				=> 'Debe seleccionar una opción.',
			'password.min'				=> 'El campo mínimo es de 8 carácteres.',
			'password_confirmation.min' => 'El campo mínimo es de 8 carácteres.',
			'same'      			    => 'Las contraseñas no coinciden.',
			'unique'    			    => 'Este nombre de usuario ya ha sido utilizado en otra cuenta.',
			'string'    			    => 'El campo es requerido.',
			'email'					    => 'El correo no tiene el formato correcto'
		]);
	}

	//!prueba
	public function upEmpleadoValidator($params = []) {
		$required = [
			"Nombre"         => "required|string",
			"ApellidoPat"    => "required|string",
			"ApellidoMat"    => "required|string",
			"Telefono"       => "required|string",

		];

		return validator($params, $required, [
			'required'  => 'El campo es requerido.',
			'string'    => 'El campo es requerido.',
		]);
	}

	/*
     * API PARA TRAER EL LISTADOS DE USUARIOS QUE VAN A SERVIR PARA ASIGNAR A LAS RUTAS
     * METHOD: GET
	*/
	public function getListUsuarios() {
		try
		{
			$users = User::whereIn('IdPerfil',[2,4])->get();

			return response([
                "status"   => true,
                "message"  => 'success',
                "data"     => $users,

            ],200);
		}
		catch (Exception $exception) {
			return $exception->getMessage();
			//return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA TRAER EL LISTADOS DE USUARIOS DE ACUERDO A SU PERFIL UNICO O EN GRUPO, BASADOS EN LA SUCURSAL
     * METHOD: GET
	*/
	public function getListUsersJoin(Request $req) {
		try
		{
			$sesion = JWTAuth::authenticate();
			$whereIn = '';
			$where = array(
				array('us.IdSucursal','=',$sesion->IdSucursal)
			);

			// SI RECIBE EL ID DEL USUARIO VA ELIMINAR DE LA LISTA EL MISMO USUARIO QUE ESTA ENVIANDO
			if(!empty($req->get('IdUsuario'))){
				$where[] = array('IdUsuario','!=',$req->get('IdUsuario'));
			}

			if($req->get('Tipo') == 4){
				$whereIn = [4];
			}
			else if($req->get('Tipo') == 3){
				$whereIn = [3];
			}
			else if($req->get('Tipo') == 2){
				$whereIn = [2];
			}
			else if($req->get('Tipo') == 'Todos'){
				$whereIn = [2,3,4];
			}

			$users = DB::table('users as us')
			->join('perfiles as pf','pf.IdPerfil','=','us.IdPerfil')
			->select(
				'us.IdUsuario',
				'us.IdEmpresa',
				'us.IdSucursal',
				'us.IdEmpleado',
				'us.IdPerfil',
				'us.IdPuesto',
				'us.IdPais',
				'us.IdEstado',
				'us.IdMunicipio',
				'us.Nombre',
				'us.ApellidoPat',
				'us.ApellidoMat',
				'us.NombreCompleto',
				'us.Correo',
				'us.Telefono',
				'us.username',
				'pf.Nombre as Perfil'
			)
			->whereIn('us.IdPerfil',$whereIn)
			->where($where)->get();

			return response([
                "status"   => true,
                "message"  => 'success',
                "data"     => $users,
            ],200);
		}
		catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}
}
