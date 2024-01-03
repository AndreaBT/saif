<template>
    <div class="">
        <input type="hidden" :disabled="Validate">

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

            <div v-if="ConfigList.TypeBody == 'List'" class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
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
                IsModal:        true,
                BtnNewShow:     true,
                BtnNewName:     'Nuevo',
                BtnReturnShow:  false,
                BtnReturnName:  'Regresar',
                ShowPaginador:  true,
                TypeBody:       'List',
                GoRoute:        '',
                EmitSeccion:    '',
                Obj:            {}, //declara cualquier objeto para enviar al form
            },
            ConfigLoad:{
                ShowLoader: true,
                ClassLoad:  true,
            },
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
    },
    computed: {
        Validate() {

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

            if(this.pConfigList.ShowPaginador != undefined){
                this.ConfigList.ShowPaginador = this.pConfigList.ShowPaginador;
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


            return this.ConfigList.IsModal;
        },
    },
}
</script>