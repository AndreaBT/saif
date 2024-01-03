<template>
	<div>

		<CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
			<template slot="Filtros">
				<select v-model="Filtro.IdUsuario" class="form-control form-select" name="Perfil" @change="Lista();" style="width: 138px;">
					<option value="0">--Seleccionar--</option>
					<option v-for="(item, index) in listEntregadores" :key="index" :value="item.IdUsuario">
						{{item.NombreCompleto}}
					</option>
				</select>
				<label><CValidation v-if="this.errorvalidacion.IdUsuario" :Mensaje="'*'+errorvalidacion.IdUsuario[0]"></CValidation></label>
			</template>

			<template slot="header">
				<th class="td-sm"></th>
                <!--<th>#</th>-->
                <th># Folio</th>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Monto Solicitado</th>
                <th>Fecha Registro</th>
                <th >Estatus</th>
                <th>Entregador</th>

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
                    <td> <b-badge pill variant="success">{{ lista.EstatusP }} </b-badge></td>
                    <td><CAvatar :fullname="lista.NomEntregador" :size="25" :radius="50"/> {{ lista.NomEntregador }}</td>


				</tr>
				<CSinRegistros :pContIF="ListaArrayRows.length" :pColspan="8"></CSinRegistros>
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

	import Form       from "@/views/modulos/crm/prestamos/FormReActivar.vue";
	import CValidation from '@/components/CValidation.vue';
    import CList from "../../../../components/CList";
    import CModal from "../../../../components/CModal";
    import CSinRegistros from "../../../../components/CSinRegistros";
    import CAvatar from "../../../../components/CAvatar";

	const EmitEjecuta =    "SeccionPorEntregar";

	export default {
		name: "ListaClientes",
		components: {
			Form,
            CValidation,
            CList,
            CModal,
            CSinRegistros,
            CAvatar
		},
		data() {
			return {
				ConfigList: {
					Title: "Prestamos Por Entregar",
					IsModal: true,
					ShowLoader: true,
					BtnReturnShow: true,
					BtnNewShow: true,
					BtnNewName: 'Desasignar',
					EmitSeccion: EmitEjecuta,
					TitleFirst: 'Menús Prestamos',
				},
				Filtro: {
					Nombre: "",
					Pagina: 1,
					Entrada: 10,
					TotalItem: 0,
					Placeholder: "Buscar..",
                    IdUsuario: 0,
				},
				ConfigModal: {
					ModalTitle: "Desasignación",
					ModalSize: 'lg',
					EmitSeccion: EmitEjecuta,
				},
				oBtnSave: {
					toast: 0,
					IsModal: true,
					DisableBtn: false,
					EmitSeccion: EmitEjecuta,
				},
				segurity: {},
				obj: {},
				ListaArrayRows: [],
                listEntregadores: [],
				 errorvalidacion:[],
			};
		},
		methods: {
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
				this.ConfigList.ShowLoader = true;

				await this.$http.get("prestamosasignado", {
                    params: {
                        txtBusqueda: this.Filtro.Nombre,
                        Entrada: this.Filtro.Entrada,
                        Pag: this.Filtro.Pagina,
                        EstatusPrestamo: 'Asignado',
                        IdUsuario: this.Filtro.IdUsuario,
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
				this.$router.push({ name: 'menuprestamos', params: {}
				});
			},
		},

		created() {
			this.bus.$off("List_" + EmitEjecuta);
			// this.bus.$off("Save_" + EmitEjecuta);
			this.ListaPerfil();
		},
		mounted() {

			this.Lista();

			this.bus.$on("List_" + EmitEjecuta, () => {
				this.Lista();
			});
			this.bus.$on('EmitReturn', () => {
				this.Regresar();
			});
		},
	};

</script>
