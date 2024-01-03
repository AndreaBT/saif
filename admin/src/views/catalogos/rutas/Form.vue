<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">

                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for="Nombre">Nombre Ruta</label>
                                    <input type="text" v-model="objRuta.NombreRuta"   maxlength="250" class="form-control" placeholder="Ingresar Nombre" />
                                    <CValidation v-if="this.errorvalidacion.NombreRuta" :Mensaje="'*'+errorvalidacion.NombreRuta[0]"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for="IdSucursal">Sucursal</label>
                                    <select id="IdSucursal" v-model="objRuta.IdSucursal" class="form-control form-select">
                                        <option :value="0">--Seleccionar--</option>
                                        <option v-for="(item, index) in ListaSucursalesArr" :key="index" :value="item.IdSucursal" >
                                            {{ item.Nombre }}
                                        </option>
                                    </select>
                                    <CValidation v-if="this.errorvalidacion.IdSucursal" :Mensaje="'*' + errorvalidacion.IdSucursal[0]"/>
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
        name:  "FormRuta",
        props: ['poBtnSave'],
        data() {
            return {
                Emit:               this.poBtnSave.EmitSeccion,
                errorvalidacion:    [],
                ListaSucursalesArr: [],
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
                objRuta:{
                    IdRuta:     0,
                    NombreRuta: '',
                    IdPais:     0,
                    IdEstado:   0,
                    IdSucursal: 0
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                if(this.objRuta.IdRuta == 0) {
                    this.$http.post('rutas', this.objRuta).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                } else {
                    this.$http.put('rutas/'+this.objRuta.IdRuta,this.objRuta).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                }
            },
            EjecutaConExito(res) {
                this.poBtnSave.DisableBtn = false;  
                this.poBtnSave.toast      = 1;
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
                this.$http.get("rutas/"+this.objRuta.IdRuta).then( (res) => {
                    this.objRuta = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.objRuta = {
                    IdRuta:     0,
                    NombreRuta: '',
                    IdPais:     0,
                    IdEstado:   0,
                    IdSucursal: 0
                };
                this.errorvalidacion = [];
            },
            async ListaSucursales() {
                await this.$http.get("sucursales", {
                    params: {
                        simple: 1
                    },
                })
                .then((res) => {
                    this.ListaSucursalesArr = res.data.data;
                });
            },
        },
        created() {
            this.poBtnSave.toast = 0;
            this.ListaSucursales();

            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(Id)=> {
                this.ConfigLoad.ShowLoader = true;    
                this.poBtnSave.DisableBtn  = false;

                this.bus.$off('Save_'+this.Emit);
                this.bus.$on('Save_'+this.Emit,()=> {
                    this.Guardar();
                });
                this.Limpiar();
                    
                if(Id!='') {
                    this.objRuta.IdRuta = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }
                
            });
        },
    }
    
</script>