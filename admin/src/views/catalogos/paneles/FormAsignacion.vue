<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
            
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Permisos</h3>
                                    <draggable id="div1" class="droppable bg-feed" :list="ListaPermisos" group="rutas" @change="log">
                                        <div class="list-group-item" v-for="(element, index) in ListaPermisos" :key="element.Nombre">
                                            {{ index+1 }} {{ element.Nombre }} 
                                        </div>
                                    </draggable>
                                </div>

                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Asignados</h3>
                                    <draggable id="div2" class="droppable" :list="ListaAsignados" group="rutas" @change="log">
                                        <div class="list-group-item" v-for="(element, index) in ListaAsignados" :key="element.Nombre">
                                            {{ index+1 }} {{ element.Nombre }} 
                                        </div>
                                    </draggable>
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
        name:"FormAsig",
        props:['poBtnSave'],
        components:{
        },
        data() {
            return {
                Emit:            this.poBtnSave.EmitSeccion,
                errorvalidacion: [],
                ArrayPermisos:   [],
                ListaPermisos:   [],
                ListaAsignados:  [],
                objPanel:        {},
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
            }
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0; 
                this.poBtnSave.DisableBtn = true;

                let objSend = {
                    IdPanel: this.objPanel.IdPanel,
                    arreglo: this.ListaAsignados,
                };

                this.$http.post('panelesxpermisos', objSend).then((res)=>{                 
                    this.EjecutaConExito(res);
                }).catch(err=>{   
                    this.EjecutaConError(err);
                });
            },
            EjecutaConExito(res) {
                this.poBtnSave.DisableBtn = false;  
                this.poBtnSave.toast      = 1;
                this.bus.$emit('CloseModal_'+this.Emit);
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
                this.$http.get("panelesxpermisosinner", {
                    params: {
                        IdPanel: this.objPanel.IdPanel
                    },
                }).then( (res) => {
                    this.ListaAsignados = res.data.data;

                    this.ListaAsignados.forEach((value, index) => {

                        const newArray = this.ArrayPermisos.filter(function(item) {
                            
                            if(item.IdPermiso == value.IdPermiso){
                                return '';
                            } else {
                                return item;
                            }

                        });
                        
                        this.ArrayPermisos = newArray;
                    });

                    this.ListaPermisos = this.ArrayPermisos;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.errorvalidacion = [];
            },
            async ListaPermiso() {
                await this.$http.get("permisos", {
                    params: {
                        simple: 1
                    },
                })
                .then((res) => {
                    this.ArrayPermisos = res.data.data;
                });
            },
        },
        created() {
            this.poBtnSave.toast = 0;
            this.ListaPermiso();
            
            this.bus.$off('Recovery_'+this.Emit);

            this.bus.$on('Recovery_'+this.Emit,(item) => {
                
                this.ConfigLoad.ShowLoader = true;    
                this.poBtnSave.DisableBtn = false;

                this.bus.$off('Save_'+this.Emit);
                this.bus.$on('Save_'+this.Emit,() => {
                    this.Guardar();
                });

                this.Limpiar();
                this.objPanel = item;
                this.Recuperar();
            });
            
        },
    }
    
</script>