<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;
use App\Models\User;

class PerfilesController extends Controller {
    
    public function findAll (Request $req) {
        try {
            if ($req->simple > 0) {
                $Perfil = Perfil::get();
            } else {
                $multiwhere = array();

                if(!empty($req->get('TxtBusqueda'))) {
                    $multiwhere[] = array('Nombre','like','%' . $req->get('TxtBusqueda') . '%');
                }

                $Perfil = Perfil::where($multiwhere)->paginate(
                    $req->query("Entrada"),
                    "*",
                    "page",
                    $req->query("Pag")
                );
            }

            $data['perfiles'] = $Perfil;
            
            return response([
                "message" => "success",
                "data"    => $data,     
            ]);
        } catch (\Exception $element){
            return response([
                "status" => false,
                "mesage" => 'Internal Error',
                "error"  => $element->getMessage(),
            ], 500);
        }
    }

    public function findOne($IdPerfil) {  
        try {
            $Perfil = Perfil::find($IdPerfil);
            
            if (!empty($Perfil)) {        
                return response([
                    "status"  => true,
                    "message" => "success",
                    "data"    => $Perfil
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
            //'IdPerfil',
            $ReqData    = $req->only('Nombre');
            $validation = $this->validator($ReqData);

            if ($validation->fails()) return response([
                "message" => 'parametros invalidos',
                "errors"  => $validation->errors()
            ], 400);

            $newAdd = new Perfil($ReqData);

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

    public function update(Request $req, int $IdPerfil) {
        try {
            $ReqData    = $req->only('Nombre');
            $validation = $this->validator($ReqData);

            if ($validation->fails()) return response([
                "status" => "parametros invalidos",
                "errors"  => $validation->errors()
            ], 400);

            $update = Perfil::find($IdPerfil);

            if (!empty($update)) {
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

    public function delete($IdPerfil) {    
        try {
           
            $respose = Perfil::find($IdPerfil);

            if (!empty($respose)) { 
                $exist = User::where('IdPerfil', '=', $IdPerfil)->first();

                if(empty($exist)) {
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
                        "message" => 'El registro no puede ser eliminado porque esta en uso.',
                    ], 400);
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

    public function validator($params = []) {
        return validator($params, 
            ['Nombre'   => "required|string"],
            ['required' => "El Campo es requerido"]
        );
    }
}