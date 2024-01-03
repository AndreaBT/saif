<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <label for="item19">Entregador</label>
                <select class="form-control form-select" name="Perfil" v-model="IdEntregador" @change="Lista()">
                    <option value="0">--Seleccionar--</option>
                    <option v-for="(item, index) in listEntregadores" :key="index" :value="item.IdUsuario" >{{item.NombreCompleto}}</option>
                </select>
                <label>
                    <CValidation v-if="this.errorvalidacion.IdUsuario" :Mensaje="'*'+errorvalidacion.IdUsuario[0]"/>
                </label>
            </div>
            <br>

            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Retornar</h3>
                                   <draggable id="div2" class="droppable" :list="listSinAsignar" group="rutas" >
                                        <div class="list-group-item" v-for="(element, index) in listSinAsignar" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }}
                                        </div>
                                    </draggable>
                                    <label>
                                        <CValidation v-if="this.errorvalidacion.arreglo" :Mensaje="'*'+errorvalidacion.arreglo[0]"/>
                                    </label>
                                </div>
                                 <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <h3>Asignados a Entrega</h3>
                                   <draggable id="div1" class="droppable bg-feed" :list="listaAsignados" group="rutas">
                                        <div class="list-group-item" v-for="(element, index) in listaAsignados" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }}
                                        </div>
                                    </draggable>
                                </div>
                            </div>


                        </div><!--fin col-12-->
                    </div>
                </div>
                <!-- <button type="button" @click="Guardar()">hola</button> -->
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
            ConfigLoad:{
                ShowLoader: false,
                ClassLoad: false,
            },
            errorvalidacion: [],
            listEntregadores: [],
            listSinAsignar: [],
            listaAsignados: [],
            IdEntregador: 0,

            Filtro: {
                Nombre: "",
                Pagina: 1,
                Entrada: 10,
                TotalItem: 0,
                Placeholder: "Buscar..",
            },

            Emit: this.poBtnSave.EmitSeccion,
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
            this.ConfigLoad.ShowLoader = true;
            await this.$http.get("prestamosasignado", {
                params: {
                    txtBusqueda: this.Filtro.Nombre,
                    Entrada: this.Filtro.Entrada,
                    Pag: this.Filtro.Pagina,
                    EstatusPrestamo: 'Asignado',
                    IdUsuario: this.IdEntregador,
                    isSimple:1
                },

            }).then((res) => {
                this.listaAsignados     = res.data.data;


            }).finally(() => {
                this.ConfigLoad.ShowLoader = false;
            });
        },

        Guardar(){

            this.errorvalidacion = [];
            this.poBtnSave.toast = 0;
            this.poBtnSave.DisableBtn = true;

            let newRequest = {
                IdUsuario: this.IdEntregador,
                arreglo: this.listSinAsignar
            }

             this.$http.post('retornaprestamosautorizado', newRequest,).then((res)=>{
                this.EjecutaConExito(res);
                this.Lista();
            }).catch(err=>{
                this.EjecutaConError(err);
            });
        },

        EjecutaConExito(res) {
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





    },
    created() {
        this.poBtnSave.toast = 0;
        this.ListaPerfil();
        this.bus.$off('Save_'+this.Emit);

    },
    mounted(){
        this.poBtnSave.DisableBtn = false;
        this.bus.$on('Save_'+this.Emit,()=>
        {
            this.Guardar();
        });
    }


}

</script>
