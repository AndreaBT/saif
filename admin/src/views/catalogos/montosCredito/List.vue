<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Monto</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{$getNumItem(index)}}</td>
                    <td>{{ formatNumber(lista.Monto,"$") }}</td>
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdPrestamoMonto" :pEmitSeccion="ConfigList.EmitSeccion">
							<template slot="btnaccion">
                            </template>
						</CBtnAccion>
                    </td>
                </tr>
                <CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="4"></CSinRegistro>
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

    import Form        from '@/views/catalogos/montosCredito/Form.vue';
    import Configs     from '@/helpers/VarConfig.js';
    const  EmitEjecuta = 'seccionMontosCredito';

    export default {
        name: 'ListaMontos',
        components: {
            Form
        },
        data() {
            return {
                counterField:   1,
                segurity:       {},
                obj:            {},
                ListaArrayRows: [],
                ListaHeader:    [],
                ConfigList:{
                    Title:          'Listado Montos Crédito',
                    IsModal:        true,
                    ShowLoader:     true,
                    BtnReturnShow:  false,
                    EmitSeccion:    EmitEjecuta,
                },
                Filtro:{  
                    Nombre:      '',
                    Pagina:      1,
                    Entrada:     10,
                    TotalItem:   0,
                    Placeholder: 'Buscar..',
                },
                ConfigModal:{
                    ModalTitle:  "Formulario Montos",
                    ModalNameId: 'ModalForm',
                    ModalSize:   'md',
                    EmitSeccion:  EmitEjecuta,
                },
                oBtnSave: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitEjecuta,
                },
            }
        },
        methods: {
            async Lista()
            {
                this.ConfigList.ShowLoader = true;

                await this.$http.get('prestamosmontos', {
                    params:{
                        TxtBusqueda: this.Filtro.Nombre,
                        Entrada:     this.Filtro.Entrada,
                        Pag:         this.Filtro.Pagina,
                    }
                }).then( (res) => {
                    this.ListaArrayRows   = res.data.data.data;
                    this.Filtro.Pagina    = res.data.data.current_page;
                    this.Filtro.TotalItem = res.data.data.total;
                    this.$setStartItem();
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Eliminar(Id) {
                this.$swal(Configs.configEliminar).then((result) => {
                    if(result.value) {
                        this.$http.delete('prestamosmontos/'+Id).then( (res) => {
                            this.$swal(Configs.configEliminarConfirm);
                            this.Lista();
                        })
                        .catch( err => {
                            this.$toast.error(err.response.data.message);
                        });
                    }
                });
            },
            formatNumber(num,prefix) {
                if (num !== null) {
                    num    =  Math.round(parseFloat(num) * Math.pow(10, 2)) / Math.pow(10, 2)
                    prefix =  prefix || '';
                    num    += '';
                    let splitStr    = num.split('.');
                    let splitLeft   = splitStr[0];
                    let splitRight  = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
                    splitRight      = splitRight + '00';
                    splitRight      = splitRight.substr(0, 3);
                    let regx        = /(\d+)(\d{3})/;

                    while (regx.test(splitLeft)) {
                        splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');

                    }

                    return prefix + splitLeft + splitRight;
                } else {
                    return prefix + '0.00';
                }
            },
        },
        created() {
            this.bus.$off('Delete_'+EmitEjecuta);
            this.bus.$off('List_'+EmitEjecuta);
        },
        mounted() {
            this.Lista();

            this.bus.$on('Delete_'+EmitEjecuta,(Id)=> {
                this.Eliminar(Id);
            });

            this.bus.$on('List_'+EmitEjecuta,()=> {
                this.Lista();
            });

        },
    }

</script>
