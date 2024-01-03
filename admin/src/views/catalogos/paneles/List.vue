<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="Filtros">
                <div class="form-group">
                    <label class="mr-1">Tipo:</label>
                    <select v-model="Filtro.TipoMenu" @change="Lista" class="form-control form-select">
                        <option value="">Todos</option>
                        <option value="Menu">Menu</option>
                        <option value="SubMenu">Sub Menu</option>
                        <option value="Apartado">Apartado</option>
                        <option value="SubApartado">Sub Apartado</option>
                    </select>
                </div>
            </template>

            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Nombre</th>
                <th>Tipo de Menu</th>
                <th>Menu Asociado</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{(index+1)}}</td>
                    <td>{{lista.Nombre }}</td>
                    <td>{{lista.TipoMenu }}</td>
                    <td>{{lista.MenuAsociado }}</td>
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdPanel" :pEmitSeccion="ConfigList.EmitSeccion">
							<template slot="btnaccion">
                                <button type="button" @click="AsignarPermisos(lista);" class="btn btn-icon btn-warning ml-1" v-b-tooltip.hover.Top title="Asignar Permisos">
                                    <i class="fa fa-key"></i>
                                </button>
                            </template>
						</CBtnAccion>  
                    </td>  
                </tr>
                <CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="6"></CSinRegistro> 
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

    import Form       from '@/views/catalogos/paneles/Form.vue';
    import FormAsig   from '@/views/catalogos/paneles/FormAsignacion.vue';
    import Configs    from '@/helpers/VarConfig.js';
    const EmitEjecuta = 'seccionPaneles';
    const EmitAsigna  = 'seccionPermisosPanel';

    export default {
        name: 'ListaPanelMenu',
        components: { 
            Form,
            FormAsig
        },
        data() {
            return {
                ListaHeader:    [],
                ListaArrayRows: [],
                obj:            {},
                segurity:       {},
                ConfigList:{
                    Title:          'Listado Menus',
                    IsModal:        true,
                    ShowLoader:     true,
                    BtnReturnShow:  false,
                    EmitSeccion:    EmitEjecuta, 
                },
                Filtro:{
                    Nombre:       '',
                    Pagina:       1,
                    Entrada:      10,
                    TotalItem:    0,
                    Placeholder: 'Buscar..',
                    TipoMenu:    '',
                },
                ConfigModal:{
                    ModalTitle:  "Formulario Menus",
                    ModalNameId: 'ModalForm',
                    EmitSeccion: EmitEjecuta,
                    ModalSize:   'lg',
                },
                oBtnSave: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitEjecuta,
                },
                ConfigModal2:{
                    ModalTitle:  "Asignación De Permisos",
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
                await this.$http.get('panelmenu', {
                    params:{
                        TxtBusqueda: this.Filtro.Nombre,
                        Entrada:     this.Filtro.Entrada,
                        Pag:         this.Filtro.Pagina,
                        TipoMenu:    this.Filtro.TipoMenu
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
                        this.$http.delete('panelmenu/'+Id)
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
            AsignarPermisos(item) {
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