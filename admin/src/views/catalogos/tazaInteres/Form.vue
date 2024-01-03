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
                                    <input type="text" v-model="objTaza.Nombre"   maxlength="250" class="form-control" placeholder="Ingresar Nombre" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for="Porcentaje">Porcentaje</label>
                                    <input type="text" v-model="objTaza.Porcentaje"   maxlength="250" class="form-control" placeholder="Ingresar Porcentaje" />
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
        components:{
        },
        data() {
            return {
                ListaSucursalesArr: [],
                Emit:               this.poBtnSave.EmitSeccion,
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
                objTaza:{
                    IdTazainteres: 0,
                    Nombre:        '',
                    Porcentaje:    0,
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                    this.$http.put('updateTazaInteres/'+this.objTaza.IdTazainteres,this.objTaza).then((res)=>{                 
                        this.EjecutaConExito(res);
                    }).catch(err=>{   
                        this.EjecutaConError(err);
                    });
                
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
                this.$http.get("getOneTazaInteres/"+this.objTaza.IdTazainteres).then( (res) => {
                    this.objTaza = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.objTaza = {
                    IdTazainteres: 0,
                    Nombre:        '',
                    Porcentaje:    0,
                };
            },
            
        },
        created() {
            this.poBtnSave.toast = 0;

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
                    this.objTaza.IdTazainteres = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }
                
            });
        },
    }

</script>