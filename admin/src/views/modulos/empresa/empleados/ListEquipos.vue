<template>
    <CTabla @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
        <template slot="header">
            <th class="td-sm"></th>
            <th>#</th>
            <th v-if="objEquipos.TipoHerramienta == 'Vehiculo'">Placa-Nombre</th>
            <th v-else>Nombre</th>
            <th>Modelo</th>
            <th v-show="objEquipos.TipoHerramienta == 'Telefono'">Telefono</th>
            <th class="text-center">Acciones</th>
        </template>

        <template slot="body">
            <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                <td class="td-sm"></td>
                <td>{{(index+1)}}</td>
                <td>
                    <template v-if="objEquipos.TipoHerramienta == 'Vehiculo'">
                        {{lista.Placa+' / '+lista.Nombre }}
                    </template>
                    <template v-else>
                        {{lista.Nombre }}
                    </template>
                </td>
                <td>{{lista.Modelo }}</td>
                <td v-if="objEquipos.TipoHerramienta == 'Telefono'">{{lista.Telefono }}</td>
                <td class="text-center">
                    <CBtnAccion :pShowBtnEdit="false" :pShowBtnDelete="false" :pIsModal="true" :pEmitSeccion="ConfigList.EmitSeccion">
                        <template slot="btnaccion">
                            <button type="button" v-if="lista.Visible" v-b-tooltip.hover.Top title="Agregar" class="btn btn-primary btn-icon" @click="AsignarEquipo(lista)">
                                <i class="fas fa-plus"></i>
                            </button>
                            <span v-else class="badge bg-success">
                                Asignado
                            </span>
                        </template>
                    </CBtnAccion>
                </td>
            </tr>
            <CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="10"></CSinRegistro>
        </template>
    </CTabla>
</template>

<script>

const EmitEjecuta = 'seccionEquipos';

export default {
    name:       'ListEquipos',
    props:      ['poBtnSave'],
    components: {  },
    data() {
        return{
            ConfigLoad:{
                ShowLoader: false,
                ClassLoad: false,
            },
            ConfigList:{
                Title: 'Listado de Equipos',
                ShowLoader: false,
                BtnNewShow: false,
                ShowTitleFirst: false,
                EmitSeccion:   EmitEjecuta,
            },
            Filtro:{
                Nombre: '',
                Pagina: 1,
                Entrada: 10,
                TotalItem: 0,
                Placeholder: 'Buscar..',
            },
            objEquipos:{},
            errorvalidacion: [],
            Emit: this.poBtnSave.EmitSeccion,
            segurity: {},
            obj: {},
            ListaArrayRows: [],
            ListaHeader: [],
        }
    },
    methods:{
        async Lista()
        {
            this.ConfigList.ShowLoader = true;
            
            await this.$http.get('equipos', {
                params:{
                    TxtBusqueda: this.Filtro.Nombre,
                    Entrada:     this.Filtro.Entrada,
                    Pag:         this.Filtro.Pagina,
                    TipoEquipo:  (this.objEquipos.TipoHerramienta == 'Telefono')?2:1,
                    Asignado:    'NO'
                }
            }).then( (res) => {
                this.ListaArrayRows     = res.data.data.data;
                this.Filtro.Pagina      = res.data.data.current_page;
                this.Filtro.TotalItem   = res.data.data.total;

                this.ListaArrayRows.forEach((item, index) => {
                    item.Visible = true
                    if(item.IdEquipo == this.objEquipos.IdEquipo){
                        item.Visible = false;
                    }
                });
            }).finally(()=>{
                this.ConfigList.ShowLoader = false;
            });
        },
        AsignarEquipo(item) {
            
            let obj = {
                IdEquipo: item.IdEquipo,
                Nombre:   item.Nombre,
                TipoHerr: this.objEquipos.TipoHerramienta
            }

            this.bus.$emit('pAsignarEquipo',obj);
            this.bus.$emit('CloseModal_'+this.Emit);
        },
    },
    created(){
        this.bus.$off('Recovery_'+this.Emit);
        this.bus.$on('Recovery_'+this.Emit,(obj)=>
        {
            this.objEquipos = obj
            this.Lista();
        });
    },
}
</script>
