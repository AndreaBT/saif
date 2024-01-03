<template>
    <div>
		<CList @FiltrarC="Lista" :pConfigList="ConfigList" :pFiltro="Filtro" :segurity="segurity">
            <template slot="header">
                <th class="td-sm"></th>
                <th>#</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th class="text-center">Acciones</th>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaArrayRows" :key="index" >
                    <td class="td-sm"></td>
                    <td>{{(index+1)}}</td>
                    <td>{{lista.NombreCompleto }}</td>   
                    <td>{{lista.Correo }}</td>
                    <td>{{lista.Telefono }}</td>
                    <td class="text-center">
                        <CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="false" :pId="lista.IdEmpleado" :pEmitSeccion="ConfigList.EmitSeccion" pGoRoute="empleadosave">
							<template slot="btnaccion">
                                <button type="button" @click="DarDeBajaUsuario(lista)" v-b-tooltip.hover.Top title="Dar de Baja" class="btn btn-warning btn-icon">
                                    <i class="fa fa-user-slash"></i>
                                </button>
                            </template>
						</CBtnAccion>  
                    </td>  
                </tr>
                <CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="7"></CSinRegistro> 
            </template>
        </CList>

        <CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave">
			<template slot="Form">
				<FormBaja :poBtnSave="oBtnSave"></FormBaja>
			</template>
		</CModal>
	</div>
</template>

<script>
import Configs    from '@/helpers/VarConfig.js';
import FormBaja   from '@/views/modulos/empresa/empleados/FormBaja.vue';
const EmitEjecuta =    'seccionEmpleadosList';
const EmitBaja    =    'seccionDarDeBaja';

export default {
    name: 'ListaEmpleados',
    components: {
        FormBaja
    },
    data() {
        return {
            ConfigList:{
                Title:         'Listado De Empleados',
                IsModal:       false,
                ShowLoader:    true,
                BtnReturnShow: false,
                EmitSeccion:   EmitEjecuta, 
                GoRoute:       'empleadosave',
            },
            Filtro:{
                Nombre:      '',
                Pagina:      1,
                Entrada:     10,
                TotalItem:   0,
                Placeholder: 'Buscar..',
            }, 
            ConfigModal:{
                ModalTitle:  "Dar de baja al Usuario",
                ModalNameId: 'ModalForm',
                EmitSeccion:  EmitBaja,
                ModalSize:   'lg',
            },
            oBtnSave: {
                toast: 0,
                IsModal: true,
                DisableBtn: false,
                EmitSeccion: EmitBaja,
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
            
            await this.$http.get('users', {
                params:{
                    TxtBusqueda: this.Filtro.Nombre,
                    Entrada: this.Filtro.Entrada,
                    Pag: this.Filtro.Pagina,
                }
            }).then( (res) => {
                this.ListaArrayRows     = res.data.data.usuarios.data;
                this.Filtro.Pagina      = res.data.data.usuarios.current_page;
                this.Filtro.TotalItem   = res.data.data.usuarios.total;
            }).finally(()=>{
                this.ConfigList.ShowLoader = false;
            });
        },
        Eliminar(Id){
            this.$swal(Configs.configEliminar).then((result) => {
                if(result.value)
                {                
                    this.$http.delete('users/'+Id)
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
        DarDeBajaUsuario(item)
        {
            this.ConfigModal.ModalSize = 'md';
            if(item.IdPerfil == 4){
                this.ConfigModal.ModalSize = 'lg';
            }

            this.bus.$emit('NewModal_'+EmitBaja,item);
        }
    },
    created()
    {
        this.bus.$off('Delete_'+EmitEjecuta);
        this.bus.$off('List_'+EmitEjecuta);
    },
    mounted()
    {
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