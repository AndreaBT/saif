<?php

use App\Http\Controllers\PrestamosCancelacionesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* A PARTIR DE AQUI VA LA IMPORTACIÓN DE LOS CONTROLADORES A USAR */
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailServiceConfigController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\PerfilesController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\EmpDatosFamController;
use App\Http\Controllers\EmpleadosRefPersonalesController;
use App\Http\Controllers\EmpleadosHerramientasController;
use App\Http\Controllers\EmpleadosEvidenciasController;
use App\Http\Controllers\EquiposController;
use App\Http\Controllers\PrestamosController;
use App\Http\Controllers\PrestamosEvidenciasController;
use App\Http\Controllers\PrestamosPagosController;
use App\Http\Controllers\PrestamosMontosController;
use App\Http\Controllers\PrestamosEntregasController;
use App\Http\Controllers\RutasController;
use App\Http\Controllers\RutasxEmpleadosController;
use App\Http\Controllers\PuestosController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\MunicipiosController;
use App\Http\Controllers\PanelMenuController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ClientesEvidenciasController;
use App\Http\Controllers\PanelesxPermisosController;
use App\Http\Controllers\PerfilesxMenusController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ClientesxCobratarioController;
use App\Http\Controllers\TazaInteresController;
use App\Http\Controllers\CobranzaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* AÑADIR AQUI LAS RUTAS SIN PROTECCIÓN DE TOKEN */

Route::post("/login", 		       [UserController::class, "login"]);
Route::post("/usresetacount",      [UserController::class, "mailPassUser"]);
Route::post("/usrvalidreset", 	   [UserController::class, "validReset"]);
Route::post("/usraceptchangepass", [UserController::class, "changeMyPass"]);

Route::post("/creaEnlace", [SystemController::class, "creaEnlace"]); ## Crear el Storage-link si no existe



/* AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
Route::group(['middleware' => ['jwt.verify']], function () {



	#API PARA EL GET DE PRESTAMOS CON ESTATUS COBRANZA Y CANCELACION - PARA APP
    Route::get("/entregadorprestamos",   [PrestamosController::class, "getPrestamosEntregador"]);
	Route::get("/prestamosCobranza",     [CobranzaController::class, "getPrestamosCobranzaApp"]); ## APP


	#ROUTE USUARIOS

	// ESTANDAR CRUD
	Route::get("/users", 	  	 		  [UserController::class, "findAll"]);
	Route::get("/users/{id}", 	 		  [UserController::class, "findOne"]);
	Route::post("/users", 	  	 		  [UserController::class, "add"]);
	Route::post("/userUp", 	  	 		  [UserController::class, "update"]);
	Route::delete("/users/{id}", 		  [UserController::class, "delete"]);
	//--> SPECIAL OPERATIONS
	Route::post("/usrcredentials", 		  [UserController::class, "changePassword"]);
	Route::get("/usrsrchme", 	   		  [UserController::class, "searchMe"]);
	Route::post("/usrupdaprofile", 		  [UserController::class, "updateProfile"]);
	Route::get("/usrefresh", 	   		  [UserController::class, "refresh"]);
	Route::get("/users/getUsuEmple/{id}", [UserController::class, "getUserEmpleado"]);
	Route::get("/usuariosCobratarios", 	  [UserController::class, "getListUsersJoin"]);

    ## REVISADAS
    Route::get("/getusersbyperfil", 	   	  [UserController::class, "getUserTipoPerfil"]);

	#TERMINA USUARIOS

	#PERFILES


	// ESTANDAR CRUD
	Route::get("/perfiles",         [PerfilesController::class, "findAll"]);
	Route::get("/perfiles/{id}",    [PerfilesController::class, "findOne"]);
	Route::post("/perfiles",        [PerfilesController::class, "add"]);
	Route::put("/perfiles/{id}",    [PerfilesController::class, "update"]);
	Route::delete("/perfiles/{id}", [PerfilesController::class, "delete"]);


	#TERMINA PERFILES

	#ROUTE PERMISOS


	// ESTANDAR CRUD
	Route::get("/permisos",         [PermisosController::class, "findAll"]);
	Route::get("/permisos/{id}",    [PermisosController::class, "findOne"]);
	Route::post("/permisos",        [PermisosController::class, "add"]);
	Route::put("/permisos/{id}",    [PermisosController::class, "update"]);
	Route::delete("/permisos/{id}", [PermisosController::class, "delete"]);


	#TERMINA PERMISOS

	#SUCURSALES


	Route::get("/sucursales", 	   	  [SucursalesController::class, "findAll"]);
	Route::get("/sucursales/{id}", 	  [SucursalesController::class, "findOne"]);
	Route::post("/sucursales", 		  [SucursalesController::class, "add"]);
	Route::put("/sucursales/{id}",    [SucursalesController::class, "update"]);
	Route::delete("/sucursales/{id}", [SucursalesController::class, "delete"]);


	#TERMINA SUCURSALES

	#EMPRESAS

	Route::get("/empresas", 	 	[EmpresasController::class, "findAll"]);
	Route::get("/empresas/{id}", 	[EmpresasController::class, "findOne"]);
	Route::post("/empresas", 		[EmpresasController::class, "add"]);
	Route::post("/empresasup",      [EmpresasController::class, "update"]);
	Route::delete("/empresas/{id}", [EmpresasController::class, "delete"]);

	#TERMINA EMPRESAS

	#EMPLEADOS
	Route::get("/empleados", 		 [EmpleadosController::class, "findAll"]);
	Route::get("/empleados/{id}", 	 [EmpleadosController::class, "findOne"]);
	Route::post("/empleados", 		 [EmpleadosController::class, "add"]);
	Route::put("/empleados/{id}", 	 [EmpleadosController::class, "update"]);
	Route::delete("/empleados/{id}", [EmpleadosController::class, "delete"]);

	#TERMINA EMPLEADOS

	#EMPDATOSFAM
	Route::get("/empdatosfam",         [EmpDatosFamController::class, "findAll"]);
	Route::get("/empdatosfam/{id}",    [EmpDatosFamController::class, "findOne"]);
	Route::post("/empdatosfam", 	   [EmpDatosFamController::class, "add"]);
	Route::put("/empdatosfam/{id}",    [EmpDatosFamController::class, "update"]);
	Route::delete("/empdatosfam/{id}", [EmpDatosFamController::class, "delete"]);

	#TERMINA EMPDATOSFAM

	#EMPLEADOSREFPERSONALES
	Route::get("/EmpDatosRef", 		   [EmpleadosRefPersonalesController::class, "findAll"]);
	Route::get("/EmpDatosRef/{id}",    [EmpleadosRefPersonalesController::class, "findOne"]);
	Route::post("/EmpDatosRef",        [EmpleadosRefPersonalesController::class, "add"]);
	Route::put("/EmpDatosRef/{id}",    [EmpleadosRefPersonalesController::class, "update"]);
	Route::delete("/EmpDatosRef/{id}", [EmpleadosRefPersonalesController::class, "delete"]);

	#TERMINA EMPLEADOSREFPERSONALES

	#HERRAMIENTAS
	Route::get("/herramientas", 		[EmpleadosHerramientasController::class, "findAll"]);
	Route::get("/herramientas/{id}", 	[EmpleadosHerramientasController::class, "findOne"]);
	Route::post("/herramientas", 		[EmpleadosHerramientasController::class, "add"]);
	Route::put("/herramientas/{id}",    [EmpleadosHerramientasController::class, "update"]);
	Route::delete("/herramientas/{id}", [EmpleadosHerramientasController::class, "delete"]);
	#TERMINA HERRAMIENTAS

	#EVIDENCIAS
	Route::get("/evidencias",         [EmpleadosEvidenciasController::class, "findAll"]);
	Route::get("/evidencias/{id}",    [EmpleadosEvidenciasController::class, "findOne"]);
	Route::post("/evidencias", 		  [EmpleadosEvidenciasController::class, "add"]);
	Route::put("/evidencias/{id}",    [EmpleadosEvidenciasController::class, "update"]);
	Route::delete("/evidencias/{id}", [EmpleadosEvidenciasController::class, "delete"]);

	#TERMINA EVIDENCIAS

	#EQUIPOS
	Route::get("/equipos", 		   [EquiposController::class, "findAll"]);
	Route::get("/equipos/{id}",    [EquiposController::class, "findOne"]);
	Route::post("/equipos", 	   [EquiposController::class, "add"]);
	//Route::put("/equipos/{id}",    [EquiposController::class, "update"]);
	Route::delete("/equipos/{id}", [EquiposController::class, "delete"]);
	Route::get("/equipoTipoVehiculo", [EquiposController::class, "GetTipoDeEquipos"]);
	Route::get("/equipobitacora", [EquiposController::class, "getBitacoraEquipo"]);
	Route::get("/recoveryequipobitacora/{id}", [EquiposController::class, "findOneBitacora"]);


	#TERMINA EQUIPOS

	#RUTAS PRESTAMOS
	Route::get("/prestamos", 	   	   [PrestamosController::class, "findAll"]);
	Route::get("/prestamos/{id}", 	   [PrestamosController::class, "findOne"]);
	Route::post("/prestamos", 		   [PrestamosController::class, "add"]);
	Route::put("/prestamos/{id}",      [PrestamosController::class, "update"]);
	Route::delete("/prestamos/{id}",   [PrestamosController::class, "delete"]);
	Route::get("/entregadorprestamos", [PrestamosController::class, "getPrestamosEntregador"]);
	#RUTAS DE PRESTAMOS PARA CLIENTES ACTIVOS
	Route::post("/addPrestamoClienteActivo",  [PrestamosController::class, "addPrestamoClienteActivo"]);
	// Route::get("/getprestamosclientesall",	  [PrestamosController::class, "GetPrestamosClientesAll"]);
	// Route::put("/prestamoPreautorizado/{id}", [PrestamosController::class, "AuthorizePrestamo"]);
	#TERMINA RUTAS DE PRESTAMOS PARA CLIENTES ACTIVOS

	Route::get("/getPrestamosAsignados",	  [PrestamosController::class, "getPrestamosxUsuario"]);
	Route::post("/ReasignarPrestamosUsuario", [PrestamosController::class, "reasignarPrestamosUsuario"]);

	#TERMINA PRESTAMOS

	#PRESTAMOS EVIDENCIAS


	Route::get("/prestamosevidencias", 	   	   [PrestamosEvidenciasController::class, "findAll"]);
	Route::get("/prestamosevidencias/{id}",    [PrestamosEvidenciasController::class, "findOne"]);
	Route::post("/prestamosevidencias", 	   [PrestamosEvidenciasController::class, "add"]);
	Route::put("/prestamosevidencias/{id}",    [PrestamosEvidenciasController::class, "update"]);
	Route::delete("/prestamosevidencias/{id}", [PrestamosEvidenciasController::class, "delete"]);


	#TERMINA PRESTAMOS EVIDENCIAS

	#PRESTAMOS PAGOS


	Route::get("/prestamospagos", 	   	  [PrestamosPagosController::class, "findAll"]);
	Route::get("/prestamospagos/{id}", 	  [PrestamosPagosController::class, "findOne"]);
	Route::post("/prestamospagos", 		  [PrestamosPagosController::class, "add"]);
	Route::put("/prestamospagos/{id}",    [PrestamosPagosController::class, "update"]);
	Route::delete("/prestamospagos/{id}", [PrestamosPagosController::class, "delete"]);


	#TERMINA PRESTAMOS PAGOS

	#PRESTAMOS MONTOS


	Route::get("/prestamosmontos", 	   	   [PrestamosMontosController::class, "findAll"]);
	Route::get("/prestamosmontos/{id}",    [PrestamosMontosController::class, "findOne"]);
	Route::post("/prestamosmontos", 	   [PrestamosMontosController::class, "add"]);
	Route::put("/prestamosmontos/{id}",    [PrestamosMontosController::class, "update"]);
	Route::delete("/prestamosmontos/{id}", [PrestamosMontosController::class, "delete"]);


	#TERMINA PRESTAMOS MONTOS

	#PRESTAMOS RUTAS


	Route::get("/rutas", 	   	 [RutasController::class, "findAll"]);
	Route::get("/rutas/{id}", 	 [RutasController::class, "findOne"]);
	Route::post("/rutas", 		 [RutasController::class, "add"]);
	Route::put("/rutas/{id}",    [RutasController::class, "update"]);
	Route::delete("/rutas/{id}", [RutasController::class, "delete"]);


	#TERMINA PRESTAMOS RUTAS

	#RUTAS EMPLEADOS
	Route::get("/rutasxusuarios", 	   	   	[RutasxEmpleadosController::class, "findAll"]);
	Route::get("/rutasxusuarios/{id}",    	[RutasxEmpleadosController::class, "findOne"]);
	Route::post("/rutasxusuarios", 	   		[RutasxEmpleadosController::class, "add"]);
	Route::put("/rutasxusuarios/{id}",    	[RutasxEmpleadosController::class, "update"]);
	Route::delete("/rutasxusuarios/{id}", 	[RutasxEmpleadosController::class, "delete"]);
	#END EMPLEADOS

	#CONSULTAS ESPECIALES
	Route::get("/rutasxusuariosinner",  [RutasxEmpleadosController::class, "findAllInner"]);
	Route::get("/rutasxusuariosUsers",	[UserController::class, "getListUsuarios"]);




	#TERMINA RUTAS EMPLEADOS

	#RUTA PUESTOS


	Route::get("/puestos", 		   [PuestosController::class, "findAll"]);
	Route::get("/puestos/{id}",    [PuestosController::class, "findOne"]);
	Route::post("/puestos", 	   [PuestosController::class, "add"]);
	Route::put("/puestos/{id}",    [PuestosController::class, "update"]);
	Route::delete("/puestos/{id}", [PuestosController::class, "delete"]);
	#TERMINA RUTA PUESTOS

	#RUTAS ESTADOS


	Route::get("/estados", 	   	   [EstadosController::class, "findAll"]);
	Route::get("/estados/{id}",    [EstadosController::class, "findOne"]);
	Route::post("/estados", 	   [EstadosController::class, "add"]);
	Route::put("/estados/{id}",    [EstadosController::class, "update"]);
	Route::delete("/estados/{id}", [EstadosController::class, "delete"]);


	#TERMINA ESTADOS

	#RUTAS MUNICIPIOS


	Route::get("/municipios", 	   	  [MunicipiosController::class, "findAll"]);
	Route::get("/municipios/{id}", 	  [MunicipiosController::class, "findOne"]);
	Route::post("/municipios", 		  [MunicipiosController::class, "add"]);
	Route::put("/municipios/{id}",    [MunicipiosController::class, "update"]);
	Route::delete("/municipios/{id}", [MunicipiosController::class, "delete"]);


	#TERMINA MUNICIPIOS

	#RUTAS MENUS


	Route::get("/panelmenu",		 [PanelMenuController::class, "findAll"]);
	Route::get("/panelmenu/{id}",	 [PanelMenuController::class, "findOne"]);
	Route::post("/panelmenu",		 [PanelMenuController::class, "add"]);
	Route::put("/panelmenu/{id}",    [PanelMenuController::class, "update"]);
	Route::delete("/panelmenu/{id}", [PanelMenuController::class, "delete"]);


	# FIN RUTAS MENUS

	#RUTAS CLIENTES


	Route::get("/clientes", 	   	[ClientesController::class, "findAll"]);
	Route::get("/clientes/{id}", 	[ClientesController::class, "findOne"]);
	Route::post("/clientes", 		[ClientesController::class, "add"]);
	Route::put("/clientes/{id}",    [ClientesController::class, "update"]);
	Route::delete("/clientes/{id}", [ClientesController::class, "delete"]);

	Route::get("/clientesRechazados",   	   [ClientesController::class, "getRechazados"]);
	Route::get("/entregadoresxPrestamo",       [ClientesController::class, "findAllEntregadoresxPrestamo"]);
	Route::get("/clientesProspectoRechazados", [ClientesController::class, "getRechazadosProspectoPrestamo"]);
	Route::get("/prestamoAsignadoxUsu",		   [ClientesController::class, "getAsignadosxUsu"]);

	#Reciclar Rutas
	Route::post("/prospestoPrestamoEtapas",    [ClientesController::class, "clientesPrestamosCambioEstatus"]); ## CAMBIO DE ESTATUS DE PROSPECTOS,CLIENTES POR ETPAS
    Route::get("/prospectosxprestamos",		   [ClientesController::class, "findProspectosPrestamos"]); ## LISTADO DE CLIENTES CON PRESTAMOS POR ETAPAS
    Route::get("/clientesPreAutorizados",      [ClientesController::class, "GetPreAutorizados"]); ## LISTA PRESTAMOS PREAUTORIZADOS EN ARREGLO SIMPLE
    Route::post("/PrestamoAutorizar",		   [ClientesController::class, "AsignarPrestamo"]); ## POST GUARDADO DE ASIGNACION DE PRESTAMOS
    Route::post("/retornaprestamosautorizado", [ClientesController::class, "RetornarPrestamoAutorizado"]); ## POST DEASIGNACION DE PRESTAMOS
    Route::get("/prestamosasignado",		   [ClientesController::class, "getClientesPrestamosAsignados"]);

	#RUTA DE CLIENTES Activos
	Route::get("/clientesActivos",				 [ClientesController::class, "GetClienteActivo"]);
	Route::get("/clientesActivos/{id}", 		 [ClientesController::class, "findOneClienteActivo"]);
	Route::get("/clientesPrestamosActivos/{id}", [ClientesController::class, "findOndePrestamoCliente"]);
	Route::post("/clientesActivosUp", 			 [ClientesController::class, "UpdateClienteActivo"]);
	Route::get("/clientesRutasActivos/{id}",	 [ClientesController::class, "findOneRutaCliente"]);
    #TERMINA RUTA DE CLIENTES Activos

	#TERMINA CLIENTES

	#RUTAS CLIENTES EVIDENCIAS


	Route::get("/clientesevidencias", 	   	  [ClientesEvidenciasController::class, "findAll"]);
	Route::get("/clientesevidencias/{id}", 	  [ClientesEvidenciasController::class, "findOne"]);
	Route::post("/clientesevidencias", 		  [ClientesEvidenciasController::class, "add"]);
	Route::put("/clientesevidencias/{id}",    [ClientesEvidenciasController::class, "update"]);
	Route::delete("/clientesevidencias/{id}", [ClientesEvidenciasController::class, "delete"]);
	Route::post("/clientesevidenciasUpdate",  [ClientesEvidenciasController::class, "update"]);


	#TERMINA CLIENTES EVIDENCIAS

	#RUTAS CONFIGURACIÓN DE SERVICIO DEL CORREO ELECTRONICO


	Route::get("/mailserviceconf", 	       [MailServiceConfigController::class, "findAll"]);
	Route::post("/mailserviceconf/",	   [MailServiceConfigController::class, "findOne"]);
	Route::put("/mailserviceconf/{id}",    [MailServiceConfigController::class, "update"]);
	Route::delete("/mailserviceconf/{id}", [MailServiceConfigController::class, "delete"]);


	#TERMINA CONFIGURACION DE SERVICIO DE EMAIL

	#RUTAS PANELES X PERMISOS


	Route::get("/panelesxpermisos",			[PanelesxPermisosController::class, "findAll"]);
	Route::get("/panelesxpermisos/{id}",	[PanelesxPermisosController::class, "findOne"]);
	Route::post("/panelesxpermisos",		[PanelesxPermisosController::class, "add"]);
	Route::get("/panelesxpermisosinner",	[PanelesxPermisosController::class, "findAllInner"]);


	#END PANELES X PERMISOS

	#RUTAS PERFILES X MENUS


	Route::get("/perfilesxmenus",			  [PerfilesxMenusController::class, "findAll"]);
	Route::post("/perfilesxmenus",			  [PerfilesxMenusController::class, "add"]);
	Route::get("/perfilesxmenusdependientes", [PerfilesxMenusController::class, "getMenusDependientes"]);
	Route::get("/perfilesxmenuspermisos",	  [PerfilesxMenusController::class, "getPermisosxMenus"]);
	Route::get("/perfilesxmenusaddpermisos",  [PerfilesxMenusController::class, "addPermisosxPerfil"]);

	#END PERFILES X MENUS

	#RUTAS DE CLIENTESxCOBRATARIOS

	Route::get("/getClientesxCobratario", [ClientesxCobratarioController::class, "getClientesxCobratario"]);
	Route::get("/getUsuarios",			  [ClientesxCobratarioController::class, "getUsuarios"]);
	Route::post("/asignacionMasiva",	  [ClientesxCobratarioController::class, "asignacionMasiva"]);
	#END RUTAS DE CLIENTESxCOBRATARIOS

	#RUTA DE COBRANZA

	Route::get("/getUsuariosCobranza",		 [CobranzaController::class, "getUsuariosCobranza"]);
	Route::get("/getprestamoscorte",         [CobranzaController::class, "getPrestamosCorte"]);
    Route::post("/Pagos",					 [CobranzaController::class, "generaNuevoDia"]);

	#END RUTAS DE COBRANZA

	#RUTA DE TAZA DE INTERÉS

	Route::get("/getTazaInteres",		  [TazaInteresController::class, "findAll"]);
	Route::get("/getOneTazaInteres/{id}", [TazaInteresController::class, "findOne"]);
	Route::put("/updateTazaInteres/{id}", [TazaInteresController::class, "update"]);

	#END DE TAZA DE INTERÉS

    ##RUTA DE PRESTAMO CANCELACIONES

    Route::get("/prestamocancelrecovery", [PrestamosCancelacionesController::class, "findOne"]); ## RECUPERA UN MOTIVO DE CANCELACION POR ID DE LA MISMA
    Route::post("/prestamosCancelacion",  [PrestamosCancelacionesController::class, "PrestamosCancelacionApp"]); ## REALIZA LA CANCELACION DE UN PRESTAMO O ENTREGA.

    ##END RUTA DE PRESTAMO CANCELACIONES


	##RUTA DE PRESTAMOS ENTREGA


	Route::post("/prestamosEntrega", [PrestamosEntregasController::class, "PrestamoEntregaApp"]);


	##END DE PRESTAMOS ENTREGA
});
