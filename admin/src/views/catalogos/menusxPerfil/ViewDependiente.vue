<template>
    <div class="row justify-content-between">
        <input type="hidden" :value="Validate">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 bg-feed border-right" v-if="ShowDivSubMenu">
            <div class="list-group m-4">
                <h4 class="mb-3">Descripción</h4>
                <div class="list-group-item list-group-item-action" v-for="(lista,index) in ListaDependiente" :key="index">                    
                    <div class="form-row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 text-left">
                            <label class="col-form-label">{{lista.Nombre}}</label>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-1 text-right">
                            <button v-show="lista.BtnArray.Existe" type="button" @click="MostrarPermisosxMenu(lista.IdPanel,lista);" class="btn btn-sm btn-success mr-2">
                                <i class="far fa-eye"></i>
                                <span class="tiptext"> Permisos</span>
                            </button>

                            <button type="button" @click="ActivaDependiente(lista.BtnArray,lista.IdPanel);" :class="lista.BtnArray.BtnStyle" :disabled="DisabledSMAP">
                                <i :class="lista.BtnArray.BtnIcono"></i> {{lista.BtnArray.BtnTexto}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 bg-feed">
            <CLoader :pConfigLoad="ConfigLoad" class="mt-4">
                <template slot="BodyFormLoad">
                    <h4 class="mb-3">Permisos: {{NombreDependiente}}</h4>
                    <div class="row" v-for="(data,index) in ListaPermisosxMenu" :key="index">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-left">
                            <div class="list-group2">
                                <div class="list-group-item2 list-group-item-action">
                                    <b-form-checkbox switch size="lg" name="check-button" v-model="ListaPermisosChecked" :value="data.IdPermiso" style="padding-top: 1px;">
                                        {{data.Nombre}}
                                    </b-form-checkbox>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row" v-if="ListaPermisosxMenu.length>0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                            <button type="button" @click="GuardarPermisosxPerfil(ListaPermisosxMenu[0].IdPanel);" class="btn btn-primary mb-2" :disabled="ButtonSavePermiso.DisabledSave">
                                <i :class="ButtonSavePermiso.Icono"></i> {{ButtonSavePermiso.TxtButton}}
                            </button>
                        </div>
                    </div>
                </template>
            </CLoader>
        </div>
    </div>
</template>

<script>

export default {
    name: 'ViewDependiente',
    props:['pArreglo','pObjMenu','pShowDivSubMenu','pLimpiar'],
    components: { 
    },
    data() {
        return {
            NombreDependiente:      '',
            DisabledSMAP:           false,
            ShowDivSubMenu:         true,
            MenuxPerfil:            {},
            ListaDependiente:       [],
            ListaPermisosxMenu:     [],
            ListaPermisosChecked:   [],
            ConfigLoad:{
                ShowLoader: true,
                ClassLoad:  false,
            },
            ButtonSavePermiso: {
                TxtButton:      'Guardar',
                Icono:          'fa fa-plus-circle',
                DisabledSave:   false,
            },
        }
    },
    methods: {
        // MANDAMOS A GUARDAR EL SUB MENU, APARTADOS O SUBAPARTADO DEL PERFIL Y CAMBIAMOS ESTATUS DE LOS BOTONES
        ActivaDependiente(obj,IdMenu) {
            this.LimpiarPermisos();
            var txtOrigen = obj.BtnTexto;
            var icoOrigen = obj.BtnIcono;
            this.DeshabilitarBotonSubMenu(true,txtOrigen,icoOrigen,obj);

            let ObjSend = {
                IdMenu:    IdMenu,
                IdPerfil:  this.MenuxPerfil.IdPerfil,
                BtnAccion: obj.BtnActivo,
                TipoMenu:  'SubMenu'
            };

            this.$http.post('perfilesxmenus', ObjSend).then( (res) =>{
                this.DeshabilitarBotonSubMenu(false,txtOrigen,icoOrigen,obj);
                
                if(res.data.exist>0) {
                    if(obj.BtnActivo == 1)
                    {
                        obj.BtnTexto  = 'Desactivar';
                        obj.BtnIcono  = 'fa fa-ban';
                        obj.BtnStyle  = 'btn btn-sm btn-danger';
                        obj.Existe    = true;
                        obj.BtnActivo = 0;
                    } else {
                        obj.BtnTexto  = 'Activar';
                        obj.BtnIcono  = 'fa fa-lock';
                        obj.BtnStyle  = 'btn btn-sm btn-success';
                        obj.Existe    = false;
                        obj.BtnActivo = 1;
                    }
                } else {
                    this.$toast.error(err.response.data.message);
                }
            }).catch((err) => {
                this.DeshabilitarBotonSubMenu(false,txtOrigen,icoOrigen,obj);
                this.$toast.error(err.response.data.message);
			}).finally(()=>{
                this.bus.$emit('RefrescaVista');
            });
        },
        // MOSTRAMOS EL LISTADO DE PERMISOS DE CADA SUBMENU O APARTADO
        MostrarPermisosxMenu(IdMenu,item) {
            this.NombreDependiente     = item.Nombre;
            this.ConfigLoad.ShowLoader = true;
            // LIMPIAMOS LOS SELECCIONADOS PARA Q NO SE QUEDEN ALMACENADOS.
            this.LimpiarPermisos();
            this.$http.get('perfilesxmenuspermisos', {
                params:{
                    IdMenu: IdMenu,
                    IdPerfil: this.MenuxPerfil.IdPerfil
                }
            }).then( (res) =>{
                this.ListaPermisosxMenu = res.data.Permisos;
                
                res.data.PermisoxPerfil.forEach((value, index) => {
                    this.ListaPermisosChecked.push(value.IdPermiso)
                });

            }).finally(()=>{
                this.ConfigLoad.ShowLoader = false;
            });
        },
        // GUARDAMOS TODOS LOS PERMISOS
        GuardarPermisosxPerfil(IdMenu) {
            this.DeshabilitarBotonGuardar(true);
            this.$http.get('perfilesxmenusaddpermisos', {
                    params:{
                        IdMenu:        IdMenu,
                        IdPerfil:      this.MenuxPerfil.IdPerfil,
                        ArrayPermisos: this.ListaPermisosChecked
                    }
            }).then( (res) =>{
                this.DeshabilitarBotonGuardar(false);
                this.$toast.success('Información guardada');
            }).catch((err) => {
                this.DeshabilitarBotonGuardar(false);
                this.$toast.error(err.response.data.message);
			});
        },
        // LIMPIAMOS LOS ARREGLOS DE PERMISOS Y PERMISOS CHECADOS
        LimpiarPermisos() {
            this.ListaPermisosxMenu   = [];
            this.ListaPermisosChecked = [];
        },
        DeshabilitarBotonSubMenu(bnd,txt,ico,obj) {
            this.DisabledSMAP = false;
            obj.BtnTexto      = txt;
            obj.BtnIcono      = ico;

            if(bnd) {
                this.DisabledSMAP = true;
                obj.BtnTexto      = ' Espere...';
                obj.BtnIcono      = 'fa fa-spinner fa-pulse';
            }

        },
        DeshabilitarBotonGuardar(bnd) {
            this.ButtonSavePermiso.DisabledSave = false;
            this.ButtonSavePermiso.TxtButton    = 'Guardar';
            this.ButtonSavePermiso.Icono        = 'fa fa-plus-circle';

            if(bnd) {
                this.ButtonSavePermiso.DisabledSave = true;
                this.ButtonSavePermiso.TxtButton    = ' Espere...';
                this.ButtonSavePermiso.Icono        = 'fa fa-spinner fa-pulse';
            }

        }
    },
    computed: {
        Validate() {
            if(this.pArreglo!=undefined){
                this.ListaDependiente = this.pArreglo;
            }
            if(this.pObjMenu != undefined){
                this.MenuxPerfil = this.pObjMenu;
            }
            if(this.pShowDivSubMenu != undefined){
                this.ShowDivSubMenu = this.pShowDivSubMenu;

                if(this.ShowDivSubMenu){
                    this.ConfigLoad.ShowLoader = false;
                }

            }
            if(this.pLimpiar) {
                this.NombreDependiente = '';
                this.LimpiarPermisos();
            }

            return this.MenuxPerfil.IdPerfil;
        }
    }
}
</script>