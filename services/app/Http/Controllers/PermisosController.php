<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;

class PermisosController extends Controller {
    
    public function findAll (Request $req) {

        try
        {
            if ($req->simple > 0) {
                $Permiso = Permiso::get();
            } else {
                $multiwhere = array();

                if(!empty($req->get('TxtBusqueda'))) {
                    $multiwhere[] = array('Nombre','like','%' . $req->get('TxtBusqueda') . '%');
                }

                $Permiso = Permiso::where($multiwhere)->paginate(
                    $req->query("Entrada"),
                    "*",
                    "page",
                    $req->query("Pag")
                );
            }
            
            return response([
                "message" => "success",
                "data"    => $Permiso,     
            ]);
        }
        catch (\Exception $element){
            return response([
                "status" => false,
                "mesage" => 'Internal Error',
                "error"  => $element->getMessage(),
            ], 500);
        }
    }

    public function findOne($IdPermiso) {
        
        try
        {
            $Permiso = Permiso::find($IdPermiso);
            
            if (!empty($Permiso))
            {
                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $Permiso
                ]);
            } else {
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 400);
            }
        } catch (\Exception $element) {
            return response([
                "status"  => false,
                "message" => 'Internal Error',
                "error"   => $element->getMessage(),
            ], 500); 
        }
    }

    public function add(Request $req) {
        
        try {
            
            $ReqData    = $req->only('Nombre','Clave');
            $validation = $this->validator($ReqData);

            if ($validation->fails()) return response([
                "message" => 'parametros invalidos',
                "errors"  => $validation->errors()
            ], 400);

            $newAdd = new Permiso($ReqData);

            if (!$newAdd->save()) return response([
                "status"  => false,
                "message" => 'El registro no se creó',
            ], 400);

            return response([
                "status"  => true,
                "message" => false,
                "data"    => $newAdd
            ], 201);

        } catch (\Exception $element){

            return response([
                "status"  => false,
                "message" => 'Internal Error',
                "error"   => $element->getMessage(),
            ], 500);

        }

    }

    public function update(Request $req, int $IdPermiso) {

        try {

            $ReqData    = $req->only('Nombre','Clave');
            $validation = $this->validator($ReqData);

            if ($validation->fails()) return response([
                "message" => 'parametros invalidos',
                "errors"  => $validation->errors()
            ], 400);

            $update = Permiso::find($IdPermiso);

            if (!empty($update))
            {
                
                $update->Nombre = $ReqData['Nombre'];

                if (!$update->save()) return response([
                    "status"  => false,
                    "message" => 'Update Error'
                ], 500);

                return response([
                    "status"  => true,
                    "message" => 'success',
                    "data"    => $update
                ], 200);

            } else {
                
                return response([
                    "status"  => true,
                    "message" => 'Registro no encontrado',
                ], 400);

            }

        } catch (\Exception $element) {

            return response([
                "status"  => false,
                "message" => 'Internal Error!',
                "error"   => $element->getMessage(),
            ],500);

        }

    }

    public function delete($IdPermiso) {
        
        try {
            
            $respose = Permiso::find($IdPermiso);

            if (!empty($respose)) { 

                if ($respose->delete()) {

                    return response([
                        "status"  => true,
                        "message" => 'Registro Eliminado'
                    ], 200);

                } else {

                    return response([
                        "status"  => false,
                        "message" => 'delete Error',
                    ], 500);

                }

            } else {
            
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 400);

            }

        } catch (\Exception $element){

            return response([
                "status"  => false,
                "message" => 'Internal_error',
                'error'   => $element->getMessage(),
            ],500);

        }

    }

    public function validator ($params = []) {
        return validator($params, 
            ['Nombre'   => "required|string",
             'Clave'    => "required|string"],
            ['required' => "El Campo es requerido"]
        );
    }
}
