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
                                    <input type="text" v-model="objEstados.Nombre"   maxlength="250" class="form-control" placeholder="Ingresar Nombre" />
                                    <label v-if="this.errorvalidacion.Nombre"><CValidation :Mensaje="'*'+errorvalidacion.Nombre[0]"></CValidation></label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <input type="hidden" v-model="objEstados.IdPais" value="1">
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
        name:"FormEstados",
        props:['poBtnSave'],
        data() {
            return {
                errorvalidacion: [],
                Emit:            this.poBtnSave.EmitSeccion,
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
                objEstados:{
                    IdEstado: 0,
                    IdPais:   1,
                    Nombre:   '',
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                if(this.objEstados.IdEstado == 0) {
                    this.$http.post('estados',this.objEstados).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                } else {
                    this.$http.put(
                        'estados/'+this.objEstados.IdEstado,
                        this.objEstados 
                    ).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                }

            },
            EjecutaConExito(res)
            {
                this.poBtnSave.DisableBtn = false;  
                this.poBtnSave.toast = 1;
                this.bus.$emit('CloseModal_'+this.Emit);
                this.bus.$emit('List_'+this.Emit);
            },
            EjecutaConError(err)
            {
                this.poBtnSave.DisableBtn = false;
                
                if(err.response.data.errors){
                    this.errorvalidacion = err.response.data.errors;
                    this.poBtnSave.toast = 2;
                }
                else{
                    this.$toast.error(err.response.data.message);
                }
            },
            Recuperar()
            {
                this.$http.get(
                    "estados/"+this.objEstados.IdEstado
                ).then( (res) => {
                    this.objEstados = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar()
            {
                this.objEstados = {
                    IdEstado: 0,
                    IdPais: 1,
                    Nombre: '',
                };
                this.errorvalidacion = [];
            },
        },
        created() {
            this.poBtnSave.toast = 0;

            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(Id)=>
            {
                this.ConfigLoad.ShowLoader = true;    
                this.poBtnSave.DisableBtn = false;

                this.bus.$off('Save_'+this.Emit);
                this.bus.$on('Save_'+this.Emit,()=>
                {
                    this.Guardar();
                });
                this.Limpiar();
                    
                if(Id!='') 
                {
                    this.objEstados.IdEstado = Id;
                    this.Recuperar();
                }
                else
                {
                    this.ConfigLoad.ShowLoader = false;
                }
            });
        },
    }

</script>