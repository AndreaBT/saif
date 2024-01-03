<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Permiso</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{(index+1)}}</td>
                    <td>{{ lista.Nombre}}</td>
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdPermiso" :pEmitSeccion="ConfigList.EmitSeccion">
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

    import Form       from '@/views/catalogos/permisos/Form.vue';
    import Configs    from '@/helpers/VarConfig.js';
    const EmitEjecuta = 'seccionPermisos';

    export default {
        name: 'ListaPermisos',
        components: {
            Form
        },
        data() {
            return {
                segurity:       {},
                obj:            {},
                ListaArrayRows: [],
                ListaHeader:    [],
                ConfigList:{
                    Title:         'Listado Permisos',
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
                    ModalTitle:  "Formulario Permisos",
                    ModalNameId: 'ModalForm',
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
            async Lista() {
                this.ConfigList.ShowLoader = true;

                await this.$http.get('permisos', {
                    params:{
                        TxtBusqueda: this.Filtro.Nombre,
                        Entrada:     this.Filtro.Entrada,
                        Pag:         this.Filtro.Pagina,
                    }
                }).then( (res) => {
                    this.ListaArrayRows   = res.data.data.data;
                    this.Filtro.Pagina    = res.data.data.current_page;
                    this.Filtro.TotalItem = res.data.data.total;
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Eliminar(Id) {
                this.$swal(Configs.configEliminar).then((result) => {
                    if(result.value) {
                        this.$http.delete('permisos/'+Id).then( (res) => {
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
            
            this.bus.$on('Delete_'+EmitEjecuta,(Id) => {
                this.Eliminar(Id);
            });

            this.bus.$on('List_'+EmitEjecuta,() => {
                this.Lista();
            });
        },
    }
    
</script>
