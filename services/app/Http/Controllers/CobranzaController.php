<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\PrestamoPago;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CobranzaController extends Controller
{
    private $RutaFile     = 'imgusers';

    /*
    * API PARA listar a los Usuarios con el Perfil de Gestor - Supervisor
    * METHOD: GET
    * USE IN: WEB
    */

    public function getUsuariosCobranza(Request $request){
        try {

            $multiWhere = array();
            if (!empty($request->get('txtBusqueda'))) {
                $multiWhere[] = array("users.NombreCompleto", "like", "%".  $request->get('txtBusqueda')."%");
            }

            $PerfilGet = User::query()->where(function ($query) {
                $query->where('users.IdPerfil', '=', '2')
                    ->orWhere('users.IdPerfil', '=', '4');

            })->where($multiWhere)
                ->with(array('perfil','puesto'))
                ->paginate(
                    $request->query("Entrada"),
                    "*",
                    "page",
                    $request->query("Pag")
                );

            return response([
                "status"    => true,
                "message"   => "success",
                "data"      => $PerfilGet,
                "UrlImg"    => URL::to('/').Storage::url($this->RutaFile.'/'),
            ]);

        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception, true);
        }
    }

    /*
    * API PARA recuperar los PRÉSTAMOS DE Usuarios con el Perfil de Gestor - Supervisor
    * METHOD: GET
    */
    public function getPrestamosCorte(Request $request){
        try {

            if ( !empty($request->get('IdCobratario') )) {
                $IdCobratario = $request->get('IdCobratario'); ## IdUsuario

            }else {
                return response([
                    "status" => false,
                    "message" => "parametro IdUsuario no recibido"
                ],400);
            }

            $multiWhere = array();
            if (!empty($request->get('txtBusqueda'))) {
                $multiWhere[] = array("users.NombreCompleto", "like", "%".  $request->get('txtBusqueda')."%");
            }


            if ( !empty($request->get('estatusP')) ) {
                $multiWhere[] = array('prestamos.Estatus', '=', $request->get('estatusP'));

            }else {
                return response([
                    "status" => false,
                    "message" => "parametro estatusP no recibido"
                ],400);
            }

            $PrestamosCobranza = Prestamo::query()
                ->join('users', 'users.IdUsuario', '=', 'prestamos.IdCobratario')
                ->join('clientes', 'prestamos.IdCliente', '=', 'clientes.IdCliente')
                ->where('prestamos.IdCobratario','=', $IdCobratario)
                ->where($multiWhere)
                ->orderBy('prestamos.IdPrestamo','DESC')
                ->paginate(
                    $request->query("Entrada"),
                    array(
                        'prestamos.IdPrestamo',
                        'prestamos.IdCobratario',
                        'prestamos.IdCliente',
                        'prestamos.MontoSolicitud',
                        'prestamos.MontoEntregado',
                        'prestamos.NumPagoActual',
                        'prestamos.Estatus',
                        'prestamos.MontoDiario',
                        'prestamos.Folio',
                        'prestamos.updated_at',
                        'users.NombreCompleto',
                        'clientes.NombreCompleto as NomCliente',
                        'clientes.Telefono',
                    ),
                    "page",
                    $request->query("Pag")
                );


            $data['PrestamosCobranzas']=$PrestamosCobranza;

            return response([
                "status"    => true,
                "message"   => "success",
                "data"      => $data,
            ]);
        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception, true);
        }
    }

    /*
     * API PARA los pagos del día siguiente.
     * METHOD: post
     */
    public function generaNuevoDia(Request $request){
        try {

            $params = $request->only(
                "IdCobratario"
            );

            $d=strtotime("tomorrow");

            $Prestamo = Prestamo::where('IdCobratario', "=", $params['IdCobratario'])
                ->where('Estatus','=','Entregado')
                ->get()
                ->toArray();

            if(!empty($Prestamo) ){

                $PrestamosPagos = '';

                foreach($Prestamo as $elemento) {

                    $PagosArray = array(
                        'IdPrestamo' 		=> $elemento['IdPrestamo'],
                        'IdCobratario' 		=> $elemento['IdCobratario'],
                        'IdCliente' 		=> $elemento['IdCliente'],
                        'MontoReal' 		=> 0,//cambiar el monto real.
                        'NumeroPago'		=> 1,
                        'NumeroAbono'		=> 1,
                        'EstatusPago'		=> 'Pendiente',
                        'MontoEstimado' 	=> $elemento['MontoDiario'],
                        'FechaPagoEstimado'	=> date("Y-m-d", $d),
                    );

                    $PrestamosPagos = new PrestamoPago($PagosArray);

                    if (!$PrestamosPagos->save()) return response([
                        "status"  => false,
                        "message" => "No se autorizó el prestamo."
                    ], 500);

                }

                $data['prestamo'] = $Prestamo;

            }

            $PrestamoCobranza = Prestamo::query()
                ->where('IdCobratario', "=", $params['IdCobratario'])
                ->where('Estatus','=','Cobranza')
                ->get()
                ->toArray();

            foreach($PrestamoCobranza as $elemento) {
                $nextPago   = ($elemento['NumPagoActual'] +1);

                $PagosArray = array(
                    'IdPrestamo' 		=> $elemento['IdPrestamo'],
                    'IdCobratario' 		=> $elemento['IdCobratario'],
                    'IdCliente' 		=> $elemento['IdCliente'],
                    'MontoReal' 		=> 0,//cambiar el monto real.
                    'NumeroPago'		=> $nextPago,
                    'NumeroAbono'		=> 1,
                    'EstatusPago'		=> 'Pendiente',
                    'MontoEstimado' 	=> $elemento['MontoDiario'],
                    'FechaPagoEstimado'	=> date("Y-m-d", $d),
                    'Estatus'           => 'Cobranza',
                );

                $PrestamosPagos = new PrestamoPago($PagosArray);

                if (!$PrestamosPagos->save()) return response([
                    "status"  => false,
                    "message" => "No se autorizó el prestamo."
                ], 500);

                Prestamo::query()
                    ->where("IdPrestamo", "=", $elemento['IdPrestamo'])
                    ->where("Estatus", "=", 'Cobranza')
                    ->update(["NumPagoActual" => $nextPago]);

            }

            return response([
                "status"  => true,
                "message" => "Autorizado.",
                "data"    => '',
            ], 200);

        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception,"PrestamosController/Pagos","",true);
        }

    }

    /*
     * API PARA LISTADO DE PRESTAMOS CON ESTATUS COBRANZA -- PARA APP.
     * METHOD: GET
    */

    public function getPrestamosCobranzaApp(){
        try {
            $ClientesPrestamos =Prestamo::query()
                ->where('prestamos.Estatus','=', 'Cobranza')
                ->get();

            $PrestamosCobranza = array();
            if (!empty($ClientesPrestamos)) {
                foreach ($ClientesPrestamos as $element) {

                    $Cliente = Cliente::query()
                        ->find($element->IdCliente , array('NombreCompleto'));


                    if (!empty($Cliente)) {
                        $PrestamosPagos = PrestamoPago::query()
                            ->select('MontoEstimado','IdPrestamo','IdPrestamosPago')
                            ->where('IdPrestamo', '=',$element->IdPrestamo)
                            ->get();

                        $Cliente->prestamopago = $PrestamosPagos;
                    }

                    $PrestamosCobranza[] = $Cliente;
                }

                return response([
                    "status"    => true,
                    "message"   => "success",
                    "data"      => $PrestamosCobranza,
                ]);
            }else{
                return response([
                    "status"  => true,
                    "message" => 'Prestamo no encontrado.',
                ], 404);
            }


        } catch (Exception $exception) {
            return ResponseManager::setErrorServerResponse($exception,"PrestamosController/getPrestamosCobranzaApp","",true);
        }
    }
}
