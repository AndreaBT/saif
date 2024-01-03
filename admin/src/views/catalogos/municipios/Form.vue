<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for="Nombre">Nombre</label>
                                    <input type="text" v-model="objMunicipio.Nombre"   maxlength="250" class="form-control" placeholder="Ingresar Nombre" />
                                    <label v-if="this.errorvalidacion.Nombre"><CValidation :Mensaje="'*'+errorvalidacion.Nombre[0]"></CValidation></label>
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
        name:  "FormMunicipios",
        props: ['poBtnSave'],
        data() {
            return {
                Emit:            this.poBtnSave.EmitSeccion,
                errorvalidacion: [],
                ObjEstado:       {},
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
                objMunicipio:{
                    IdMunicipio: 0,
                    IdEstado:    0,
                    Nombre:      '',
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                if(this.objMunicipio.IdMunicipio == 0) {
                    this.$http.post('municipios',this.objMunicipio).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                } else {
                    this.$http.put('municipios/'+this.objMunicipio.IdEstado,this.objMunicipio).then((res)=>{                 
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
                }
                else{
                    this.$toast.error(err.response.data.message);
                }
            },
            Recuperar() {
                this.$http.get(
                    "municipios/"+this.objMunicipio.IdMunicipio
                ).then( (res) => {
                    this.objMunicipio = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.objMunicipio = {
                    IdMunicipio: 0,
                    IdEstado: this.ObjEstado.IdEstado,
                    Nombre: '',
                };
                this.errorvalidacion = [];
            },
        },
        created() {
            this.ObjEstado = JSON.parse(sessionStorage.getItem('oEstado'));

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
                    
                if(Id!='') {
                    this.objMunicipio.IdMunicipio = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }
                
            });
        },
    }

</script>