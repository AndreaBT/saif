<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Nombre</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{(index+1)}}</td>
                    <td>{{lista.Nombre }}</td>   
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdPuesto" :pEmitSeccion="ConfigList.EmitSeccion">
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

    import Form    from '@/views/catalogos/puestos/Form.vue';
    import Configs from '@/helpers/VarConfig.js';
    const EmitEjecuta = 'seccionPuestos';

    export default {
        name:       'ListaPuestos',
        components: { 
            Form,
        },
        data() {
            return {
                ConfigList:{
                    Title:         'Listado Puestos',
                    IsModal:       true,
                    ShowLoader:    true,
                    BtnReturnShow: false,
                    EmitSeccion:   EmitEjecuta, 
                },
                Filtro:{
                    Nombre:      '',
                    Pagina:      1,
                    Entrada:     10,
                    TotalItem:   0,
                    Placeholder: 'Buscar..',
                },
                ConfigModal:{
                    ModalTitle:  "Formulario Puestos",
                    ModalSize:   'md',
                    EmitSeccion:  EmitEjecuta,
                },
                oBtnSave: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitEjecuta,
                },
                segurity:       {},
                obj:            {},
                ListaArrayRows: [],
                ListaHeader:    [],
            }
        },
        methods: {
            async Lista(){
                this.ConfigList.ShowLoader = true;
                
                await this.$http.get('puestos', {
                    params:{
                        TxtBusqueda: this.Filtro.Nombre,
                        Entrada:     this.Filtro.Entrada,
                        Pag:         this.Filtro.Pagina,
                    }
                }).then( (res) => {
                    this.ListaArrayRows = res.data.data.Puesto.data;
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Eliminar(Id){
                this.$swal(Configs.configEliminar).then((result) => {
                    if(result.value) {                
                        this.$http.delete('puestos/'+Id).then( (res) => {
                            this.$swal(Configs.configEliminarConfirm);
                            this.Lista();
                        })
                        .catch( err => {
                            this.$toast.error(err.response.data.message);
                        });
                    }
                });
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