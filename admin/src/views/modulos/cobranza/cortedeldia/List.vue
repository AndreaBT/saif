<template>
	<div>
		<CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity" >
			<template slot="header">
				<th class="td-sm"></th>
				<th>#</th>
				<th>Nombre</th>
                <th>Puesto</th>
				<th>Perfil</th>
				<th class="text-center">Acciones</th>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
					<td>{{ $getNumItem(index) }}</td>
					<td>{{ lista.NombreCompleto }}</td>
                    <td>{{ lista.puesto.Nombre }}</td>
					<td>{{ lista.perfil.Nombre }}</td>
					<td class="text-center">
						<CBtnAccion pGoRoute="corteDiaPagos" :pShowBtnEdit="false" :pShowBtnDelete="false" :pIsModal="false" :pId="lista.IdUsuario" :pEmitSeccion="ConfigList.EmitSeccion" >
							<template slot="btnaccion">
								<button type="button" v-b-tooltip.hover.Top title="Ver Corte" @click="Prestamos(lista.IdUsuario)" class="btn btn-primary btn-icon" >
									<i class="fas fa-hand-holding-usd"></i>
								</button>
							</template>
						</CBtnAccion>
					</td>
				</tr>
				<CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="7" ></CSinRegistro>
			</template>
		</CList>

	</div>
</template>

<script>

	const EmitEjecuta = "seccionCliente";

	export default {
		name: "ListaCorteDelDia",
		components: {},
		data() {
			return {
				ConfigList: {
                    ShowTitleFirst:  false,
					Title: 			'Corte del Dia',
					IsModal: 		false,
					ShowLoader: 	true,
					BtnReturnShow:  false,
					EmitSeccion: 	EmitEjecuta,
					GoRoute: 		'',
					BtnNewShow:     false,
				},
				Filtro: {
					Nombre: "",
					Pagina: 1,
					Entrada: 10,
					TotalItem: 0,
					Placeholder: "Buscar..",
				},
				segurity: {},
				obj: {},
				ListaArrayRows: [],
                counterField: 1
			};
		},
		methods: {
			async Lista() {
				this.ConfigList.ShowLoader = true;

				await this.$http.get("getUsuariosCobranza", {
						params: {
							txtBusqueda: this.Filtro.Nombre,
							Entrada: 	 this.Filtro.Entrada,
							Pag: 		 this.Filtro.Pagina
						},
					})
					.then((res) => {
                        this.Filtro.Pagina      = res.data.data.current_page;
                        this.Filtro.TotalItem   = res.data.data.total;
                        this.$setStartItem();

						this.ListaArrayRows     = res.data.data.data;

					}).finally(() => {
						this.ConfigList.ShowLoader = false;
					});
			},
			Prestamos(Id) {
				this.$router.push({name:'corteDiaPagos',params:{Id}});
                sessionStorage.setItem('IdGeneral',Id);
			},

		},
		created() {
			this.bus.$off("List_" + EmitEjecuta);
            sessionStorage.setItem('IdGeneral',0);
		},
		mounted() {
			this.Lista();

			this.bus.$on("List_" + EmitEjecuta, () => {
				this.Lista();
			});

		},
	};

</script>
