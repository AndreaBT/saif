<template>
	<div>
		<CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity" >
			<template slot="header">
				<th class="td-sm"></th>
				<th>#</th>
				<th>Nombre</th>
                <th>Porcentaje</th>
				<th class="text-center">Acciones</th>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
					<td>{{ index + 1 }}</td>
					<td>{{ lista.Nombre }}</td>
                    <td>{{ lista.Porcentaje }} %</td>
					<td class="text-center">
						<CBtnAccion pGoRoute="corteDiaPagos" :pShowBtnEdit="true" :pShowBtnDelete="false" :pIsModal="true" :pId="lista.IdTazainteres" :pEmitSeccion="ConfigList.EmitSeccion" >
							<template slot="btnaccion">
								
							</template>
						</CBtnAccion>
					</td>
				</tr>
				<CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="7" ></CSinRegistro>
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

	import Configs 	  from "@/helpers/VarConfig.js";
	import Form 	  from '@/views/catalogos/tazaInteres/Form.vue';
	const EmitEjecuta = "seccionTaza";

	export default {
		name: "ListaClientes",
		components: {Form},
		data() {
			return {
				ListaArrayRows: [],
				segurity: 		{},
				obj: 	  		{},
				ConfigList: {
					Title: 			"Listado de Tazas de Interés",
					IsModal: 		true,
					ShowLoader: 	true,
					BtnReturnShow:  false,
					EmitSeccion: 	EmitEjecuta,
					TitleFirst:     'Inicio',
					BtnNewShow:     false,
				},
				Filtro: {
					Nombre: 	 "",
					Pagina: 	 1,
					Entrada: 	 10,
					TotalItem: 	 0,
					Placeholder: "Buscar..",
				},
				ConfigModal:{
					ModalTitle:  "Taza De Interés",
					ModalNameId: 'ModalForm',
					EmitSeccion:  EmitEjecuta,
				},
				oBtnSave: {
					toast: 		 0,
					IsModal: 	 true,
					DisableBtn:  false,
					EmitSeccion: EmitEjecuta,
				}, 
			};
		},
		methods: {
			async Lista() {
				this.ConfigList.ShowLoader = true;

				await this.$http.get("getTazaInteres", {
					params: {
						TxtBusqueda: this.Filtro.Nombre,
						Entrada: 	 this.Filtro.Entrada,
						Pag: 		 this.Filtro.Pagina
					},
				})
				.then((res) => {
					this.ListaArrayRows   = res.data.data.TazaInteres.data;
					this.Filtro.Pagina    = res.data.data.TazaInteres.current_page;
					this.Filtro.TotalItem = res.data.data.TazaInteres.total;
				})
				.finally(() => {
					this.ConfigList.ShowLoader = false;
				});
			},
		},
		created() {
			this.bus.$off("List_" + EmitEjecuta);
		},
		mounted() {
			this.Lista();

			this.bus.$on("List_" + EmitEjecuta, () => {
				this.Lista();
			});
			
		},
	};

</script>
