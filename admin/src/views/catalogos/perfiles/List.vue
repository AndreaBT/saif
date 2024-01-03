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
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="false" :pIsModal="true" :pId="lista.IdPerfil" :pEmitSeccion="ConfigList.EmitSeccion">
							<template slot="btnaccion">
                                <template v-if="lista.isDefault !== 1">
                                    <button type="button" v-b-tooltip.hover.Top title="Eliminar" class="btn btn-danger btn-icon" @click="Eliminar(lista.IdPerfil)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </template>


                                <button type="button" @click="AsignarPermisos(lista);" v-b-tooltip.hover.Top title="Asignar Permisos" class="btn btn-icon btn-warning">
                                    <i class="fa fa-lock"></i>
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
    </div>
</template>

<script>

    import Form       from '@/views/catalogos/perfiles/Form.vue';
    import Configs    from '@/helpers/VarConfig.js';
    const EmitEjecuta =    'seccionPerfil';

    export default {
        name: 'ListaPerfiles',
        components: {
            Form,
        },
        data() {
            return {
                ListaArrayRows: [],
                ListaHeader:    [],
                segurity:       {},
                obj:            {},
                ConfigList:{
                    Title:          'Listado Perfiles',
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
                    ModalTitle:  "Formulario Perfil",
                    ModalNameId: 'ModalForm',
                    EmitSeccion: EmitEjecuta,
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

                await this.$http.get('perfiles', {
                    params:{
                        TxtBusqueda: this.Filtro.Nombre,
                        Entrada:     this.Filtro.Entrada,
                        Pag:         this.Filtro.Pagina,
                    }
                }).then( (res) => {
                    this.ListaArrayRows   = res.data.data.perfiles.data;
                    this.Filtro.Pagina    = res.data.data.perfiles.current_page;
                    this.Filtro.TotalItem = res.data.data.perfiles.total;
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Eliminar(Id) {
                this.$swal(Configs.configEliminar).then((result) => {
                    if(result.value) {
                        this.$http.delete('perfiles/'+Id)
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
                this.$router.push({name: 'asignarPermisos',params:{pObjPerfil:item}});
            }
        },
        created()
        {
            this.bus.$off('List_'+EmitEjecuta);
        },
        mounted()
        {
            this.Lista();

            this.bus.$on('List_'+EmitEjecuta,()=>
            {
                this.Lista();
            });
        },
    }

</script>
