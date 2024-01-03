<?php

namespace App\Http\Controllers;

use App\Models\PanelMenu;
use App\Models\PanelxPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelMenuController extends Controller {
    
    public function findAll (Request $req) {
        try
        {
            if ($req->simple > 0) {
                $multiwhere = array();
                if(!empty($req->get('IdAsociado'))) {
                    $multiwhere[] = array('IdAsociado','=',$req->get('IdAsociado'));
                }
                if(!empty($req->get('TipoMenu'))) {
                    $multiwhere[] = array('TipoMenu','=',$req->get('TipoMenu'));
                }
                $Panel = PanelMenu::where($multiwhere)->get();
            } else {
                $multiwhere = array();

                if(!empty($req->get('TxtBusqueda'))) {
                    $multiwhere[] = array('pm.Nombre','like','%' . $req->get('TxtBusqueda') . '%');
                }
                if(!empty($req->get('IdAsociado'))) {
                    $multiwhere[] = array('pm.IdAsociado','=',$req->get('IdAsociado'));
                }
                if(!empty($req->get('TipoMenu'))) {
                    $multiwhere[] = array('pm.TipoMenu','=',$req->get('TipoMenu'));
                }

                $Panel = DB::table('panelesmenus AS pm')
                ->leftJoin('panelesmenus AS pm2', 'pm2.IdPanel', '=', 'pm.IdAsociado')
                ->select(
                    'pm.IdPanel',
                    'pm.Nombre',
                    'pm.TipoMenu',
                    'pm2.Nombre AS MenuAsociado'
                )
                ->where($multiwhere)
                ->paginate(
                    $req->query("Entrada"),
                    "*",
                    "page",
                    $req->query("Pag")
                );

                /* $Panel = PanelMenu::where($multiwhere)->paginate(
                    $req->query("Entrada"),
                    "*",
                    "page",
                    $req->query("Pag")
                ); */
            }
            
            return response([
                "message" => "success",
                "data"    => $Panel,     
            ]);
        }
        catch (\Exception $element)
        {
            return response([
                "status" => false,
                "mesage" => 'Internal Error',
                "error"  => $element->getMessage(),
            ], 500);
        }
    }

    public function findOne($IdPanel) {  
        try
        {
            $Panel = PanelMenu::find($IdPanel);
            
            if (!empty($Panel))
            {
                $PanelMenus = '';
                $PanelSubMenus = '';
                $PanelApartados = '';

                if($Panel->TipoMenu != 'Menu'){
                    $PanelMenus = PanelMenu::where('TipoMenu','=','Menu')->get();
                }

                if($Panel->TipoMenu == 'Apartado' || $Panel->TipoMenu == 'SubApartado')
                {
                    $where = array(
                        array('TipoMenu','=','SubMenu'),
                        array('IdMenu','=',$Panel->IdMenu)
                    );
                    $PanelSubMenus = PanelMenu::where($where)->get();
                }

                if($Panel->TipoMenu == 'SubApartado')
                {
                    $where = array(
                        array('TipoMenu','=','Apartado'),
                        array('IdSubMenu','=',$Panel->IdSubMenu)
                    );
                    $PanelApartados = PanelMenu::where($where)->get();
                }

                return response([
                    "status"    => true,
                    "message"   => "success",
                    "data"      => $Panel,
                    'Menus'     => $PanelMenus,
                    'SubMenus'  => $PanelSubMenus,
                    'Apartados' => $PanelApartados,
                ]);
            } else {  
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 400);
            }
        }
        catch (\Exception $element)
        {
            return response([
                "status"  => false,
                "message" => 'Internal Error',
                "error"   => $element->getMessage(),
            ], 500); 
        }
    }

    public function add(Request $req) {
        try
        {
            $ReqData    = $req->only(
                'Nombre',
                'TipoMenu',
                'IdMenu',
                'IdSubMenu',
                'IdApartado',
                'IdAsociado',
                'Clave',
                'Lugar'
            );

            $validation = $this->validator($ReqData);

            if ($validation->fails()) return response([
                "message" => 'parametros invalidos',
                "errors"  => $validation->errors()
            ], 400);

            $newAdd = new PanelMenu($ReqData);

            if (!$newAdd->save()) return response([
                "status"  => false,
                "message" => 'El registro no se creó',
            ], 500);

            return response([
                "status"  => true,
                "message" => false,
                "data"    => $newAdd
            ], 201);
        }
        catch (\Exception $element)
        {
            return response([
                "status"  => false,
                "message" => 'Internal Error',
                "error"   => $element->getMessage(),
            ], 500);
        }
    }

    public function update(Request $req, int $IdPanel) {
        try
        {
            $ReqData    = $req->only(
                'Nombre',
                'TipoMenu',
                'IdMenu',
                'IdSubMenu',
                'IdApartado',
                'IdAsociado',
                'Clave',
                'Lugar'
            );
            $validation = $this->validator($ReqData);

            if ($validation->fails()) return response([
                "status" => "parametros invalidos",
                "errors"  => $validation->errors()
            ], 400);

            $update = PanelMenu::find($IdPanel);

            if (!empty($update))
            {
                $update->Nombre     = $ReqData['Nombre'];
                $update->TipoMenu   = $ReqData['TipoMenu'];
                $update->IdMenu     = $ReqData['IdMenu'];
                $update->IdSubMenu  = $ReqData['IdSubMenu'];
                $update->IdApartado = $ReqData['IdApartado'];
                $update->IdAsociado = $ReqData['IdAsociado'];
                $update->Clave      = $ReqData['Clave'];
                $update->Lugar      = $ReqData['Lugar'];

                if (!$update->save()) return response([
                    "status"  => false,
                    "message" => 'Update Error'
                ], 500);

                return response([
                    "status"  => true,
                    "message" => 'success',
                    "data"    => $update
                ], 200);
            }
            else
            {
                return response([
                    "status"  => true,
                    "message" => 'Registro no encontrado',
                ], 400);
            }
        }
        catch (\Exception $element)
        {
            return response([
                "status"  => false,
                "message" => 'Internal Error!',
                "error"   => $element->getMessage(),
            ],500);
        }
    }

    public function delete($IdPanel) {    
        try
        {   
            $respose = PanelMenu::find($IdPanel);

            if (!empty($respose))
            { 
                $exist = PanelxPermiso::where('IdPanel', '=', $IdPanel)->first();

                if(empty($exist))
                {
                    if ($respose->delete())
                    {
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
                }
                else
                {
                    return response([
                        "status"  => false,
                        "message" => 'El registro no puede ser eliminado porque esta en uso.',
                    ], 400);
                }
            }
            else
            {
                return response([
                    "status"  => false,
                    "message" => 'Registro no encontrado',
                ], 400);
            }
        }
        catch (\Exception $element)
        {
            return response([
                "status"  => false,
                "message" => 'Internal_error',
                'error'   => $element->getMessage(),
            ],500);
        }
    }

    public function validator($params = []) {
        $required = [
            'Nombre'       => "required|string",
            'TipoMenu'     => "required|string",
            'Lugar'        => "required|string",
        ];
        
        if($params['TipoMenu'] == 'SubMenu'){
            $required+=['IdMenu' => 'required|numeric|min:1'];
        }
        else if($params['TipoMenu'] == 'Apartado'){
            $required+=['IdMenu' => 'required|numeric|min:1'];
            $required+=['IdSubMenu' => 'required|numeric|min:1'];
        }
        else if($params['TipoMenu'] == 'SubApartado'){
            $required+=['IdMenu' => 'required|numeric|min:1'];
            $required+=['IdSubMenu' => 'required|numeric|min:1'];
            $required+=['IdApartado' => 'required|numeric|min:1'];
        }
        return validator($params, $required,[
            'required'  => "El Campo es requerido",
            'min'       => 'Debe seleccionar una opción.',
            ]
        );
    }
}