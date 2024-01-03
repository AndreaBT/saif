<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use Illuminate\Http\Request;
use App\Models\TazaInteres;

use Exception;

class TazaInteresController extends Controller
{
    /***************************FUNCIONES CRUD**************************** */
	/*
     * API PARA LISTADO DE TODOS LAS TAZAS DE INTERÉS
     * METHOD: GET
    */

    public function findAll(Request $request){
        try{
            $search='';
            $multiWhere = array();

            if (!empty($request->get('TxtBusqueda'))) {
				$multiWhere[] = array("Nombre", "like", "%".  $request->get('TxtBusqueda')."%");
			}

            $TazaInteres = TazaInteres::where($multiWhere)
                ->paginate(
                    $request -> query("Entrada"),
                    "*",
                    "page",
                    $request -> query("Pag")
                );

            if (!empty($TazaInteres)) {
                
                $data['TazaInteres'] = $TazaInteres;

                return response([
                    "status" => true,
                    "message" => "Datos encontrados.",
                    "data"    => $data,
                ],200);

            } else {
                
                return response([
                    "status"  => false,
                    "message" => 'Sin registros',
                ], 404);

            }


        }catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }

    /*
     * API PARA LA BUSQUEDA DE TAZAS DE INTERÉS ESPECIFICOS DEL SISTEMA
     * METHOD: GET
    */

    public function findOne($id){
        try {
            $TazaInteres = TazaInteres::find($id);

            if( !empty($TazaInteres) ) {

                return response([
                    "status"    => true,
                    "message"   => "success",
                    "data"      => $TazaInteres,
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
    * API PARA MODIFICAR INFORMACIÓN DE UNA TAZAS DE INTERÉS EN ESPECIFICO
    * METHOD: PUT
    */

    public function update(int $IdTazainteres, Request $request){
        try {

			$params = $request->only(
               'Nombre',
               'Porcentaje'
            );

			$TazaInteresUpdate = TazaInteres::find($IdTazainteres);

			if (!empty($TazaInteresUpdate)) {

				$TazaInteresUpdate->Nombre      = $params['Nombre'];
                $TazaInteresUpdate->Porcentaje  = $params['Porcentaje'];
				

				if (!$TazaInteresUpdate->save()) return response([
					"status"  => false,
					"message" => 'Taza de Interés no actualizada.'
				], 500);

				$data['Tazainteres'] = $TazaInteresUpdate;

				return response([
					"status" => true,
					"message" => "Taza de Interés actualizada.",
					"data" => $data
				], 201);
			} else {

				return response([
					"status"  => true,
					"message" => 'Taza de Interés no encontrado.',
				], 400);
			}
		} catch(Exception $exception){
            return ResponseManager::setErrorServerResponse($exception);
        }
    }


}
