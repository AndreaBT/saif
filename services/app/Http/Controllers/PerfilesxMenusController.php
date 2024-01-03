<?php

namespace App\Http\Controllers;

use App\Custom\ResponseManager;
use App\Models\PanelMenu;
use App\Models\Perfil;
use App\Models\PerfilxMenu;
use App\Models\PerfilxPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

class PerfilesxMenusController extends Controller
{
    /*
     * API PARA LISTAR TODOS LOS MENUS EXISTENTES
     * METHOD: GET
    */
    public function findAll (Request $req)
    {
        try
        {
            $IdPerfil = $req->get('IdPerfil');
            $multiwhere = array();

            if(!empty($req->get('IdAsociado'))) {
                $multiwhere[] = array('IdAsociado','=',$req->get('IdAsociado'));
            }
            if(!empty($req->get('TipoMenu'))) {
                $multiwhere[] = array('TipoMenu','=',$req->get('TipoMenu'));
            }
            $Panel = PanelMenu::where($multiwhere)->get();

            $band = 0;
            foreach($Panel as $element)
            {
                $consulta = array(
                    array('IdPerfil','=',$IdPerfil),
                    array('IdPanel','=',$element->IdPanel),
                );
                $PerfilxMenu = PerfilxMenu::where($consulta)->first();

                $Existe = 0;
                if(!empty($PerfilxMenu)){
                    $Existe = 1;
                }

                $Panel[$band]->Existe = $Existe;
                $band++;
            }
            
            
            return response([
                "message" => "success",
                "data"    => $Panel,     
            ]);
        }
        catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
    }

    // FUNCION PARA AGREGAR O ELIMINAR EL MENU Y EL PERFIL EN PERFILESXMENU PD: SIRVE PARA MENU Y SUS DEPENDIENTES
    public function add(Request $request)
	{
		try
		{
			$params = $request->only(
				'IdMenu',
				'IdPerfil',
                'TipoMenu',
                'BtnAccion',
			);

            $IdMenu     = $params['IdMenu'];
            $IdPerfil   = $params['IdPerfil'];
            $TipoMenu   = $params['TipoMenu'];
            $BtnAccion  = $params['BtnAccion'];

            // SI RECIBIMOS UNA ACCION COMO 1 INSERTAMOS, SINO ELIMINAMOS Y ELIMINAMOS SUS PERMISOS DEPENDIENTES
            if($BtnAccion>0)
            {
                $newArray = array();
                $newArray['IdPerfil']   = $IdPerfil;
                $newArray['IdPanel']    = $IdMenu;

                $PerfilxMenu = new PerfilxMenu($newArray);

                if (!$PerfilxMenu->save()) return response([
					"status"    => false,
                    'exist'     => 0,
					"message"   => "Menu no asignado."
				], 500);

                return response([
                    "status" 	=> true,
                    'exist'     => 1,
                    "message"   => "Menu asignado.",
                    "data"		=> $PerfilxMenu
                ], 201);
            }
            else
            {
                $consulta = array(
                    array('IdPanel','=',$IdMenu),
                    array('IdPerfil','=',$IdPerfil),
                );
                $PerfilxMenu = PerfilxMenu::where($consulta)->delete();
                $this->deletePermisosSubMenu($IdMenu,$IdPerfil,$TipoMenu);

                return response([
                    "status" 	=> true,
                    'exist'     => 1,
                    "message"   => "Menu desasignado.",
                    "data"		=> $PerfilxMenu
                ], 201);
            }
		}
        catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
	}

    // PARA ELIMINAR LOS PERMISOS Y/O SUBEMUS DEPENDIENDO DE LA OPCION
    public function deletePermisosSubMenu($IdMenu,$IdPerfil)
    {
        $PanelMenu = PanelMenu::find($IdMenu);

        if(!empty($PanelMenu))
        {
            if($PanelMenu->TipoMenu == 'SubApartado')
            {
                // SI ES UN SUBAPARTADO ELIMINAMOS SUS PERMISOS Y LO ELIMINAMOS DE LOS MENUS ASIGNADOS
                $this->deleteMenusPermisos($IdMenu,$IdPerfil);
            }
            else if($PanelMenu->TipoMenu == 'Apartado')
            {
                // SI ES UN APARTADO BUSCAMOS SUS SUBAPARTADOS Y DESPUES ELIMINAMOS SUS PERMISOS
                $PanelMenuRow = $this->getArregloDependiente($IdMenu,'SubApartado');

                foreach($PanelMenuRow as $elemento)
                {
                    // ELIMINAMOS LOS SUBAPARTADOS
                    $this->deleteMenusPermisos($elemento->IdPanel,$IdPerfil);
                }

                // FINALMENTE ELIMINAMOS ESE APARTADO
                $this->deleteMenusPermisos($IdMenu,$IdPerfil);
            }
            else if($PanelMenu->TipoMenu == 'SubMenu')
            {
                // SI ES UN SUBMENU PRIMERO BUSCAMOS SUS APARTADOS
                $PanelMenuRow = $this->getArregloDependiente($IdMenu,'Apartado');

                // ITERAMOS Y BUSCAMOS SUS SUBAPARTADOS PARA ELIMINARLOS
                foreach($PanelMenuRow as $elemento)
                {
                    $PanelMenuRowSub = $this->getArregloDependiente($elemento->IdPanel,'SubApartado');
                    foreach($PanelMenuRowSub as $elementoSub)
                    {
                        // ELIMINAMOS LOS SUBAPARTADOS
                        $this->deleteMenusPermisos($elementoSub->IdPanel,$IdPerfil);
                    }

                    // ELIMINAMOS LOS APARTADOS
                    $this->deleteMenusPermisos($elemento->IdPanel,$IdPerfil);
                }

                // FINALMENTE ELIMINAMOS ESE SUBMENU
                $this->deleteMenusPermisos($IdMenu,$IdPerfil);
            }
            else if($PanelMenu->TipoMenu == 'Menu')
            {
                // SI ES UN MENU PRIMERO BUSCAMOS SUS SUBMENUS
                $PanelMenuRow = $this->getArregloDependiente($IdMenu,'SubMenu');

                // ITERAMOS Y BUSCAMOS SUS APARTADOS PARA ELIMINARLOS
                foreach($PanelMenuRow as $elemento)
                {
                    $PanelApartRow = $this->getArregloDependiente($elemento->IdPanel,'Apartado');

                    // ITERAMOS Y BUSCAMOS SUS SUBAPARTADOS PARA ELIMINARLOS
                    foreach($PanelApartRow as $elementoApa)
                    {
                        $PanelMenuRowSub = $this->getArregloDependiente($elementoApa->IdPanel,'SubApartado');
                        foreach($PanelMenuRowSub as $elementoSub)
                        {
                            // ELIMINAMOS LOS SUBAPARTADOS
                            $this->deleteMenusPermisos($elementoSub->IdPanel,$IdPerfil);
                        }
                        
                        // ELIMINAMOS LOS APARTADOS
                        $this->deleteMenusPermisos($elementoApa->IdPanel,$IdPerfil);
                    }

                    // ELIMINAMOS LOS SUBMENUS
                    $this->deleteMenusPermisos($elemento->IdPanel,$IdPerfil);
                }

                // FINALMENTE ELIMINAMOS ESE MENU
                $this->deleteMenusPermisos($IdMenu,$IdPerfil);
            }
        }
    }

    // DEVUELVE SOLO EL ARREGLO DE LOS DEPENDIENTES, PARA ITERAR Y ELIMINAR
    private function getArregloDependiente($IdMenu,$TipoMenu)
    {
        $search = array(
            array('IdAsociado','=',$IdMenu),
            array('TipoMenu','=',$TipoMenu),
        );
        $PanelMenuRow = PanelMenu::where($search)->get();
        return $PanelMenuRow;
    }

    // ELIMINA EL MENU DE PERFILESXMENU Y ELIMINA LOS PERMISOS DE ESE MENU
    private function deleteMenusPermisos($IdMenu,$IdPerfil)
    {
        $condition = array(
            array('IdPerfil','=',$IdPerfil),
            array('IdPanel','=',$IdMenu),
        );
        // ELIMINAMOS EL MENU DE LOS PERFILES X MENU
        $deleteSub = PerfilxMenu::where($condition)->delete();

        // ELIMINAMOS LOS PERMISOS ASIGNADOS A ESE MENU
        $deletePerm = PerfilxPermiso::where($condition)->delete();
    }

    // FUNCION PARA TRAER TODOS LOS SUBMENUS, APARTADOS Y SUBAPARTADOS DE UN MENU, ASI COMO SU ESTATUS EN PERFILXMENU
    public function getMenusDependientes(Request $req)
    {
        try
        {
            $IdMenu = $req->get('IdMenu');
            $IdPerfil = $req->get('IdPerfil');

            // OBTENEMOS PRIMERO LOS SUBMENUS DEPENDIENTES DEL MENU
            $where = array(
                array('IdMenu','=',$IdMenu),
                array('TipoMenu','=','SubMenu'),
            );
            $RowSubMenu = PanelMenu::where($where)->get();
            $RowSubMenu = $this->getEstatusependiente($RowSubMenu,$IdPerfil);

            // OBTENEMOS LOS APARTADOS DEPENDIENTES DE LOS SUBMENUS BASADOS EN EL MENU
            $whereAp = array(
                array('IdMenu','=',$IdMenu),
                array('TipoMenu','=','Apartado'),
            );

            $RowApartados = PanelMenu::where($whereAp)->get();
            $RowApartados = $this->getEstatusependiente($RowApartados,$IdPerfil);


            // OBTENEMOS LOS SUBAPARTADOS DEPENDIENTES DE LOS APARTADOS BASADOS EN EL MENU
            $whereSubAp = array(
                array('IdMenu','=',$IdMenu),
                array('TipoMenu','=','SubApartado'),
            );

            $RowSubApartados = PanelMenu::where($whereSubAp)->get();
            $RowSubApartados = $this->getEstatusependiente($RowSubApartados,$IdPerfil);
            
            return response([
                "status" 	    => true,
                "message"       => "Dependientes Encontrados.",
                "SubMenus"      => $RowSubMenu,
                "Apartados"     => $RowApartados,
                "SubApartados"  => $RowSubApartados,
            ], 201);
        }
        catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
    }

    // SIRVE SOLO PARA ITERAR Y FORMAR LA ESTRUCTURA DEL BOTON ACTIVADO O DESACTIVADO
    public function getEstatusependiente($Arreglo,$IdPerfil)
    {
        $band = 0;
        foreach($Arreglo as $elemento)
        {
            // REVISA EL DEPENDIENTE Y SI EXISTE EN PERFILESXMENUS - ESTATUS DEL BTN ACTIVO
            $whereItem = array(
                array('IdPerfil','=',$IdPerfil),
                array('IdPanel','=',$elemento->IdPanel),
            );
            $PerfilxMenu = PerfilxMenu::where($whereItem)->first();

            $activo     = false;
            $btnStyle1  = 'btn btn-sm btn-success';
            $btnTexto1  = 'Activar';
            $btnIcono1  = 'fa fa-lock';
            $btnActiv1  = 1;

            if(!empty($PerfilxMenu))
            {
                $activo     = true;
                $btnStyle1  = 'btn btn-sm btn-danger';
                $btnTexto1  = 'Desactivar';
                $btnIcono1  = 'fa fa-ban';
                $btnActiv1  = 0;
            }

            $Arreglo[$band]->BtnArray = array(
                'BtnStyle'  => $btnStyle1,
                'BtnTexto'  => $btnTexto1,
                'BtnIcono'  => $btnIcono1,
                'BtnActivo' => $btnActiv1,
                'Existe'    => $activo
            );
            $band++;
        }

        return $Arreglo;
    }

    // FUNCION PARA TRAER TODOS LOS PERMISOS X MENU (PANELESXPERMISOS) Y LOS PERMISOS X PERFIL (PERFILXPERMISOS)
    public function getPermisosxMenus(Request $req)
    {
        try
        {
            $IdMenu = $req->get('IdMenu');
            $IdPerfil = $req->get('IdPerfil');

            $where = array(
                array('IdPanel','=',$IdMenu),
            );

            $Permisos = DB::table('panelesxpermisos AS pp')
            ->join('permisos AS p', 'p.IdPermiso', '=', 'pp.IdPermiso')
            ->select(
                'pp.IdPermiso',
                'pp.IdPanel',
                'Nombre',
                'Clave'
            )->where($where)->get();

            $wherePxP = array(
                array('IdPanel','=',$IdMenu),
                array('IdPerfil','=',$IdPerfil),
            );

            $PermisosxPerfil = PerfilxPermiso::where($wherePxP)->get();

            return response([
                "status" 	    => true,
                "message"       => "Dependientes Encontrados.",
                "Permisos"      => $Permisos,
                'PermisoxPerfil'=> $PermisosxPerfil
            ], 201);
        }
        catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
    }
    
    // AGREGAMOS Y ELIMINAMOS LOS PERMISOS X PUESTO
    public function addPermisosxPerfil(Request $req)
    {
        try
        {
            $IdMenu = $req->get('IdMenu');
            $IdPerfil = $req->get('IdPerfil');
            $Arreglo = $req->get('ArrayPermisos');

            // ELIMINAMOS TODOS LOS PERMISOS QUE TENGA ASIGNADO
            $where = array(
                array('IdPerfil','=',$IdPerfil),
                array('IdPanel','=',$IdMenu),
            );
            $delete = PerfilxPermiso::where($where)->delete();

            // SI RECIBIMOS ELEMENTOS EN EL ARRAY LOS INSERTAMOS
            $PerfilxPermiso = '';

            if(count($Arreglo)>0)
            {
                foreach($Arreglo as $elemento)
                {
                    $newArray = array();
                    $newArray['IdPerfil']   = $IdPerfil;
                    $newArray['IdPanel']    = $IdMenu;
                    $newArray['IdPermiso']  = $elemento;
                    $PerfilxPermiso = new PerfilxPermiso($newArray);

                    if (!$PerfilxPermiso->save()) return response([
                        "status" => false,
                        "message"   => "Permiso no asignado."
                    ], 500);
                }
            }

            return response([
                "status" 	=> true,
                "message"   => "Permisos asignados.",
                "data"		=> $PerfilxPermiso
            ], 201);   
        }
        catch (Exception $exception) {
			return ResponseManager::setErrorServerResponse($exception, true);
		}
    }
}