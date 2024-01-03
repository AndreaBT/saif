<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label for="item19">Entregador</label>
                <select class="form-control form-select" name="Perfil" v-model="IdEntregador">
                    <option value="0">--Seleccionar--</option>
                    <option v-for="(item, index) in listEntregadores" :key="index" :value="item.IdUsuario" >{{item.NombreCompleto}}</option>
                </select>
                <label>
                    <CValidation v-if="this.errorvalidacion.IdUsuario" :Mensaje="'*'+errorvalidacion.IdUsuario[0]"/>
                </label>
            </div>
            <br>
            <form class="form-horizontal" autocomplete="off" onSubmit="return false">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Sin Asignar</h3>
                                    <draggable id="div1" class="droppable bg-feed" :list="listSinAsignar" group="rutas">
                                        <div class="list-group-item" v-for="(element, index) in listSinAsignar" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }}
                                        </div>
                                    </draggable>
                                </div>

                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Asignados para Entrega</h3>
                                    <draggable id="div2" class="droppable" :list="listaAsignados" group="rutas" >
                                        <div class="list-group-item" v-for="(element, index) in listaAsignados" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }}
                                        </div>
                                    </draggable>
                                    <label>
                                        <CValidation v-if="this.errorvalidacion.arreglo" :Mensaje="'*'+errorvalidacion.arreglo[0]"/>
                                    </label>
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

    import CValidation from '@/components/CValidation.vue';
    import CLoader     from "../../../../components/CLoader";

    export default {
        name:  "FormAsig",
        props: ['poBtnSave'],
        components:{
            CValidation,
            CLoader
        },
        data() {
            return {
                IdEntregador:     0,
                Emit:             this.poBtnSave.EmitSeccion,
                errorvalidacion:  [],
                listEntregadores: [],
                listSinAsignar:   [],
                listaAsignados:   [],
                ConfigLoad:{
                    ShowLoader: false,
                    ClassLoad:  false,
                },
                Filtro: {
                    Nombre:      "",
                    Pagina:      1,
                    Entrada:     10,
                    TotalItem:   0,
                    Placeholder: "Buscar..",
                },
            }
        },
        methods : {
            async ListaPerfil() {
                await this.$http.get('getusersbyperfil', {
                    params:{
                        IdPerfil: 3
                    }
                }).then( (res) => {
                    this.listEntregadores = res.data.data;
                });
            },
            async Lista() {
                this.listSinAsignar        = [];
                this.ConfigLoad.ShowLoader = true;
                await this.$http.get("clientesPreAutorizados", {
                        params: {
                            Estatus:         'Activo',
                            EstatusPrestamo: 'PreAutorizado'
                        },
                    })
                    .then((res) => {
                        this.listSinAsignar = res.data.data;
                    })
                    .finally(() => {
                        this.ConfigLoad.ShowLoader = false;
                    });
            },
            Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0;
                this.poBtnSave.DisableBtn = true;

                let newRequest = {
                    IdUsuario: this.IdEntregador,
                    arreglo:   this.listaAsignados
                }

                this.$http.post('PrestamoAutorizar', newRequest).then((res)=>{
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

                if(err.response.data.errors) {
                    this.errorvalidacion = err.response.data.errors;
                    this.poBtnSave.toast = 2;
                }
                else{
                    this.$toast.error(err.response.data.message);
                }
            },
        },
        created() {
            this.poBtnSave.toast = 0;
            this.ListaPerfil();
            this.Lista();

            this.bus.$off('Recovery_'+this.Emit);

            this.bus.$on('Recovery_'+this.Emit,(item)=>
            {
                this.poBtnSave.DisableBtn = false;
                this.bus.$off('Save_'+this.Emit);
                this.bus.$on('Save_'+this.Emit,()=> {
                    this.Guardar();
                });


            });
        },
    }

</script>
