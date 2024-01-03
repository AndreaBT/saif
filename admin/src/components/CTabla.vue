<template>
    <div class="">
        <input type="hidden" :disabled="Validate">
        <input type="hidden" :value="ValidateFiltros">

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li v-if="ConfigList.ShowTitleFirst" class="breadcrumb-item" @click="RunReturn">
                                    <a class="breadcrumb-a">{{ConfigList.TitleFirst}}</a>
                                </li>
                                <li v-if="ConfigList.ShowTitleEnd" class="breadcrumb-item active" aria-current="page">{{ConfigList.Title}}</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <form class="form-inline float-right">
                            <div class="form-group mr-1">
                                <button v-if="ConfigList.BtnReturnShow && ConfigList.TypeBody == 'Form'" @click="RunReturn" type="button" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-circle-left"></i>
                                    {{ConfigList.BtnReturnName}}
                                </button>
                            </div>

                            <div class="form-group mr-1">
                                <slot name="accHeader"></slot>
                            </div>

                            <div class="form-group">
                                <template v-if="ConfigList.BtnNewShow">
                                    <button type="button" @click="RunNew(0)" class="btn btn-primary btn-sm" >
                                        <i class="fas fa-plus-circle"></i> 
                                        {{ConfigList.BtnNewName}}
                                    </button>
                                </template>
                            </div>
                        </form>

                        <form class="form-inline float-right" v-if="ConfigList.ShowFiltros">
                            <div class="form-group" v-if="ConfigList.ShowSearch">
                                <input type="text" @input="Search" v-model="FiltroC.Nombre" class="form-control" :placeholder="FiltroC.Placeholder"  autocomplete="off">
                            </div>
                            <div class="form-group mx-sm-3" v-if="ConfigList.ShowEntradas">
                                <label for="item1" class="mr-1">Filas</label>
                                <select @change="Search" v-model="FiltroC.Entrada" class="form-control form-select" style="width: 72px;">
                                    <option :value="10">10</option>
                                    <option :value="25">25</option>
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                </select>
                            </div>
                            <div class="form-group mx-sm-3" v-if="ConfigList.ShowYear">
                                <label class="mr-1">Año:</label>
                                <select @change="Search" v-model="FiltroC.Anio" class="form-control form-select" style="width: 80px;">
                                    <option v-for="(itemY,indexY) in ListYears" :key="indexY" :value="itemY">{{itemY}}</option>
                                </select>
                            </div>
                            <div class="form-group mx-sm-3" v-if="ConfigList.ShowMonth">
                                <label class="mr-1">Mes:</label>
                                <select @change="Search" v-model="FiltroC.mes" class="form-control form-select" style="width: 120px;">
                                    <option v-for="(meses,ind) in ListMonths" :key="ind" :value="meses.mes">{{meses.Nombre}}</option>
                                </select>
                            </div>
                            <slot name="Filtros"></slot>
                        </form>
                    </div>
                </div>

                <div v-if="ConfigList.TypeBody == 'List'" class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card card-pri">
                            <slot name="btn-body"></slot>
                            <CLoader :pConfigLoad="ConfigLoad">
                                <template slot="BodyFormLoad">
                                    <table class="table table-responsive-lg">
                                        <thead>
                                            <tr>
                                            <slot name="header"></slot>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <slot name="body"></slot>
                                            <slot name="botones"></slot>
                                        </tbody>
                                    </table>
                                </template>
                            </CLoader>
                        </div>
                    </div>
                </div>

                <div v-else class="row mb-3">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <slot name="bodyForm"></slot>
                    </div>
                </div>
                
                <CPagina v-if="ConfigList.ShowPaginador" :Filtro="pFiltro" @Pagina="Search"></CPagina>
        </div>
    </div>
</template>

<script>

/*****
# pConfigList   == CONTIENE TODAS LAS CONFIGURACIONES VISUALES DEL CLIST, PARA CONTROLAR LOS ELEMENTOS DEL MISMO
# pFiltro       == CONTIENE TODAS LAS CONFIGURACIONES QUE SE NECESITAN PARA HACER EL FILTRADO, SIRVE TAMBIEN A CPAGINA
# segurity      == CONTIENE TODOS LOS PERMISOS PARA RESTRINGIR LA VISUALIZACION DEL BOTON NUEVO O ALGUNA ACCION ADICIONAL
*****/

export default {
    name:  "CTabla",
    props:['pConfigList','pFiltro','segurity'],
    components:{ },
    data() {
        return {
            ConfigList:{
                Title:          'Listado',
                TitleFirst:     'Inicio',
                IsModal:        true,
                BtnNewShow:     true,
                BtnNewName:     'Nuevo',
                BtnReturnShow:  false,
                BtnReturnName:  'Regresar',
                ShowFiltros:    true,
                ShowSearch:     true,
                ShowEntradas:   true,
                ShowPaginador:  true,
                ShowYear:       false,
                ShowMonth:      false,
                TypeBody:       'List',
                GoRoute:        '',
                EmitSeccion:    '',
                ShowTitleFirst: true,
                ShowTitleEnd:   true,
                Obj:            {}, //declara cualquier objeto para enviar al form
            },
            FiltroC:{
                Nombre:      '',
                Entrada:     50,
                Placeholder: 'Buscar..',
                Anio:        '',
                mes:         0,
            },
            ConfigLoad:{
                ShowLoader: true,
                ClassLoad:  true,
            },
            ListYears:[],
            ListMonths: [
                {mes:'0',Nombre:'Elija un mes'},
                {mes:'01',Nombre:'Enero'},
                {mes:'02',Nombre:'Febrero'},
                {mes:'03',Nombre:'Marzo'},
                {mes:'04',Nombre:'Abril'},
                {mes:'05',Nombre:'Mayo'},
                {mes:'06',Nombre:'Junio'},
                {mes:'07',Nombre:'Julio'},
                {mes:'08',Nombre:'Agosto'},
                {mes:'09',Nombre:'Septiembre'},
                {mes:'10',Nombre:'Octubre'},
                {mes:'11',Nombre:'Noviembre'},
                {mes:'12',Nombre:'Diciembre'},
            ],
            TimeOut: null,
        }
    },
    methods :{
        RunNew() {
            // EJECUTA EL EMIT DEL NUEVO PARA LLAMAR A LA FUNCION GET ONE DE LOS FORMULARIOS
            // SINO LLAMA AL ROUTER PUSH PARA IR A LA RUTA INDICADA
            
            if(this.ConfigList.IsModal==true) {
                this.bus.$emit('NewModal_'+this.ConfigList.EmitSeccion,0,this.ConfigList.Obj);
            }  else {
                this.$router.push({name:this.ConfigList.GoRoute,params:{Id:0,Obj:this.ConfigList.Obj}})
            }

        },
        RunReturn() {

            if(this.ConfigList.TypeBody == 'List' && this.ConfigList.BtnReturnShow == false) {
                this.$router.push({name: 'inicio'}); 
            } else {
                this.bus.$emit('EmitReturn');
            }

        },
        Search() {

            if(this.pFiltro !=undefined) {

                if(this.FiltroC.Entrada != this.pFiltro.Entrada) {
                    this.pFiltro.Pagina  = 1; 
                }
                
                this.pFiltro.Nombre  = this.FiltroC.Nombre;
                this.pFiltro.Entrada = this.FiltroC.Entrada;
                this.pFiltro.Anio    = this.FiltroC.Anio;
                
                if(this.FiltroC.Nombre!='') {
                    clearTimeout(this.TimeOut);

                    this.TimeOut = setTimeout(() => {
                        this.$emit('FiltrarC');
                    }, 1000);

                } else {
                    this.$emit('FiltrarC');
                }

            }
        },
        GetYearConfig() { 
            //this.ListYears = JSON.parse(sessionStorage.getItem('ListYears'));
        },
        GetMonthFilter(buscar) {
            let aBuscar  = buscar;
            let devuelve = '';

            let arr      = this.ListMonths.filter(function(vl) {
                
                if(aBuscar!='') {
                    
                    if(aBuscar == vl.mes) {
                        return vl;
                    }  else {
                        return '';
                    }

                }

            });

            if(arr[0]) {
                devuelve = arr[0].Nombre;
            }
            
            return devuelve;
        },
    },
    created() {
        this.GetYearConfig();
    },
    computed: {
        Validate() {

            if(this.pConfigList.Title != undefined){
                this.ConfigList.Title = this.pConfigList.Title;
            }

            if(this.pConfigList.IsModal != undefined){
                this.ConfigList.IsModal = this.pConfigList.IsModal;
            }

            if(this.pConfigList.BtnNewShow != undefined){
                this.ConfigList.BtnNewShow = this.pConfigList.BtnNewShow;
            }

            if(this.pConfigList.BtnNewName != undefined){
                this.ConfigList.BtnNewName = this.pConfigList.BtnNewName;
            }

            if(this.pConfigList.BtnReturnShow != undefined){
                this.ConfigList.BtnReturnShow = this.pConfigList.BtnReturnShow;
            }

            if(this.pConfigList.BtnReturnName != undefined){
                this.ConfigList.BtnReturnName = this.pConfigList.BtnReturnName;
            }

            if(this.pConfigList.ShowFiltros != undefined){
                this.ConfigList.ShowFiltros = this.pConfigList.ShowFiltros;
            }

            if(this.pConfigList.ShowSearch != undefined){
                this.ConfigList.ShowSearch = this.pConfigList.ShowSearch;
            }

            if(this.pConfigList.ShowEntradas != undefined){
                this.ConfigList.ShowEntradas = this.pConfigList.ShowEntradas;
            }

            if(this.pConfigList.ShowPaginador != undefined){
                this.ConfigList.ShowPaginador = this.pConfigList.ShowPaginador;
            }

            if(this.pConfigList.ShowYear != undefined){
                this.ConfigList.ShowYear = this.pConfigList.ShowYear;
            }

            if(this.pConfigList.ShowMonth != undefined){
                this.ConfigList.ShowMonth = this.pConfigList.ShowMonth;
            }

            if(this.pConfigList.ShowLoader != undefined){
                this.ConfigLoad.ShowLoader = this.pConfigList.ShowLoader;
            }

            if(this.pConfigList.ClassLoad != undefined){
                this.ConfigLoad.ClassLoad = this.pConfigList.ClassLoad;
            }

            if(this.pConfigList.GoRoute != undefined){
                this.ConfigList.GoRoute = this.pConfigList.GoRoute;
            }

            if(this.pConfigList.TypeBody != undefined){
                this.ConfigList.TypeBody = this.pConfigList.TypeBody;
            }

            if(this.pConfigList.EmitSeccion != undefined){
                this.ConfigList.EmitSeccion = this.pConfigList.EmitSeccion;
            }

            if(this.pConfigList.Obj != undefined){
                this.ConfigList.Obj = this.pConfigList.Obj;
            }

            if(this.pConfigList.TitleFirst != undefined){
                this.ConfigList.TitleFirst = this.pConfigList.TitleFirst;
            }

            if(this.pConfigList.ShowTitleFirst != undefined){
                this.ConfigList.ShowTitleFirst = this.pConfigList.ShowTitleFirst;
            }

            if(this.pConfigList.ShowTitleEnd != undefined){
                this.ConfigList.ShowTitleEnd = this.pConfigList.ShowTitleEnd;
            }


            return this.ConfigList.IsModal;
        },
        ValidateFiltros() {
            if(this.pFiltro !=undefined) {
                if(this.pFiltro.Nombre!=undefined){
                    this.FiltroC.Nombre = this.pFiltro.Nombre;
                }
                
                if(this.pFiltro.Placeholder!=undefined){
                    this.FiltroC.Placeholder = this.pFiltro.Placeholder;
                }
                
                if(this.pFiltro.Entrada!=undefined){
                    this.FiltroC.Entrada = this.pFiltro.Entrada;
                }
                
                if(this.pFiltro.Anio!=undefined){
                    this.FiltroC.Anio = this.pFiltro.Anio;
                }
                
            }
            
            return this.FiltroC.Placeholder;
        },
    },
}
</script>