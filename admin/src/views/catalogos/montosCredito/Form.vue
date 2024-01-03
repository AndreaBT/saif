<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label for="Nombre">Monto</label>
                                    <input type="text" v-model="objMonto.Monto"   maxlength="250" class="form-control" placeholder="Ingresar Nombre" />
                                    <label v-if="this.errorvalidacion.Monto"><CValidation :Mensaje="'*'+errorvalidacion.Monto[0]"></CValidation></label>
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
                objMonto:{
                    IdPrestamoMonto: 0,
                    Monto:           '',
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                if(this.objMonto.IdPrestamoMonto == 0) {
                    this.$http.post('prestamosmontos',this.objMonto).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                } else {
                    this.$http.put('prestamosmontos/'+this.objMonto.IdPrestamoMonto,this.objMonto).then((res)=>{                 
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
                this.$http.get("prestamosmontos/"+this.objMonto.IdPrestamoMonto).then( (res) => {
                    this.objMonto = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.objMonto = {
                    IdPrestamoMonto: 0,
                    Monto:           '',
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
                    this.objMonto.IdPrestamoMonto = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }
                
            });
        },
    }
    
</script>