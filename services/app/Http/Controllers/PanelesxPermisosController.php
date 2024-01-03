<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\PanelxPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

class PanelesxPermisosController extends Controller
{
	/*
	 * Funciones CRUD
	 *
	 */

	/*
     * API PARA LISTADO DE TODOS LOS PANELES POR PERMISOS
     * METHOD: GET
     */
	public function findAll(Request $request)
	{
		try
		{
			$multiWhere = array();

			if(!empty($request->get('IdPanel'))) {
				$multiWhere[] = array("IdPanel", '=', $request->get('IdPanel'));
			}
			if(!empty($request->get('IdPermiso'))) {
				$multiWhere[] = array("IdPermiso", '=', $request->get('IdPermiso'));
			}

			$panelPermiso = PanelxPermiso::where(function ($query){
				$query->join('permisos', 'permisos.IdPermiso', '=', 'panelesxpermisos.IdPermiso');
			})->where($multiWhere)->paginate(
				$request->query("Entrada"),
				"*",
				"page",
				$request->query("Pag")
			);

			if(!empty($panelPermiso)) {
				return response([
					"status" 	=> true,
					"message"	=> "Panel encontrado.",
					"data"		=> $panelPermiso,
				], 200);
			}
			else
			{
				return response([
					"status" 	=> true,
					"message"	=> "Datos no encontrados.",
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA LA BÚSQUEDA DE PANEL X PERMISO ESPECÍFICA
     * METHOD: GET
     */
	public function findOne($IdPanelxPermiso)
	{
		try {

			$panelxPermiso = PanelxPermiso::find($IdPanelxPermiso);

			if(!empty($panelxPermiso))
			{
				return response([
					"status"  => true,
					"message" => "Panel encontrado.",
					"data"    => $panelxPermiso
				], 200);
			} else {

				return response([
					"status"  => false,
					"message" => 'Panel no encontrado.',
				], 404);
			}
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA AGREGAR PANEL X PERMISO AL SISTEMA
     * METHOD: POST
     */
	public function add(Request $request)
	{
		try
		{
			$params = $request->only(
				'IdPanel',
				'arreglo',
			);
			
			//$user = DB::table('panelesxpermisos')->where('IdPanel', $params['IdPanel'])->delete();
			$user = PanelxPermiso::where('IdPanel', $params['IdPanel'])->delete();
			
			$panelesxPermisos = '';
			foreach($params['arreglo'] as $elemento)
			{
				$newArray = array();
				$newArray['IdPanel'] = $params['IdPanel'];
				$newArray['IdPermiso'] = $elemento['IdPermiso'];
				$panelesxPermisos = new PanelxPermiso($newArray);

				if (!$panelesxPermisos->save()) return response([
					"status" => false,
					"message"   => "Permiso no asignado."
				], 500);
			}

			return response([
				"status" 	=> true,
				"message"   => "Permisos asignados.",
				"data"		=> $panelesxPermisos
			], 201);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     * API PARA JOIN DE PERMISOS CON PANELES X PERMISOS
     * METHOD: GET
	*/
	public function findAllInner(Request $request)
	{
		try {
			$IdPanel = $request->get('IdPanel');

			$panelesxpermisos = DB::table('panelesxpermisos')
			->join('permisos', 'permisos.IdPermiso', '=', 'panelesxpermisos.IdPermiso')
			->select(
				'panelesxpermisos.IdPanelxPermiso',
				'permisos.Nombre',
				'permisos.IdPermiso'
			)
			->where('panelesxpermisos.IdPanel', '=', $IdPanel)
			->get();

			return response([
				"status"    => true,
				"message"   => "success",
				"data"      => $panelesxpermisos,
			]);
		} catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

	/*
     ********************** FUNCIONES DE VALIDACIÓN *********************************
     * 
	*/
}
