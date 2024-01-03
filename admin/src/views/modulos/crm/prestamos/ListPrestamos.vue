<template>
	<div>
		<CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
			<template slot="Filtros">
				<button type="button" v-b-tooltip.hover.Top title="Recargar" @click="Lista()" class="btn btn-primary btn-sm mr-2">
					<i class="fas fa-redo"></i>
				</button>
			</template>
			<template slot="header">
                <th class="td-sm"></th>
                <th># Folio</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Monto Solicitado</th>
                <th>Fecha Registro</th>
                <th>Estatus</th>
                <th class="text-center">Acciones</th>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
                    <td><b>{{lista.Folio}}</b></td>
                    <td>{{ lista.NombreCompleto }}</td>
                    <td>{{ lista.Telefono }}</td>
                    <td>{{ $formatNumber(lista.MontoSolicitud,'$') }}</td>
                    <td>{{ $getCleanDate(lista.FechaPrestamo,false) }}</td>
					<td>
						<b-badge pill variant="primary">{{ lista.EstatusP }}</b-badge>
					</td>
					<td class="text-center">
						<CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="false" :pIsModal="false" :pId="lista.IdCliente" :pEmitSeccion="ConfigList.EmitSeccion" :pGoRoute="ConfigList.GoRoute">
							<template slot="btnaccion">
								<button type="button" v-b-tooltip.hover.Top title="Aprobar" @click="Preautorizar(lista.IdPrestamo)" class="btn btn-success btn-icon mr-1">
									<i class="fas fa-check"></i>
								</button>
								<button type="button" v-b-tooltip.hover.Top title="Rechazar" @click="Rechazo(lista)" class="btn btn-danger btn-icon">
									<i class="fas fa-ban"></i>
								</button>
                            </template>
						</CBtnAccion>
					</td>
				</tr>
				<CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="8"></CSinRegistro>
			</template>
		</CList>

		<CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave">
			<template slot="Form">
				<FormMotivoRechazo :poBtnSave="oBtnSave"></FormMotivoRechazo>
			</template>
		</CModal>
	</div>
</template>

<script>

	import FormMotivoRechazo from "@/views/modulos/crm/prestamos/FormRechazo.vue";
	import Configs 	 	  	 from "@/helpers/VarConfig.js";
	const  EmitEjecuta 	  	 = 	  "seccionPrestamo";

	export default {
		name: "ListaPrestamos",
		components: {
			FormMotivoRechazo
		},
		data() {
			return {
				ListaArrayRows: [],
				ListaHeader: 	[],
				segurity: 		{},
				obj: 	  		{},
				ConfigList: {
					TitleFirst:    'Menús Prestamos',
					Title: 		   'Listado Préstamos',
					IsModal:       false,
					ShowLoader:    true,
					BtnNewShow:    false,
                    BtnReturnShow: true,
                    TypeBody:      "List",
					GoRoute: 	   'formularioprestamos',
					EmitSeccion:   EmitEjecuta,
				},
				Filtro: {
					Nombre: 	 '',
					Pagina: 	 1,
					Entrada: 	 10,
					TotalItem: 	 0,
					Placeholder: 'Buscar..',
				},
				oBtnSave: {
					toast: 		 0,
					IsModal: 	 true,
					DisableBtn:  false,
					EmitSeccion: EmitEjecuta,
				},
				ConfigModal: {
					ModalTitle: 'Motivo de Rechazo',
					ModalSize:  'md',
					EmitSeccion: EmitEjecuta,
				},
			};
		},
		methods: {
			async Lista() {
                this.ConfigList.ShowLoader = true;

                await this.$http.get("prospectosxprestamos", {
                    params: {
                        txtBusqueda: this.Filtro.Nombre,
                        Entrada: this.Filtro.Entrada,
                        Pag: this.Filtro.Pagina,
                        Estatus: 'Activo',
                        EstatusPrestamo: 'Pendiente',
                        isCliente:1,
                    },

                }).then((res) => {
                    this.Filtro.Pagina      = res.data.data.current_page;
                    this.Filtro.Entrada     = res.data.data.per_page;
                    this.Filtro.TotalItem   = res.data.data.total;

                    this.ListaArrayRows     = res.data.data.data;

                }).finally(() => {
                    this.ConfigList.ShowLoader = false;
                });
			},


			Preautorizar(item) {
				this.$swal(Configs.configAutorizarPrestamo).then((result) => {
					if (result.value) {

                        this.errorvalidacion = [];

                        let request = {
                            IdCliente: item.IdCliente,
                            IdPrestamo: item.IdPrestamo,
                            Operacion: 'AceptoPrestamo',
                            MotivoRechazo: ''
                        };

                        this.$http.post('prospestoPrestamoEtapas', request).then( (res) => {
                            this.$toast.success('Información Guardada');
                            this.Lista();

                        }).catch((err) => {
                            this.$toast.error('No se pudo actulizar la información');
                        });

					}
				});
			},

			Rechazo(Element) {
				this.bus.$emit('NewModal_'+EmitEjecuta, Element);
			},

			Timer() {
				this.IntervalTime = setInterval(
					function () {
						this.Lista("");
					}.bind(this),300000
				);
			},

			Regresar() {
				this.$router.push({name: 'menuprestamos',params:{}});
			},



		},
		created() {
			this.bus.$off("Delete_" + EmitEjecuta);
			this.bus.$off("List_" + EmitEjecuta);
			this.bus.$off("EmitReturn");
		},
		mounted() {
			this.Lista();
			this.Timer();

			this.bus.$on("List_" + EmitEjecuta, () => {
				this.Lista();
			});

			this.bus.$on("EmitReturn", () => {
				this.Regresar();
			});

		},
		destroy() {
			this.Timer();
		}
	};

</script>
