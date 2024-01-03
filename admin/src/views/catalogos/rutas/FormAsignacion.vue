<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false"> 
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Empleados</h3>
                                    <draggable id="div1" class="droppable bg-feed" :list="ListaEmpleados" group="rutas" @change="log">
                                        <div class="list-group-item" v-for="(element, index) in ListaEmpleados" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }} 
                                        </div>
                                    </draggable>
                                </div>

                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Asignados</h3>
                                    <draggable id="div2" class="droppable" :list="ListaAsignados" group="rutas" @change="log">
                                        <div class="list-group-item" v-for="(element, index) in ListaAsignados" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }} 
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
        name:  "FormAsig",
        props: ['poBtnSave'],
        data() {
            return {
                Emit:            this.poBtnSave.EmitSeccion,
                errorvalidacion: [],
                ListUsersArr:    [],
                ListaEmpleados:  [],
                ListaAsignados:  [],
                objRuta:         {},
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
                    IdRuta:  this.objRuta.IdRuta,
                    arreglo: this.ListaAsignados,
                };

                this.$http.post('rutasxusuarios',objSend).then((res)=>{                 
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
                this.$http.get("rutasxusuariosinner", {
                    params: {
                        IdRuta: this.objRuta.IdRuta
                    },
                }).then( (res) => {
                    this.ListaAsignados = res.data.data;

                    this.ListaAsignados.forEach((value, index) => {
                        const newArray = this.ListUsersArr.filter(function(item){
                            if(item.IdUsuario == value.IdUsuario){
                                return '';
                            } else {
                                return item;
                            }
                        });
                        
                        this.ListUsersArr = newArray;
                    });

                    this.ListaEmpleados = this.ListUsersArr;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.errorvalidacion = [];
            },
            async ListaUsuarios() {
                await this.$http.get("rutasxusuariosUsers",)
                .then((res) => {
                    this.ListUsersArr = res.data.data;
                });
            },
        },
        created() {
            this.poBtnSave.toast = 0;
            this.ListaUsuarios();
            
            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(item)=> {
                
                this.ConfigLoad.ShowLoader = true;    
                this.poBtnSave.DisableBtn  = false;

                this.bus.$off('Save_'+this.Emit);
                this.bus.$on('Save_'+this.Emit,()=> {
                    this.Guardar();
                });

                this.Limpiar();
                
                this.objRuta = item;
                this.Recuperar();
            });
        },
    }
    
</script>