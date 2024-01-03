<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label for="Nombre">Permiso</label>
                                    <input type="text" v-model="objPermiso.Nombre"   maxlength="250" class="form-control" placeholder="Ingresar Permiso" />
                                    <CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*'+errorvalidacion.Nombre[0]"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label for="Nombre">Clave</label>
                                    <input type="text" v-model="objPermiso.Clave"   maxlength="250" class="form-control" placeholder="Ingresar Clave" />
                                    <CValidation v-if="this.errorvalidacion.Clave" :Mensaje="'*'+errorvalidacion.Clave[0]"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </template>
    </CLoader>
</template>

<script>

    export default {
        name:  "FormMontoCredito",
        props: ['poBtnSave'],
        data() {
            return {
                Emit:            this.poBtnSave.EmitSeccion,
                errorvalidacion: [],
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
                objPermiso:{
                    IdPermiso: 0,
                    Clave:     '',
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                if(this.objPermiso.IdPermiso == 0) {
                    this.$http.post('permisos',this.objPermiso).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                } else {
                    this.$http.put('permisos/'+this.objPermiso.IdPermiso, this.objPermiso).then((res)=>{                 
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
                this.$http.get("permisos/"+this.objPermiso.IdPermiso).then( (res) => {
                    this.objPermiso = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.objPermiso = {
                    IdPermiso: 0,
                    Clave:     '',
                };
                this.errorvalidacion = [];
            },
        },
        created() {
            this.poBtnSave.toast = 0;

            this.bus.$off('Recovery_'+this.Emit);

            this.bus.$on('Recovery_'+this.Emit,(Id)=> {
                this.ConfigLoad.ShowLoader = true;    
                this.poBtnSave.DisableBtn  = false;

                this.bus.$off('Save_'+this.Emit);
                this.bus.$on('Save_'+this.Emit,() => {
                    this.Guardar();
                });

                this.Limpiar();
                    
                if(Id!='') {
                    this.objPermiso.IdPermiso = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }
            });
        },
    }

</script>