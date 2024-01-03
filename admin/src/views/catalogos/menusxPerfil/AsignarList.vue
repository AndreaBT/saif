<template>
    <CList :pConfigList="ConfigList" :segurity="segurity">
        <template slot="bodyForm">
            <CLoader :pConfigLoad="ConfigList">
                <template slot="BodyFormLoad">
                    <div class="form-row">
                        <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1">
                            <label class="col-form-label mr-1">Menus: </label>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                            <span class="form-inline">                                
                                <select v-model="MenuxPerfil.IdMenu" @change="ObtenerMenuActivo()" class="form-control form-select" style="width: 190px;">
                                    <option value="0">Seleccione una opción</option>
                                    <option v-for="(lista,index) in ListaMenus" :key="index" :value="lista.IdPanel">
                                        {{lista.Nombre}}
                                    </option>
                                </select>
                            </span>
                        </div>

                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3" v-show="ShowActiveMenu">
                            <button type="button" @click="ActivarMenu(ButtonActiveMenu.BtnStatus);" :class="ButtonActiveMenu.Class" :disabled="DisabledMenu">
                                <i :class="ButtonActiveMenu.Icon"></i> {{ButtonActiveMenu.TxtButton}}
                            </button>
                        </div>
                    </div>

                    <div class="row mt-5" v-show="MenuxPerfil.IdMenu == 0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                            <h1>Seleccione un Menú</h1>
                        </div>
                    </div>
                    
                    <div class="row mt-5" v-show="ShowActiveSubMenu">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation" v-show="ShowDivSubMenu">
                                    <a class="nav-link active" id="tab1-tab" @click="ActivaTab('tab1-tab')" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
                                        Sub Menu
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation" v-show="ShowDivSubMenu">
                                    <a class="nav-link" id="tab2-tab" @click="ActivaTab('tab2-tab')" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
                                        Apartados
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation" v-show="ShowDivSubMenu">
                                    <a class="nav-link" id="tab3-tab" @click="ActivaTab('tab3-tab')" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">
                                        Sub Apartados
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation" v-show="!ShowDivSubMenu">
                                    <a class="nav-link" id="tab4-tab" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab3" aria-selected="false">
                                        Permisos
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content shadow-sm" id="myTabContent" style="padding-bottom:0;">
                                <div class="tab-pane fade active show" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                                    <div class="container-fluid">
                                        <Dependiente :pArreglo="ListaSubMenus" :pObjMenu="MenuxPerfil" ref="child" :pShowDivSubMenu="ShowDivSubMenu" :pLimpiar="BndLimpia"></Dependiente>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                    <div class="container-fluid">
                                        <Dependiente :pArreglo="ListaApartados" :pObjMenu="MenuxPerfil" ref="child" :pShowDivSubMenu="ShowDivSubMenu" :pLimpiar="BndLimpia"></Dependiente>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                                    <div class="container-fluid">
                                        <Dependiente :pArreglo="ListaSubApartados" :pObjMenu="MenuxPerfil" ref="child" :pShowDivSubMenu="ShowDivSubMenu" :pLimpiar="BndLimpia"></Dependiente>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
                                    <div class="container-fluid">
                                        <Dependiente :pObjMenu="MenuxPerfil" ref="child" :pShowDivSubMenu="ShowDivSubMenu"></Dependiente>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </CLoader>
        </template>
    </CList>
</template>

<script>

    import Configs     from '@/helpers/VarConfig.js';
    import Dependiente from '@/views/catalogos/menusxPerfil/ViewDependiente.vue';
    const EmitEjecuta  = 'seccionPPP';

    export default {
        name:  'ListaPermxPerfil',
        props: ['pObjPerfil'],
        components: { 
            Dependiente
        },
        data() {
            return {
                ShowActiveMenu:     false,
                ShowActiveSubMenu:  false,
                ShowDivSubMenu:     true,
                DisabledMenu:       false,
                BndLimpia:          false,
                TabActivo:          'tab1-tab',
                ListaMenus:         [],
                ListaSubMenus:      [],
                ListaApartados:     [],
                ListaSubApartados:  [],
                segurity:           {},
                ObjPerfil:          {},
                ConfigList:{
                    Title:          'Permisos Por Perfil',
                    IsModal:        true,
                    ShowLoader:     true,
                    BtnReturnShow:  true,
                    EmitSeccion:    EmitEjecuta, 
                    BtnNewShow:     false,
                    ShowTitleFirst: false,
                    ShowSearch:     false,
                    ShowEntradas:   false,
                    ShowPaginador:  false,
                    TypeBody:       'Form'
                },
                oBtnSave: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitEjecuta,
                },
                MenuxPerfil:{
                    IdMenu:   0,
                    IdPerfil: 0,
                },
                ButtonActiveMenu:{
                    Class:      'btn btn-sm btn-success',
                    Icon:       'fa fa-check-circle',
                    TxtButton:  'Activar Menu',
                    BtnStatus:  1, // 1 para activar boton, 0 para desactivar boton
                },
            }
        },
        methods: {
            async ListarMenus() {
                this.ConfigList.ShowLoader = true;
                await this.$http.get('perfilesxmenus', {
                    params:{
                        IdPerfil: this.ObjPerfil.IdPerfil,
                        TipoMenu: 'Menu'
                    }
                }).then( (res) => {
                    this.ListaMenus = res.data.data;
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Regresar() {
                this.$router.push({name:'perfiles',params:{}});
            },
            // OBTENEMOS EL ESTATUS DEL BOTON DE MENUS PARA SABER SI ESTA ACTIVO O NO
            ObtenerMenuActivo() {
                let IdMenu             = this.MenuxPerfil.IdMenu
                this.ShowActiveMenu    = false;
                this.ShowActiveSubMenu = false;

                // SI HAY ALGUN MENU SELECCIONADO OBTENEMOS SU ARREGLO Y REVISAMOS SI SU BOTON ESTA ACTIVADO O DESACTIVADO
                if(IdMenu>0) {
                    this.ShowActiveMenu    = true;
                    this.ShowActiveSubMenu = true;
                    let ObjMenu            = this.FiltrarArreglo(IdMenu);

                    // SI TIENE EXISTE EL MENU ACTIVO ENTONCES SE SETEA COMO BOTON DESACTIVAR SINO COMO ACTIVAR
                    if(ObjMenu.Existe>0){
                        this.CambiaEstatusBotonMenu(0);
                    } else {
                        this.CambiaEstatusBotonMenu(1);
                    }

                } else {
                    this.ListaSubMenus     = [];
                    this.ListaApartados    = [];
                    this.ListaSubApartados = [];
                    this.BndLimpia         = true;
                    this.ShowActiveMenu    = false;
                    this.ShowActiveSubMenu = false;
                }
            },
            FiltrarArreglo(Id) {
                let arr = this.ListaMenus.filter(function(item,index){
                    if(item.IdPanel == Id){
                        return item;
                    } else {
                        return '';
                    }
                });

                if(arr[0]){
                    return arr[0];
                }

            },
            // ESTABLECEMOS LOS TEXTOS Y COLOR DEL BOTON DE MENUS
            CambiaEstatusBotonMenu(Tipo) {
                this.BndLimpia = true;
                // 1 PARA CUANDO SEA ACTIVAR EL BOTON Y 0 PARA CUANDO SEA DESACTIVAR EL BOTON
                if(Tipo==0) {
                    this.ButtonActiveMenu.Class     = 'btn btn-sm btn-danger';
                    this.ButtonActiveMenu.Icon      = 'fa fa-ban';
                    this.ButtonActiveMenu.TxtButton = 'Desactivar Menu';
                    this.ButtonActiveMenu.BtnStatus = 0;
                    this.ShowActiveSubMenu          = true;
                } else {
                    this.ButtonActiveMenu.Class     = 'btn btn-sm btn-success';
                    this.ButtonActiveMenu.Icon      = 'fa fa-check-circle';
                    this.ButtonActiveMenu.TxtButton = 'Activar Menu';
                    this.ButtonActiveMenu.BtnStatus = 1;
                    this.ShowActiveSubMenu          = false;
                }
                this.ObtenerDependientesMenu();
            },
            // MANDAMOS A GUARDAR EL MENU EN EL PERFIL
            ActivarMenu(accion) {
                var txtOrigen = this.ButtonActiveMenu.TxtButton;
                var icoOrigen = this.ButtonActiveMenu.Icon;
                this.DeshabilitarBotonMenu(true,txtOrigen,icoOrigen);

                let ObjSend = {
                    IdMenu:    this.MenuxPerfil.IdMenu,
                    IdPerfil:  this.MenuxPerfil.IdPerfil,
                    BtnAccion: accion,
                    TipoMenu:  'Menu'
                };

                this.$http.post('perfilesxmenus',ObjSend).then((res) =>{
                    this.DeshabilitarBotonMenu(false,txtOrigen,icoOrigen);

                    if(res.data.exist>0) {
                        if(accion == 0){
                            this.CambiaEstatusBotonMenu(1);
                        } else {
                            this.CambiaEstatusBotonMenu(0);
                        }
                    } else{
                        this.$toast.error(err.response.data.message);
                    }
                }).catch((err) => {
                    this.$toast.error(err.response.data.message);
                });
            },
            ObtenerDependientesMenu() {
                this.ShowDivSubMenu = true;
                this.$http.get('perfilesxmenusdependientes', {
                        params:{
                            IdMenu:   this.MenuxPerfil.IdMenu,
                            IdPerfil: this.MenuxPerfil.IdPerfil
                        }
                    }
                ).then( (res) =>{
                    this.ListaSubMenus     = res.data.SubMenus;
                    this.ListaApartados    = res.data.Apartados;
                    this.ListaSubApartados = res.data.SubApartados;

                    // SINO RECIBE NINGUN DEPENDIENTE, SOLO MUESTRA LOS PERMISOS DE ESE APARTADO
                    if(this.ListaSubMenus.length == 0 && this.ListaApartados.length == 0 && this.ListaSubApartados.length == 0) {
                        this.ShowDivSubMenu = false;
                        $('#tab4-tab').click();
                        let ObjMenu = this.FiltrarArreglo(this.MenuxPerfil.IdMenu);
                        this.$refs.child.MostrarPermisosxMenu(this.MenuxPerfil.IdMenu,ObjMenu);
                    } else {
                        $('#'+this.TabActivo).click();
                    }
                });
            },
            DeshabilitarBotonMenu(bnd,txt,ico) {
                this.DisabledMenu               = false;
                this.ButtonActiveMenu.TxtButton = txt;
                this.ButtonActiveMenu.Icon      = ico;

                if(bnd) {
                    this.DisabledMenu               = true;
                    this.ButtonActiveMenu.TxtButton = ' Espere...';
                    this.ButtonActiveMenu.Icon      = 'fa fa-spinner fa-pulse';
                }

            },
            ActivaTab(Tab) {
                this.TabActivo = Tab;
            }
        },
        created() {
            if(this.pObjPerfil != undefined){
                sessionStorage.setItem('oPerfil',JSON.stringify(this.pObjPerfil));
            }

            this.ObjPerfil            = JSON.parse(sessionStorage.getItem('oPerfil'));
            this.ConfigList.Title     = this.ConfigList.Title+': '+this.ObjPerfil.Nombre;
            this.MenuxPerfil.IdPerfil = this.ObjPerfil.IdPerfil;

            this.bus.$off('Delete_'+EmitEjecuta);
            this.bus.$off('List_'+EmitEjecuta);
            this.bus.$off('EmitReturn');
            this.bus.$off('RefrescaVista');
        },
        mounted() {
            this.ListarMenus();

            this.bus.$on('Delete_'+EmitEjecuta,(Id)=> {
                this.Eliminar(Id);
            });

            this.bus.$on('List_'+EmitEjecuta,()=> {
                this.Lista();
            });

            this.bus.$on('EmitReturn',()=> {
                this.Regresar();
            });

            this.bus.$on('RefrescaVista',()=> {
                this.ObtenerDependientesMenu();
            });
        },
    }

</script>