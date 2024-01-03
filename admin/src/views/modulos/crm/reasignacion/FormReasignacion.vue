<template>
	<div>
		<CList :pConfigList="ConfigList" >
            <template slot="bodyForm">

                <div class="row">
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label for="item19">Gestor - Supervisor</label>
                        <select v-model="IdUsuario" class="form-control form-select" name="Perfil" @change="Lista();">
                            <option value="0">--Seleccionar--</option>
                            <option v-for="(item, index) in listEntregadores" :key="index" :value="item.IdUsuario" >{{item.NombreCompleto}} - {{item.NomPerfil}}</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 float-right">
                        <label for="item19">Gestor - Supervisor</label>
                        <select v-model="IdUsuario2" class="form-control form-select" name="Perfil2"  @change="Lista2();" >
                            <option value="0">--Seleccionar--</option>
                            <option v-for="(item, index) in listEntregadores" :key="index" :value="item.IdUsuario" >{{item.NombreCompleto}} - {{item.NomPerfil}}</option>
                        </select>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-row">
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <!-- <h3 v-if="Nombre==''  || Nombre=='--Seleccionar--' " >Seleccione un Gestor o Supervisor</h3>
                                <h3 v-if="Nombre!='' && Nombre!='--Seleccionar--'" >{{Nombre}}</h3> -->
                                <h3>Cliente</h3>
                            <draggable  id="div2" class="droppable" :list="listClientesUsr1" group="rutas" >
                                    <div class="list-group-item" v-for="(element, index) in listClientesUsr1" :key="element.NombreCompleto">
                                        {{ index+1 }} {{ element.ClienteNombre }}
                                    </div>
                                </draggable>
                            </div>
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <!-- <h3 v-if="Nombre2=='' || Nombre2=='--Seleccionar--' "  >Seleccione un Gestor o Supervisor</h3>
                                <h3 v-if="Nombre2!='' && Nombre2!='--Seleccionar--'" >{{Nombre2}}</h3> -->
                                <h3>Cliente</h3>
                            <draggable  id="div1" class="droppable bg-feed" :list="listClientesUsr2" group="rutas">
                                    <div class="list-group-item" v-for="(element, index) in listClientesUsr2" :key="element.NombreCompleto">
                                        {{ index+1 }} {{ element.ClienteNombre }}
                                    </div>
                                </draggable>
                            </div>
                        </div>


                    </div><!--fin col-12-->
                </div>

                 <div class="row mt-2">
                    <div class="col-12 text-right">
                        <CBtnSave :poBtnSave="oBtnSave" />
                    </div>
                </div>
            </template>
		</CList>
    </div>
</template>

<script>
import Configs from "@/helpers/VarConfig.js";
import CValidation from '@/components/CValidation.vue'
const EmitEjecuta = "seccionCliente";

export default {
	name: "ListaClientes",
	components: {CValidation},
	data() {
		return {
			ConfigList: {
				Title: 'Reasignación',
                ShowLoader: false,
                IsModal: false,
                BtnReturnShow: false,
                ShowSearch: false,
                ShowPaginador: false,
                ShowEntradas: false,
                BtnNewShow: false,
                TypeBody: 'Form',
                ShowTitleFirst: false,
                EmitSeccion: EmitEjecuta,
                TitleFirst:     'Menú',
			},
            Filtro: {
				Nombre: "",
				Pagina: 1,
				Entrada: 10,
				TotalItem: 0,
				Placeholder: "Buscar..",
			},
            oBtnSave: {
				toast: 0,
				IsModal: false,
				ShowBtnSave: true,
				ShowBtnCancel: false,
                EmitSeccion: EmitEjecuta,
			},


            listEntregadores:[],
            IdUsuario:0,
            IdUsuario2:0,
            listClientesUsr1:[],
            listClientesUsr2:[],
		};
	},
	methods: {

        async ListaPerfil() {
            await this.$http.get('getusersbyperfil', {
                params:{
                    IdPerfil: [2,4],
                    InIdPerfil:1
                }
            }).then( (res) => {
                this.listEntregadores = res.data.data;
            });
        },

        async Lista() {
            this.listClientesUsr1 = [];
            await this.$http
                .get("getClientesxCobratario", {
                    params: {
                        IdUsuario:this.IdUsuario,
                        isSimple: 1,
                    },
                })
                .then((res) => {
                    this.listClientesUsr1 =res.data.data.Activos;
                });
        },

        async Lista2() {
            this.listClientesUsr2 = [];
            await this.$http
                .get("getClientesxCobratario", {
                    params: {
                        IdUsuario:this.IdUsuario2,
                        isSimple: 1,
                    },
                })
                .then((res) => {
                    this.listClientesUsr2 =res.data.data.Activos;
                });
        },


        Guardar(){

            this.errorvalidacion=[];
            this.oBtnSave.toast = 0;
            this.oBtnSave.DisableBtn = true;

            let newRequest = {
                IdUsuario: this.IdUsuario,
                arreglo: this.listClientesUsr1,
                IdUsuario2: this.IdUsuario2,
                arreglo2: this.listClientesUsr2,
            }

             this.$http.post('asignacionMasiva',newRequest).then((res)=>{
                this.EjecutaConExito(res);
                this.Lista();
                this.Lista2();
            }).catch(err=>{
                this.EjecutaConError(err);
            });
        },

        EjecutaConExito(res)
        {
            this.oBtnSave.DisableBtn = false;
            this.oBtnSave.toast = 1;
            this.bus.$emit('CloseModal_'+this.EmitSeccion);
            this.bus.$emit('List_'+this.EmitSeccion);
        },
        EjecutaConError(err)
        {
            this.oBtnSave.DisableBtn = false;

            if(err.response.data.errors){
                this.errorvalidacion = err.response.data.errors;
                this.oBtnSave.toast = 2;
            }
            else{
                this.$toast.error(err.response.data.message);
            }
        },





	},
	created() {
		this.ListaPerfil();
        this.bus.$off('Save_'+this.ConfigList.EmitSeccion);
	},
	mounted() {
		this.bus.$on('Save_'+this.ConfigList.EmitSeccion,()=>
        {
            this.Guardar();
        });
	},
};
</script>
