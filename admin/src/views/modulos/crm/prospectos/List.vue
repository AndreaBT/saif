<template>
	<div>
		<CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity" >

			<template slot="Filtros">
                <label  class="mr-1">Estatus</label>
				<select v-model="Filtro.Estatus" class="form-control form-select mr-2" @change="Lista" style="width: 130px;">
					<option value="Pendiente">Pendientes</option>
					<option value="Rechazado">Rechazados</option>
				</select>

                <button type="button" v-b-tooltip.hover.Top title="Recargar Forzadamente" @click="Lista" class="btn btn-primary btn-sm"  >
                    <i class="fas fa-redo"></i>
                </button>
			</template>

			<template slot="header">
				<th class="td-sm"></th>
				<th>#</th>
				<th>Nombre</th>
				<th>Teléfono</th>
				<th>Monto Solicitado</th>
				<th>Fecha Registro</th>
                <th v-if="Filtro.Estatus === 'Rechazado'">Fecha Rechazo</th>
				<th>Estatus</th>
				<th class="text-center">Acciones</th>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
					<td>{{$getNumItem(index)}}</td>
					<td>{{ lista.NombreCompleto }}</td>
					<td>{{ lista.Telefono }}</td>
					<td>{{ $formatNumber(lista.MontoSolicitud,'$') }}</td>
					<td>{{ $getCleanDate(lista.FechaPrestamo,false) }}</td>

                    <td v-if="lista.Estatus === 'Rechazado'">{{ $getCleanDate(lista.FRechazoPrestamo,false) }} </td>


					<td  v-if="lista.Estatus === 'Pendiente'" >
                        <b-badge pill variant="primary">{{ lista.Estatus }}</b-badge>
                    </td>
					<td v-if="lista.Estatus === 'Rechazado'">
                        <b-badge  pill variant="danger">{{ lista.Estatus }}</b-badge>
                    </td>

					<td class="text-center">
						<CBtnAccion :pGoRoute="ConfigList.GoRoute" :pShowBtnEdit="Filtro.Estatus === 'Pendiente'" :pShowBtnDelete="Filtro.Estatus === 'Pendiente'" :pIsModal="false" :pId="lista.IdCliente" :pEmitSeccion="ConfigList.EmitSeccion">
							<template slot="btnaccion">
                                <button  v-if="Filtro.Estatus === 'Pendiente'" type="button" v-b-tooltip.hover.Top title="Prospecto - Préstamo" @click="modalPreautorizar(lista)" class="btn btn-success btn-icon" >
                                    <i class="fas fa-check"></i>
                                </button>
                                <button v-if="Filtro.Estatus !== 'Pendiente'" type="button" v-b-tooltip.hover.Top title="Observación"  @click="verObservaciones(lista)"  class="btn btn-info btn-icon mr-1"  >
                                    <i class="fas fa-question"></i>
                                </button>
                                <button v-if="Filtro.Estatus !== 'Pendiente'" type="button" v-b-tooltip.hover.Top title="ReActivar"  @click="reactivarProspecto(lista)"  class="btn btn-success btn-icon "  >
                                    <i class="fas fa-undo"></i>
                                </button>
							</template>
						</CBtnAccion>
					</td>
				</tr>
				<CSinRegistros :pContIF="ListaArrayRows.length" :pColspan="9" />
			</template>
		</CList>

		<CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave">
			<template slot="Form">
				<mAutorizar :poBtnSave="oBtnSave" />
			</template>
		</CModal>

		<CModal :pConfigModal="ConfigModal2" :poBtnSave="oBtnSave2">
			<template slot="Form">
				<RechazoObserRechazo :poBtnSave="oBtnSave2"></RechazoObserRechazo>
			</template>
		</CModal>
	</div>
</template>

<script>

import CList 				from "../../../../components/CList";
import CBtnAccion 			from "../../../../components/CBtnAccion";
import CBtnSave 			from "../../../../components/CBtnSave";
import CSinRegistros 		from "../../../../components/CSinRegistros";
import CModal 				from "../../../../components/CModal";
import mAutorizar 			from "./AutorizarProspecto.vue";
import RechazoObserRechazo 	from "./RechazoObserRechazo.vue";
import Configs 				from "@/helpers/VarConfig.js";
const EmitEjecuta 			= "seccionPospecto";
const EmitEjecuta2 			= "seccionCliente2";

export default {
	name: "ListProspectos",
	components: {
        CList,
        CBtnAccion,
        CBtnSave,
        CSinRegistros,
        CModal,
        mAutorizar,
        RechazoObserRechazo
    },
	data() {
		return {
			ConfigList: {
				Title: "Listado de Prospectos",
				IsModal: false,
				ShowLoader: true,
				BtnReturnShow: false,
                ShowTitleFirst:false,
				EmitSeccion: EmitEjecuta,
				GoRoute: "prospectosdetalles",
			},

			Filtro: {
				Nombre: "",
				Pagina: 1,
				Entrada: 10,
				TotalItem: 0,
				Placeholder: "Buscar..",
                Estatus:'Pendiente'
			},

			ConfigModal: {
				ModalTitle: "Autorizar",
				ModalNameId: "ModalForm",
				EmitSeccion: EmitEjecuta,
				ModalSize: "md",
			},
            oBtnSave: {
                toast: 0,
                IsModal: true,
                DisableBtn: false,
                EmitSeccion: EmitEjecuta,
            },

            ConfigModal2:{
                ModalTitle:  "Observaciones de Rechazo",
                ModalSize:   'md',
                EmitSeccion:  EmitEjecuta2,
            },

			oBtnSave2: {
                toast:       0,
                IsModal:     true,
                DisableBtn:  false,
                EmitSeccion: EmitEjecuta2,
                ShowBtnSave: false
            },

			segurity: {},
			obj: {},
			ListaArrayRows: [],
            counterField:1,

		}
	},
	methods: {
		async Lista() {
			this.ConfigList.ShowLoader = true;

            await this.$http.get("prospectosxprestamos", {
                params: {
                    txtBusqueda: 	 this.Filtro.Nombre,
                    Entrada: 		 this.Filtro.Entrada,
                    Pag: 			 this.Filtro.Pagina,
                    Estatus: 		 this.Filtro.Estatus,
                    EstatusPrestamo: this.Filtro.Estatus,
                    isProspecto: 	 1
                },

            }).then((res) => {
                this.Filtro.Pagina    = res.data.data.current_page;
                this.Filtro.Entrada   = res.data.data.per_page;
                this.Filtro.TotalItem = res.data.data.total;
                this.ListaArrayRows   = res.data.data.data;
                this.$setStartItem();
            }).finally(() => {
                this.ConfigList.ShowLoader = false;
            });

		},


		Eliminar(Id) {
			this.$swal(Configs.configEliminar).then((result) => {
				if (result.value) {
					this.$http.delete("clientes/" + Id).then((res) => {
						this.$swal(Configs.configEliminarConfirm);
						this.Lista();
					})
					.catch((err) => {
						this.$toast.error(err.response.data.message);
					});
				}
			});
		},

		reactivarProspecto(item){
			this.$swal(Configs.configReactivarProspecto).then((result) => {
				if(result.value) {
                    this.errorvalidacion = [];
                    let request = {
                        IdCliente:     item.IdCliente,
                        IdPrestamo:    item.IdPrestamo,
                        Operacion:     'ReactivaAmbos',
                        MotivoRechazo: ''
                    };

                    this.$http.post('prospestoPrestamoEtapas', request).then((res)=>{
                        this.$toast.success('Información Actualizada','','');
                        this.Lista();
                    }).catch((err) => {
                        if (err.response.data.errors) {
                            this.errorvalidacion = err.response.data.errors;
                        } else {
                            this.$toast.error('No se pudo actulizar la información','','');
                        }
					});
				}
			});
		},

		Regresar() {
            this.$router.push({name:'menuprestamos',params:{}});
        },

        /*MODAL PARA SELECCIONAR ACCION A TOMAR SOBRE UN PROSPECTO PENDIENTE*/
        modalPreautorizar(item){
            this.bus.$emit('NewModal_'+EmitEjecuta,item);
        },

        verObservaciones(item) {
			this.bus.$emit('NewModal_'+EmitEjecuta2,item);
        },

		Timer() {
			this.IntervalTime = setInterval(
				function() {
					this.Lista("");
				}.bind(this),
				300000
			);
		},



	},
	created() {
		this.bus.$off("Delete_" + EmitEjecuta);
		this.bus.$off("List_" + EmitEjecuta);
		//this.bus.$off("List_" + EmitEjecuta2);
	},
	mounted() {
		this.Lista();
		//this.Timer();
		this.bus.$on("Delete_" + EmitEjecuta, (Id) => {
			this.Eliminar(Id);
		});

		this.bus.$on("List_" + EmitEjecuta, () => {
			this.Lista();
		});
		/*this.bus.$on("List_" + EmitEjecuta2, () => {
			this.Lista();
		});*/
		this.bus.$on('EmitReturn',()=> {
            this.Regresar();
        });
	},

	destroy(){
		//this.Timer();
	}
};
</script>
