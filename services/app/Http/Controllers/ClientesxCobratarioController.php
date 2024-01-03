<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\ClientesxCobratario;

class ClientesxCobratarioController extends Controller
{
    private $location = 'ClientesxCobratarioController';
    /*
     * API PARA LISTADO DE TODOS LOS CLIENTESxCOBRATARIOS
     * METHOD: GET
     */

    public function getClientesxCobratario(Request $request){
		try {

			$multiWhere[] = array('clientes.Estatus','=','Activo');

            if(!empty($request->get('IdUsuario'))) {
                $multiWhere[] = array('clientesxcobratarios.IdUsuario', '=', $request->get('IdUsuario'));
            }else {
                return response([
                    'status' => false,
                    'message' => 'seleccionar un usuario'
                ],400);
            }

            $search = '';
            if (!empty($request->get('TxtBusqueda'))) {
                $search = $request->get('TxtBusqueda');
            }

            if (!empty($request->get('isSimple'))) {
                $clientes = Cliente::query()
                    ->join('clientesxcobratarios', 'clientesxcobratarios.IdCliente', '=', 'clientes.IdCliente')
                    ->join('users', 'users.IdUsuario', '=', 'clientesxcobratarios.IdUsuario')
                    ->where($multiWhere)
                    ->where(function ($query) use ($search) {
                        $query->where('clientes.NombreCompleto', 'like', '%' . $search . '%');
                        //->orWhere('NoEmpleado', 'like', '%' . $search . '%');

                    })
                    ->get(
                        array('clientes.IdCliente',
                            'clientes.NombreCompleto as ClienteNombre',
                            'users.NombreCompleto as CobratarioNombre',
                            'clientesxcobratarios.IdUsuario',
                            'clientesxcobratarios.Idclientesxcobratarios'
                        )
                    );
            }else {
                $clientes = Cliente::query()
                    ->join('clientesxcobratarios', 'clientesxcobratarios.IdCliente', '=', 'clientes.IdCliente')
                    ->join('users', 'users.IdUsuario', '=', 'clientesxcobratarios.IdUsuario')
                    ->select( array('clientes.IdCliente',
                        'clientes.NombreCompleto as ClienteNombre',
                        'users.NombreCompleto as CobratarioNombre',
                        'clientesxcobratarios.IdUsuario',
                        'clientesxcobratarios.Idclientesxcobratarios'))
                    ->where($multiWhere)
                    ->where(function ($query) use ($search) {
                        $query->where('clientes.NombreCompleto', 'like', '%' . $search . '%');
                        //->orWhere('NoEmpleado', 'like', '%' . $search . '%');

                    })->paginate(
                        $request->query("Entrada"),
                        "*",
                        "page",
                        $request->query("Pag")
                    );
            }



            $data['Activos']=$clientes;

			return response([
				"status"    => true,
				"message"   => "success",
				"data"      => $data,
			]);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'getClientesxCobratario');
		}
	}

    public function getUsuarios(){
		try {

			$PerfilGet = DB::table('users')
			->join('perfiles', 'perfiles.IdPerfil', '=', 'users.IdPerfil')
			->select(
				'users.IdUsuario',
				'users.IdPerfil',
				'users.NombreCompleto',
				'perfiles.Nombre'
			)
            ->where('users.IdPerfil','=','2')
            ->orWhere('users.IdPerfil','=','4')
			->get();

			$data['PerfilGets']=$PerfilGet;

			return response([
				"status"    => true,
				"message"   => "success",
				"data"      => $data,
			]);

		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'getUsuarios');
		}
	}

    public function asignacionMasiva(Request $request){
		try
		{
			$params = $request->only(
				"IdUsuario",
				"arreglo",
                "arreglo2",
                "IdUsuario2"
			);


			## ELIMINO ASIGNACION DEL USUARIO 1
            ClientesxCobratario::query()
                ->where('IdUsuario', '=' ,$params['IdUsuario'])
                ->delete();


			$entregaxcliente = $params['arreglo'];

			foreach($entregaxcliente as $elemento)
			{
				$newArray = array(
					'IdCliente' => $elemento['IdCliente'],
					'IdUsuario' => $params['IdUsuario'],
				);

				$clientexcobratario = new ClientesxCobratario();
				if (!$clientexcobratario->insert($newArray)) return response([
					"status" => false,
					"message"   => "No se autorizó el prestamo."
				], 500);

			}


            ## ELIMINO ASIGNACION DEL USUARIO 2
			ClientesxCobratario::query()
                ->where('IdUsuario', '=' ,$params['IdUsuario2'])
                ->delete();

			$entregaxcliente2       = $params['arreglo2'];

            foreach($entregaxcliente2 as $elemento)
			{
				$Array = array(
					'IdCliente' => $elemento['IdCliente'],
					'IdUsuario' => $params['IdUsuario2'],
				);

				$clientexcobratario2 = new ClientesxCobratario();
				if (!$clientexcobratario2->insert($Array)) return response([
					"status" => false,
					"message"   => "No se autorizó el prestamo."
				], 500);

			}

			return response([
				"status" 	=> true,
				"message"   => "prestamo asignado.",
			], 201);

		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, $this->location,'asignacionMasiva');
		}
	}
}
