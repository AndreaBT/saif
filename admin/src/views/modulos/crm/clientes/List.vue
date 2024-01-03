<template>
	<div>
		<CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity" >
			<template slot="header">
				<th class="td-sm"></th>
				<th>#</th>
				<th>Nombre</th>
				<th>Telefono</th>
                <th>Fecha de registro</th>
				<th>Estatus</th>
				<th class="text-center">Acciones</th>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
					<td>{{ $getNumItem(index) }}</td>
					<td>{{ lista.NombreCompleto }}</td>
					<td>{{ lista.Telefono }}</td>
                    <td>{{ $getCleanDate(lista.created_at,false) }}</td>
					<td><b-badge pill variant="success">{{ lista.Estatus }}</b-badge></td>
					<td class="text-center">
						<CBtnAccion :pGoRoute="ConfigList.GoRoute" :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="false" :pId="lista.IdCliente" :pEmitSeccion="ConfigList.EmitSeccion">
							<template slot="btnaccion"></template>
						</CBtnAccion>
					</td>
				</tr>
				<CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="7" ></CSinRegistro>
			</template>
		</CList>

	</div>
</template>

<script>

import Configs    from "@/helpers/VarConfig.js";
const EmitEjecuta =    "seccionCliente";

export default {
	name: "ListaClientes",
	data() {
		return {
            counterField:	1,
			ListaArrayRows: [],
			segurity: 		{},
			obj: 	  		{},
			ConfigList: {
				Title: 			"Listado Clientes",
				IsModal: 		false,
				ShowLoader: 	true,
				BtnReturnShow:  false,
				EmitSeccion: 	EmitEjecuta,
				GoRoute: 		"formularioclientesactivos",
				TitleFirst:     'Inicios',
				BtnNewShow:     false,
			},
			Filtro: {
				Nombre: 	 "",
				Pagina: 	 1,
				Entrada:     10,
				TotalItem:   0,
				Placeholder: "Buscar..",
			},
		};
	},
	methods: {
		async Lista() {
			this.ConfigList.ShowLoader = true;

			await this.$http
				.get("clientesActivos", {
					params: {
						TxtBusqueda: this.Filtro.Nombre,
						Entrada: 	 this.Filtro.Entrada,
						Pag: 		 this.Filtro.Pagina
					},
				})
				.then((res) => {
					this.Filtro.Pagina    = res.data.data.cliente.current_page;
					this.Filtro.TotalItem = res.data.data.cliente.total;
                    this.ListaArrayRows   = res.data.data.cliente.data;
                    this.$setStartItem();
				})
				.finally(() => {
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
	},
	created() {
		this.bus.$off("Delete_" + EmitEjecuta);
		this.bus.$off("List_" + EmitEjecuta);
	},
	mounted() {
		this.Lista();
		this.bus.$on("Delete_" + EmitEjecuta, (Id) => {
			this.Eliminar(Id);
		});

		this.bus.$on("List_" + EmitEjecuta, () => {
			this.Lista();
		});

	},
};
</script>
