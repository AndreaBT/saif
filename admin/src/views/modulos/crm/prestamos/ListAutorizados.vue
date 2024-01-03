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
				<th>Cliente</th>
				<th>Teléfono</th>
				<th>Monto Solicitado</th>
				<th>Fecha Registro</th>
				<th>Estatus</th>
				<th>Estatus Entrega</th>
				<th>Accciones</th>
			</template>
            <template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
					<!--<td>{{ index + 1 }}</td>-->
					<td><b>{{ lista.Folio }}</b></td>
					<td>{{ lista.NombreCompleto }}</td>
					<td>{{ lista.Telefono }}</td>
					<td>{{ $formatNumber(lista.MontoSolicitud,'$') }}</td>
					<td>{{ $getCleanDate(lista.FechaPrestamo,false) }}</td>
					<td ><b-badge pill variant="warning">{{ lista.EstatusP }}</b-badge></td>
					<td>
                        <template  v-if="lista.EstatusEntrega === 'Pendiente'">
                            <b-badge pill variant="primary">{{ lista.EstatusEntrega }}</b-badge>
                        </template>
                        <template  v-else-if="lista.EstatusEntrega === 'Cancelada'">
                            <b-badge pill variant="danger">{{ lista.EstatusEntrega }}</b-badge>
                        </template>

                    </td>
                    <td>
                        <CBtnAccion  :pShowBtnEdit="false" :pShowBtnDelete="false" :pIsModal="false" :pId="lista.IdCliente">
                            <template slot="btnaccion">
                                <button type="button" v-b-tooltip.hover.Top title="Cancelar Prestamo" class="btn btn-danger btn-icon mr-1" @click="cancelarPrestamo(lista)">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <button v-if="lista.EstatusEntrega === 'Cancelada'" type="button" v-b-tooltip.hover.Top title="Motivo de Cancelacion"  @click="verObservaciones(lista)"  class="btn btn-info btn-icon mr-1"  >
                                    <i class="fas fa-question"></i>
                                </button>
                            </template>
                        </CBtnAccion>
                    </td>
				</tr>
				<CSinRegistros :pContIF="ListaArrayRows.length" :pColspan="9" ></CSinRegistros>
			</template>
		</CList>

		<CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :poBtnSave="oBtnSave"/>
			</template>
		</CModal>

        <CModal :pConfigModal="ConfigModal2" :poBtnSave="oBtnSave2">
            <template slot="Form">
                <CancelarTotalPrestamo :poBtnSave="oBtnSave2" :Operacion="Operacion"/>
            </template>
        </CModal>

	</div>
</template>

<script>

	import Configs    from "@/helpers/VarConfig.js";
	import Form       from "@/views/modulos/crm/prestamos/FormAsignacion.vue";
    import CList from "../../../../components/CList";
    import CModal from "../../../../components/CModal";
    import CSinRegistros from "../../../../components/CSinRegistros";
    import CBtnAccion from "../../../../components/CBtnAccion";
    import CancelarTotalPrestamo from "./CancelarTotalPrestamo";

    const EmitEjecuta =    "seccionPrestamoAutorizar";
    const EmitEjecuta2 =    "seccionPrestamoCancelar";

	export default {
		name: "ListaClientes",
		components: {
            Form,
            CList,
            CModal,
            CSinRegistros,
            CBtnAccion,
            CancelarTotalPrestamo
        },
		data() {
			return {
				ConfigList: {
					Title:	 		"Prestamos Pre-Autorizados",
					IsModal: 		true,
					ShowLoader: 	true,
					BtnReturnShow: 	true,
					BtnNewName:     'Asignar',
					EmitSeccion: 	EmitEjecuta,
					TitleFirst:     'Menús Prestamos',
				},
				Filtro: {
					Nombre: "",
					Pagina: 1,
					Entrada: 10,
					TotalItem: 0,
					Placeholder: "Buscar..",
				},
				ConfigModal:{
					ModalTitle:  "Asignación de Clientes",
					ModalSize:   'lg',
					EmitSeccion:  EmitEjecuta,
				},
				oBtnSave: {
					toast:       0,
					IsModal:     true,
					DisableBtn:  false,
					EmitSeccion: EmitEjecuta,
				},
                // MODAL DE CANCELACION DE PRESTAMO
                ConfigModal2:{
                    ModalTitle:  "Cancelar Prestamo",
                    ModalSize:   'lg',
                    EmitSeccion:  EmitEjecuta2,
                },

                oBtnSave2: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitEjecuta2,
                    ShowBtnSave: true
                },


				segurity: {},
				obj: {},
				ListaArrayRows: [],
                Operacion:0,
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
                        EstatusPrestamo: 'PreAutorizado'
                    },

                }).then((res) => {
                    this.ListaArrayRows     = res.data.data.data;
                    this.Filtro.Pagina      = res.data.data.current_page;
                    this.Filtro.TotalItem   = res.data.data.total;

                }).finally(() => {
                    this.ConfigList.ShowLoader = false;
                });
			},

			Regresar() {
				this.$router.push({name:'menuprestamos',params:{}});
			},

            cancelarPrestamo(item){
                this.ConfigModal2.ModalTitle = 'Cancelar Prestamo';
                this.Operacion = 1;
                this.bus.$emit('NewModal_'+EmitEjecuta2,item);
            },

            verObservaciones(item) {
                this.ConfigModal2.ModalTitle = 'Motivo de Cancelacion de Entrega';
                this.Operacion = 2;
                this.bus.$emit('NewModal_'+EmitEjecuta2,item);
            }

		},
		created() {
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
		// beforeDestroy() {
		//     this.bus.$off('List');
		// },
	};

</script>
