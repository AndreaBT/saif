<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
			<template slot="Filtros">
                <label  class="mr-1">Filtrar por Equipo</label>
				<select v-model="Filtro.TipoEquipo" class="form-control form-select mr-2" @change="Lista" style="width: 140px;">
                    <option value="0">--Seleccionar--</option>
					<option value="1">Vehículo</option>
					<option value="2">Teléfono</option>
                </select>
            </template>

            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Nombre</th>
                <th>Tipo del Equipo</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Color</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{(index+1)}}</td>
                    <td>{{lista.Nombre}}</td>
                    <td>
                        <template v-if="lista.TipoEquipo == 1">Vehículo</template>
                        <template v-else>Teléfono</template>
                    </td>
                    <td>{{lista.Modelo}}</td>
                    <td>{{lista.Marca}}</td>
                    <td>{{lista.Color}}</td>
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdEquipo" :pEmitSeccion="ConfigList.EmitSeccion">
							<template slot="btnaccion">
                                <button type="button" @click="verBitacora(lista);" v-b-tooltip.hover.Top title="Ver Bitacora" class="btn btn-icon btn-warning">
                                    <i class="fas fa-history"></i>
                                </button>
                            </template>
						</CBtnAccion>
                    </td>
                </tr>
                <CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="10"></CSinRegistro>
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

    import Form       from '@/views/catalogos/equipos/Form.vue';
    import Configs    from '@/helpers/VarConfig.js';
    const EmitEjecuta =    'seccionEquipos';

    export default {
        name: 'ListaEquipos',
        components: {
            Form,
        },
        data() {
            return {
                ConfigList:{
                    Title:         'Listado Equipos',
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
                    TipoEquipo:  '0'
                },
                ConfigModal:{
                    ModalTitle:  "Formulario Equipos",
                    ModalSize:   'lg',
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

                await this.$http.get('equipos', {
                    params:{
                        TxtBusqueda: this.Filtro.Nombre,
                        Entrada:     this.Filtro.Entrada,
                        Pag:         this.Filtro.Pagina,
                        TipoEquipo:  this.Filtro.TipoEquipo,
                    }
                }).then( (res) => {
                    this.ListaArrayRows = res.data.data.data;
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Eliminar(Id){
                this.$swal(Configs.configEliminar).then((result) => {
                    if(result.value) {
                        this.$http.delete('equipos/'+Id)
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
            verBitacora(lista){
                this.$router.push({name:'bitacoraequipos',params:{pObjEquipo:lista}});

            }
        },
        created() {
            this.bus.$off('Delete_'+EmitEjecuta);
            this.bus.$off('List_'+EmitEjecuta);
        },
        mounted() {
            this.Lista();
            this.bus.$on('Delete_'+EmitEjecuta,(Id)=>
            {
                this.Eliminar(Id);
            });

            this.bus.$on('List_'+EmitEjecuta,()=>
            {
                this.Lista();
            });
        },
    }

</script>
