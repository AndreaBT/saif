<template>
	<div>
		<CList
			@FiltrarC="Lista"
			:pConfigList="ConfigList"
			:pFiltro="Filtro"
			:segurity="segurity"
		>
			<template slot="header">
                <th class="td-sm"></th>
                <th># Folio</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Monto Solicitado</th>
                <th>Fecha Rechazo</th>
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
                    <td>{{ $getCleanDate(lista.FRechazoPrestamo,false) }} </td>
                    <td ><b-badge pill variant="danger">{{ lista.EstatusP }}</b-badge></td>

					<td class="text-center">
						<CBtnAccion :pGoRoute="ConfigList.GoRoute" :pShowBtnEdit="false" :pShowBtnDelete="false" :pIsModal="false" :pId="lista.IdCliente" :pEmitSeccion="ConfigList.EmitSeccion" >
							<template slot="btnaccion">
								<button type="button"  @click="verObservaciones(lista,isPropecto);" v-b-tooltip.hover.Top title="Observación" class="btn btn-info btn-icon ml-1"  >
									<i class="fas fa-question"></i>
								</button>
								<button type="button" v-b-tooltip.hover.Top title="Reactivar" @click="Preautorizar(lista)" class="btn btn-success btn-icon ml-1"  >
									<i class="fas fa-undo"></i>
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
				<Form :poBtnSave="oBtnSave"></Form>
			</template>
		</CModal>
	</div>
</template>

<script>

	import Configs    from "@/helpers/VarConfig.js";
	import Form       from "@/views/modulos/crm/prospectos/RechazoObserRechazo.vue";
	const EmitEjecuta =    "seccionCliente";

	export default {
		name: "ListaClientes",
		components: {Form},
		data() {
			return {
				ConfigList: {
					Title: 		   "Prestamos Rechazados",
					IsModal:       true,
					BtnNewShow:    false,
					ShowLoader:    true,
					BtnReturnShow: true,
					EmitSeccion:   EmitEjecuta,
					GoRoute: 	   'autorizados',
					TitleFirst:    'Menús Prestamos',
				},
				Filtro: {
					Nombre: "",
					Pagina: 1,
					Entrada: 10,
					TotalItem: 0,
					Placeholder: "Buscar..",
				},
				ConfigModal:{
					ModalTitle:  "Rechazo",
					ModalSize:   'md',
					EmitSeccion:  EmitEjecuta,

				},
				oBtnSave: {
					toast:       0,
					IsModal:     true,
					DisableBtn:  false,
					EmitSeccion: EmitEjecuta,
					ShowBtnSave: false
				},
				segurity: {},
				obj: {},
				ListaArrayRows: [],
				IdCliente:0,
				isPropecto:0
				// ListaHeader: [],
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
                        EstatusPrestamo: 'Rechazado'
                    },

                }).then((res) => {
                    this.Filtro.Pagina      = res.data.data.current_page;
                    this.Filtro.Entrada     = res.data.data.per_page;
                    this.Filtro.TotalItem   = res.data.data.total;

                    //this.getStartCounter(this.Filtro.Pagina,this.Filtro.Entrada)

                    this.ListaArrayRows     = res.data.data.data;

                }).finally(() => {
                    this.ConfigList.ShowLoader = false;
                });
			},
			Preautorizar(item){
				this.$swal(Configs.configReactivarPrestamo).then((result) => {
					if(result.value) {
                        this.errorvalidacion = [];
						let request = {
							IdCliente:     item.IdCliente,
                            IdPrestamo:    item.IdPrestamo,
							Operacion:     "ReactivaPrestamo",
                            MotivoRechazo: ''
						};

						this.$http.post('prospestoPrestamoEtapas', request).then( (res) => {
							this.$toast.success('Información Guardada');
							this.Lista();
						}).catch( err => {
                            if (err.response.data.errors) {
                                this.errorvalidacion = err.response.data.errors;
                            } else {
                                this.$toast.error('No se pudo actulizar la información','','');
                            }

						});

					}
				});
			},
            verObservaciones(item) {
				this.bus.$emit('NewModal_'+this.ConfigList.EmitSeccion,item,this.ConfigList.Obj);
			},
			Regresar()
			{
				this.$router.push({name:'menuprestamos',params:{}});
			},
		},
		created() {
			this.bus.$off("Delete_" + EmitEjecuta);
			this.bus.$off("List_" + EmitEjecuta);
		},
		mounted() {
			this.Lista();

			this.bus.$on("List_" + EmitEjecuta, () => {
				this.Lista();
			});
			this.bus.$on('EmitReturn',()=>
			{
				this.Regresar();
			});
		},

	};

</script>
