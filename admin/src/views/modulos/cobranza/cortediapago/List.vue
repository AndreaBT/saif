<template>
    <div>
		<CList @FiltrarC="Lista()" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity" >
			<template slot="Filtros">
                <select v-model="Filtro.EstatusP" class="form-control mr-2" @change="Lista">
                    <option value="Cobranza">En Cobranza</option>
                    <option value="Entregado">Entregados</option>
                </select>

				<button type="button" v-b-tooltip.hover.Top title="Día siguiente" @click="pagos" v-show="ConfigBtn.BtnNewPaymentShow" class="btn btn-primary btn-sm mr-1">
					Día siguiente
				</button>
			</template>
			<template slot="header">
				<th class="td-sm"></th>
				<th># Folio</th>
				<th>Nombre</th>
				<th>Monto Entregado</th>
				<th>Pago Diario</th>
				<th>Pago Actual</th>
				<th>Estatus</th>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
					<td><b>{{ lista.Folio }}</b></td>
					<td>{{ lista.NomCliente }}</td>
					<td>{{ $formatNumber(lista.MontoEntregado, '$') }}</td>
					<td>{{ $formatNumber(lista.MontoDiario,'$') }}</td>
					<td>{{ lista.NumPagoActual }}</td>
					<td v-if="lista.Estatus === 'Entregado'"> <b-badge pill variant="primary">{{ lista.Estatus }}</b-badge></td>
					<td v-if="lista.Estatus === 'Cobranza'"> <b-badge pill variant="success">{{ lista.Estatus }}</b-badge></td>
				</tr>
				<CSinRegistros :pContIF="ListaArrayRows.length" :pColspan="7" />
			</template>
		</CList>
	</div>
</template>

<script>

    import moment        from 'moment';
	import CList 		 from "../../../../components/CList";
	import CSinRegistros from "../../../../components/CSinRegistros";
	import Configs 	     from "@/helpers/VarConfig.js";
	const  EmitEjecuta   = 	  "seccionCliente";

	export default {
		name: "PrestamosCobranza",
		props:['Id'],
		components: {
            CList,
            CSinRegistros
        },
		data() {
			return {
				FechaPestramo:   '',
                FechaHoy:        '',
				errorvalidacion: [],
				ListaArrayRows:	 [],
				arreglo:		 [],
				segurity: 		 {},
				obj: 			 {},
				ConfigList: {
                    ShowTitleFirst:  true,
					TitleFirst:      'Corte del Dia',
					Title: 			 "Corte del Dia de Pagos",
					IsModal: 		 false,
					BtnNewShow: 	 false,
					TypeBody: 		 "List",
					ShowTitleInline: false,
					ShowLoader: 	 true,
					BtnReturnShow: 	 true,
					EmitSeccion: 	 EmitEjecuta,
				},
				ConfigBtn: {
					BtnNewPaymentShow: true,
				},
				Filtro: {
					Nombre: 	 "",
					Pagina: 	 1,
					Entrada: 	 10,
					TotalItem: 	 0,
					Placeholder: "Buscar..",
                    EstatusP: 	 "Cobranza",
				},
				objPrestamo:{
					IdCliente:		 0,
					Prospecto:		 0,
					Estatus:		 '',
					MotivoRechazo:	 '',
					FechaRechazo:	 '',
					IdPrestamo:		 0,
					clienteRechazo:	 '',
					PrestamoRechazo: '',
					IdCobratario:	 0
				},
			};
		},
		methods: {
			pagos(){
				this.$swal(Configs.configGenerarDia).then((result) => {
					if (result.value) {
						this.errorvalidacion = [];

						let newRequest = {
							IdCobratario: this.Id
						}
						
						this.$http.post('Pagos', newRequest).then((res)=>{
							this.Lista();
						});
                    }
				});
			},
			async Lista() {
				this.ConfigList.ShowLoader = true;
				await this.$http.get("getprestamoscorte", {
					params: {
						txtBusqueda:    this.Filtro.Nombre,
						Entrada: 	    this.Filtro.Entrada,
						Pag: 		    this.Filtro.Pagina,
                        IdCobratario:   this.objPrestamo.IdCobratario,
                        estatusP: 		this.Filtro.EstatusP
					},
				}).then( (res) => {
					this.ListaArrayRows   = res.data.data.PrestamosCobranzas.data;
					this.Filtro.Pagina 	  = res.data.data.PrestamosCobranzas.current_page;
					this.Filtro.TotalItem = res.data.data.PrestamosCobranzas.total;
					
					this.ListaArrayRows.forEach(element => {

						this.FechaPestramo = moment(element.updated_at, 'YYYY-MM-DD').format('YYYY-MM-DD')
						this.FechaHoy      = moment(new Date(), 'YYYY-MM-DD').format('YYYY-MM-DD');

						// console.log(this.FechaPestramo+' '+'FECHA PRESTAMO');
						// console.log(this.FechaHoy+' '+'FECHA DEL DIA DE HOY');
						// NO ESTOY CONVENCIDO PERO POR LO MIENTRAS DEJARE LA VALIDACIÓN DEL BOTON DE DIA SIGUIENTE ASÍ
						if(element.Estatus == 'Cobranza' && this.FechaPestramo == this.FechaHoy){
							this.ConfigBtn.BtnNewPaymentShow = false;
						} else {
							this.ConfigBtn.BtnNewPaymentShow = true;
						}

					})

				}).finally(()=>{
					this.ConfigList.ShowLoader = false;
				});
			},
			Regresar() {
				this.$router.push({name: 'corteDia',params:{}});
			},
		},
		created() {
			this.bus.$off("EmitReturn");
		},
		mounted() {

			if(this.Id!=''){
				this.objPrestamo.IdCobratario=this.Id;
				this.Lista();
			} else {
                this.objPrestamo.IdCobratario = sessionStorage.getItem('IdGeneral');
            }

			this.bus.$on("EmitReturn", () => {
				this.Regresar();
			});
		},
        beforeDestroy() {
            sessionStorage.setItem('IdGeneral',0);
        }
    };

</script>
