<template>
    <div>
        <CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="Filtros">
                <select v-model="Filtro.TipoBitacora" class="form-control form-select" @change="Lista">
                    <option value="0">Todos</option>
                    <option value="Asignacion">Asingnación</option>
                    <option value="Modificacion">Modificacion</option>
                </select>
            </template>
            <template slot="header">
                <th class="td-sm"></th>
                <th>Fecha</th>
                <th>Realizo</th>
                <th>Descripcion</th>

                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{ $getCleanDate(lista.FechaEvento) }}</td>
                    <td>{{lista.Realizo}}</td>
                    <td>{{$limitCharacters(lista.Descripcion,80)}}</td>

                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="false" :pShowBtnDelete="false" :pIsModal="true" :pId="lista.IdBitacoraEquipo" :pEmitSeccion="ConfigList.EmitSeccion">
                            <template slot="btnaccion">
                                <button type="button" @click="verDetalle(lista);" v-b-tooltip.hover.Top title="ver detalle" class="btn btn-icon btn-primary ml-1">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </template>
                        </CBtnAccion>
                    </td>
                </tr>
                <CSinRegistros :pContIF="ListaArrayRows.length" :pColspan="10"></CSinRegistros>
            </template>
        </CList>

        <CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave">
            <template slot="Form">
                <ResumenBitacora :poBtnSave="oBtnSave"></ResumenBitacora>
            </template>
        </CModal>
    </div>
</template>

<script>

    import CList           from "../../../../components/CList";
    import CBtnAccion      from "../../../../components/CBtnAccion";
    import CSinRegistros   from "../../../../components/CSinRegistros";
    import CModal          from "../../../../components/CModal";
    import ResumenBitacora from "./ResumenBitacora";
    const EmitEjecuta      =    'seccionBitacoraEquipo';

    export default {
        name:  'ListaEquipos',
        props: ['pObjEquipo'],
        components: {
            CList,
            CBtnAccion,
            CSinRegistros,
            CModal,
            ResumenBitacora},
        data() {
            return {
                ConfigList:{
                    Title:         'Bitacora de Equipo',
                    TitleFirst:    'Equipos',
                    IsModal:       true,
                    ShowLoader:    true,
                    BtnReturnShow: true,
                    BtnNewShow:    false,
                    EmitSeccion:   EmitEjecuta,
                },
                Filtro:{
                    Nombre:      '',
                    Pagina:      1,
                    Entrada:     10,
                    TotalItem:   0,
                    Placeholder: 'Buscar..',
                    TipoBitacora: '0',
                },
                ConfigModal:{
                    ModalTitle:  "Detalle de bitacora",
                    ModalSize:   'lg',
                    ShowFooter:  false,
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
                ObjEquipo:      {}
            }
        },
        methods: {
            async Lista(){
                this.ConfigList.ShowLoader = true;

                await this.$http.get('equipobitacora', {
                    params:{
                        TxtBusqueda:    this.Filtro.Nombre,
                        Entrada:        this.Filtro.Entrada,
                        Pag:            this.Filtro.Pagina,
                        TipoBitacora:   this.Filtro.TipoBitacora,
                        IdEquipo:       this.ObjEquipo.IdEquipo
                    }
                }).then( (res) => {
                    this.ListaArrayRows   = res.data.data.data;
                    this.Filtro.Pagina    = res.data.data.current_page;
                    this.Filtro.TotalItem = res.data.data.total;
                }).finally(()=>{
                    this.ConfigList.ShowLoader = false;
                });
            },
            Regresar() {
                this.$router.push({name:'equipos',params:{}});
            },
            verDetalle(lista) {
                //console.log(lista);
                this.bus.$emit('NewModal_'+EmitEjecuta,lista.IdBitacoraEquipo);
            }
        },
        created() {

            if(this.pObjEquipo !== undefined){
                sessionStorage.setItem('oEquipo',JSON.stringify(this.pObjEquipo));
            }

            this.ObjEquipo        = JSON.parse(sessionStorage.getItem('oEquipo'));
            this.ConfigList.Title = this.ConfigList.Title+': '+this.ObjEquipo.Nombre;

            this.bus.$off('List_'+EmitEjecuta);
            this.bus.$off('EmitReturn');
        },
        mounted() {
            this.Lista();

            this.bus.$on('List_'+EmitEjecuta,()=> {
                this.Lista();
            });

            this.bus.$on('EmitReturn',()=> {
                this.Regresar();
            });
        },

    }

</script>
