<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Municipio</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{(index+1)}}</td>
                    <td>{{lista.Nombre }}</td>   
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdMunicipio" :pEmitSeccion="ConfigList.EmitSeccion">
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

    import Form        from '@/views/catalogos/municipios/Form.vue';
    import Configs     from '@/helpers/VarConfig.js';
    const  EmitEjecuta =    'seccionMunicipios';

    export default {
        name:  'ListaMunicipios',
        props: ['pObjEstado'],
        components: { 
            Form,
        },
        data() {
            return {
                ListaArrayRows: [],
                ListaHeader:    [],
                segurity:       {},
                obj:            {},
                ObjEstado:      {},
                ConfigList:{
                    Title:          'Listado Municipios',
                    TitleFirst:     'Estados',
                    IsModal:        true,
                    ShowLoader:     true,
                    BtnReturnShow:  true,
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
                    ModalTitle:  "Formulario Municipios",
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
                await this.$http.get('municipios', {
                    params:{
                        TxtBusqueda: this.Filtro.Nombre,
                        Entrada:     this.Filtro.Entrada,
                        Pag:         this.Filtro.Pagina,
                        IdEstado:    this.ObjEstado.IdEstado,
                    }
                }).then( (res) => {
                    this.ListaArrayRows   = res.data.data.municipio.data;
                    this.Filtro.Pagina    = res.data.data.municipio.current_page;
                    this.Filtro.TotalItem = res.data.data.municipio.total;
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Eliminar(Id) {
                this.$swal(Configs.configEliminar).then((result) => {
                    if(result.value) {                
                        this.$http.delete('municipios/'+Id).then( (res) => {
                            this.$swal(Configs.configEliminarConfirm);
                            this.Lista();
                        })
                        .catch( err => {
                            this.$toast.error(err.response.data.message);
                        });
                    }
                });
            },
            Regresar() {
                this.$router.push({name:'estados',params:{}});
            }
        },
        created() {

            if(this.pObjEstado != undefined){
                sessionStorage.setItem('oEstado',JSON.stringify(this.pObjEstado));
            }

            this.ObjEstado = JSON.parse(sessionStorage.getItem('oEstado'));

            this.bus.$off('Delete_'+EmitEjecuta);
            this.bus.$off('List_'+EmitEjecuta);
            this.bus.$off('EmitReturn');
        },
        mounted() {
            this.Lista();

            this.bus.$on('Delete_'+EmitEjecuta,(Id) => {
                this.Eliminar(Id);
            });

            this.bus.$on('List_'+EmitEjecuta,() => {
                this.Lista();
            });

            this.bus.$on('EmitReturn',() => {
                this.Regresar();
            });

        },
    }

</script>