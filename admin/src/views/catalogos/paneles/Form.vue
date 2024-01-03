<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">

                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <label for="Nombre">Nombre</label>
                                    <input type="text" v-model="objMenus.Nombre" maxlength="250" class="form-control" placeholder="Ingresar Nombre" />
                                    <CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*'+errorvalidacion.Nombre[0]"/>
                                </div>

                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <label>Posición</label>
                                    <select v-model="objMenus.Lugar" class="form-control form-select">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Izquierdo">Izquierdo</option>
                                        <option value="Derecho">Derecho</option>
                                    </select>
                                    <CValidation v-if="this.errorvalidacion.Lugar" :Mensaje="'*'+errorvalidacion.Lugar[0]"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <label for="Clave">Clave</label>
                                    <input type="text" v-model="objMenus.Clave" maxlength="250" class="form-control" placeholder="Ingresar Clave" />
                                    <CValidation v-if="this.errorvalidacion.Clave" :Mensaje="'*'+errorvalidacion.Clave[0]"/>
                                </div>
                                
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                    <label>Tipo de Item</label>
                                    <select v-model="objMenus.TipoMenu" @change="ObtenerMenus()" class="form-control form-select">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Menu">Menu</option>
                                        <option value="SubMenu">Sub Menu</option>
                                        <option value="Apartado">Apartado</option>
                                        <option value="SubApartado">Sub Apartado</option>
                                    </select>
                                    <CValidation v-if="this.errorvalidacion.TipoMenu" :Mensaje="'*'+errorvalidacion.TipoMenu[0]"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4" v-show="objMenus.TipoMenu == 'SubMenu' || objMenus.TipoMenu == 'Apartado' || objMenus.TipoMenu == 'SubApartado'">
                                    <label>Menus</label>
                                    <select v-model="objMenus.IdMenu" @change="ObtenerSubMenus()" class="form-control form-select">
                                        <option value="0">Seleccione una opción</option>
                                        <option v-for="(item,index) in ArrayMenus" :key="index" :value="item.IdPanel">
                                            {{item.Nombre}}
                                        </option>
                                    </select>
                                    <CValidation v-if="this.errorvalidacion.IdMenu" :Mensaje="'*'+errorvalidacion.IdMenu[0]"/>
                                </div>

                                <div class="col-12 col-sm-12 col-md-4 col-lg-4" v-show="objMenus.TipoMenu == 'Apartado' || objMenus.TipoMenu == 'SubApartado'">
                                    <label>Sub Menus</label>
                                    <select v-model="objMenus.IdSubMenu" @change="ObtenerApartados()" class="form-control form-select">
                                        <option value="0">Seleccione una opción</option>
                                        <option v-for="(item,index) in ArraySubMenus" :key="index" :value="item.IdPanel">
                                            {{item.Nombre}}
                                        </option>
                                    </select>
                                    <CValidation v-if="this.errorvalidacion.IdSubMenu" :Mensaje="'*'+errorvalidacion.IdSubMenu[0]"/>
                                </div>

                                <div class="col-12 col-sm-12 col-md-4 col-lg-4" v-show="objMenus.TipoMenu == 'SubApartado'">
                                    <label>Apartado</label>
                                    <select v-model="objMenus.IdApartado" class="form-control form-select">
                                        <option value="0">Seleccione una opción</option>
                                        <option v-for="(item,index) in ArrayApartados" :key="index" :value="item.IdPanel">
                                            {{item.Nombre}}
                                        </option>
                                    </select>
                                    <CValidation v-if="this.errorvalidacion.IdApartado" :Mensaje="'*'+errorvalidacion.IdApartado[0]"/>
                                </div>
                            </div>

                        </div><!--fin col-12-->
                    </div>
                </div>
            </form>
        </template>
    </CLoader>
</template>

<script>

    export default {
        name:  "FormPerfil",
        props: ['poBtnSave'],
        data() {
            return {
                errorvalidacion: [],
                ArrayMenus:      [],
                ArraySubMenus:   [],
                ArrayApartados:  [],
                ListaPaneles:    [],
                Emit:            this.poBtnSave.EmitSeccion,
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
                objMenus:{
                    IdPanel:    0,
                    Nombre:     '',
                    TipoMenu:   '',
                    IdAsociado: 0,
                    Clave:      '',
                    Lugar:      '',
                    IdMenu:     0,
                    IdSubMenu:  0,
                    IdApartado: 0
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                if(this.objMenus.TipoMenu == 'SubMenu'){
                    this.objMenus.IdAsociado = this.objMenus.IdMenu;
                } else if (this.objMenus.TipoMenu == 'Apartado'){
                    this.objMenus.IdAsociado = this.objMenus.IdSubMenu;
                }

                if(this.objMenus.TipoMenu == 'SubApartado'){
                    this.objMenus.IdAsociado = this.objMenus.IdApartado;
                }

                if(this.objMenus.IdPanel == 0) {
                    this.$http.post('panelmenu',this.objMenus).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                } else {
                    this.$http.put('panelmenu/'+this.objMenus.IdPanel,this.objMenus).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                }
            },
            EjecutaConExito(res) {
                this.poBtnSave.DisableBtn = false;  
                this.poBtnSave.toast = 1;
                this.bus.$emit('CloseModal_'+this.Emit);
                this.bus.$emit('List_'+this.Emit);
            },
            EjecutaConError(err) {
                this.poBtnSave.DisableBtn = false;
                
                if(err.response.data.errors){
                    this.errorvalidacion = err.response.data.errors;
                    this.poBtnSave.toast = 2;
                } else {
                    this.$toast.error(err.response.data.message);
                }
            },
            Recuperar() {
                this.$http.get("panelmenu/"+this.objMenus.IdPanel).then( (res) => {
                    this.ArrayMenus     = res.data.Menus;
                    this.ArraySubMenus  = res.data.SubMenus;
                    this.ArrayApartados = res.data.Apartados;
                    this.objMenus       = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.objMenus = {
                    IdPanel:    0,
                    Nombre:     '',
                    TipoMenu:   '',
                    IdAsociado: 0,
                    Clave:      '',
                    Lugar:      '',
                    IdMenu:     0,
                    IdSubMenu:  0,
                    IdApartado: 0
                };
                this.errorvalidacion = [];
            },
            async RecuperaMenus() {            
                await this.$http.get('panelmenu', {
                    params:{
                        simple: 1,
                    }
                }).then( (res) => {
                    this.ListaPaneles = res.data.data;
                });
            },
            ObtenerMenus() {
                let Buscar = this.objMenus.TipoMenu;

                if(Buscar != 'Menu' && Buscar!=''){
                    let resp = this.FiltrarAsociados('Menu',0);
                    this.ArrayMenus = resp;
                } else {
                    this.ArrayMenus      = [];
                    this.objMenus.IdMenu = 0;
                }

                this.ObtenerSubMenus();
            },
            ObtenerSubMenus() {
                let IdMenu              = this.objMenus.IdMenu;
                this.objMenus.IdSubMenu = 0;

                if(IdMenu >0) {
                    let resp           = this.FiltrarAsociados('SubMenu',IdMenu);
                    this.ArraySubMenus = resp;
                } else {
                    this.ArraySubMenus = [];
                }

                this.ObtenerApartados();
            },
            ObtenerApartados() {
                let IdSubMenu            = this.objMenus.IdSubMenu;
                this.objMenus.IdApartado = 0;

                if(IdSubMenu >0) {
                    let resp = this.FiltrarAsociados('Apartado',IdSubMenu);
                    this.ArrayApartados = resp;
                } else {
                    this.ArrayApartados = [];
                }
            },
            FiltrarAsociados(TipoItem,IdAsociado) {
                let arr = this.ListaPaneles.filter(function(item){

                    if(item.TipoMenu == TipoItem){

                        if(TipoItem != 'Menu') {

                            if(item.IdAsociado == IdAsociado) {
                                return item;
                            }

                        } else {
                            return item;
                        }

                    } else {
                        return '';
                    }
                });

                return arr;
            },
        },
        created() {
            this.poBtnSave.toast = 0;

            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(Id)=> {
                this.ConfigLoad.ShowLoader = true;    
                this.poBtnSave.DisableBtn = false;

                this.bus.$off('Save_'+this.Emit);

                this.bus.$on('Save_'+this.Emit,()=> {
                    this.Guardar();
                });

                this.Limpiar();
                this.RecuperaMenus();
                    
                if(Id!='')  {
                    this.objMenus.IdPanel = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }
                
            });
        },
    }

</script>