<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Ruta</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{(index+1)}}</td>
                    <td>{{ lista.NombreRuta}}</td>
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdRuta" :pEmitSeccion="ConfigList.EmitSeccion">
							<template slot="btnaccion">
                                <button type="button" @click="AsignarPersonal(lista);" v-b-tooltip.hover.Top title="Asignar Personal" class="btn btn-icon btn-warning">
                                    <!-- <i class="fa fa-route"></i> -->
                                    <i class="fa fa-biking"></i>
                                </button>
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

        <CModal :pConfigModal="ConfigModal2" :poBtnSave="oBtnSave2">
			<template slot="Form">
				<FormAsig :poBtnSave="oBtnSave2"></FormAsig>
			</template>
		</CModal>
    </div>
</template>

<script>

    import Form       from '@/views/catalogos/rutas/Form.vue';
    import FormAsig   from '@/views/catalogos/rutas/FormAsignacion.vue';
    import Configs    from '@/helpers/VarConfig.js';
    const EmitEjecuta = 'seccionRutas';
    const EmitAsigna  = 'seccionAsignacion';

    export default {
        name: 'ListaRutas',
        components: {
            Form,
            FormAsig
        },
        data() {
            return {
                segurity:       {},
                obj:            {},
                ListaArrayRows: [],
                ListaHeader:    [],
                ConfigList:{
                    Title:          'Listado Rutas',
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
                    ModalTitle:  "Formulario Rutas",
                    ModalNameId: 'ModalForm',
                    EmitSeccion:  EmitEjecuta,
                },
                oBtnSave: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitEjecuta,
                },
                ConfigModal2:{
                    ModalTitle:  "Asignación Personal",
                    ModalNameId: 'ModalForm2',
                    EmitSeccion: EmitAsigna,
                    ModalSize:   'lg',
                },
                oBtnSave2: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitAsigna,
                },
            }
        },
        methods: {
            async Lista() {
                this.ConfigList.ShowLoader = true;

                await this.$http.get('rutas', {
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
                        this.$http.delete('rutas/'+Id)
                        .then( (res) => {
                            this.$swal(Configs.configEliminarConfirm);
                            this.Lista();
                        })
                        .catch( err => {
                            this.$toast.error(err.response.data.message);
                        });
                    }
                });
            },
            AsignarPersonal(item) {
                this.bus.$emit('NewModal_'+EmitAsigna,item);
            }
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
