<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
            
                <div class="container-fluid">
                    <div class="form-group row">
                        <div :class="(EsCobratario)?'col-12 col-sm-12 col-md-9 col-lg-9':'col-12 col-sm-12 col-md-12 col-lg-12'">
                            <label>Empleado:</label>
                            <span><strong> {{ObjUser.NombreCompleto}}</strong></span>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-right" v-if="EsCobratario">
                            <button type="button" @click="Transferir();" class="btn btn-sm btn-primary">
                                <i class="fa fa-send"></i> Transferir Todos
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div :class="(EsCobratario)?'col-12 col-sm-12 col-md-4 col-lg-4':'col-12 col-sm-12 col-md-12 col-lg-12'">
                            <label for="Nombre">Fecha Baja</label>
                            <v-date-picker :masks="masks" :popover="{ visibility: 'focus'}" locale="es" v-model="objBaja.FechaBaja" :maxDate="new Date()">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input class="form-control cal" name="FechaEntrega[]" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
                                </template>
                            </v-date-picker>
                            <CValidation v-if="this.errorvalidacion.FechaBaja" :Mensaje="'*'+errorvalidacion.FechaBaja[0]"/>
                        </div><!--fin col-12-->

                        <div class="col-12 col-sm-12 col-md-8 col-lg-8" v-if="EsCobratario">
                            <label for="">Transferir clientes a:</label>
                            <select v-model="objBaja.IdCobratario" class="form-control form-select">
                                <option value="0">Seleccione una opción</option>
                                <option v-for="(item,index) in ListUsersArr" :key="index" :value="item.IdUsuario">
                                    {{item.NombreCompleto+' - ('+item.Perfil+')'}}
                                </option>
                            </select>
                            <CValidation v-if="this.errorvalidacion.IdCobratario" :Mensaje="'*'+errorvalidacion.IdCobratario[0]"/>
                        </div>
                    </div>

                    <div class="row mt-3" v-if="EsCobratario">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-row justify-content-center">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">
                                    <h3 class="mb-2">Clientes</h3>
                                    <draggable id="div1" class="droppable bg-feed" :list="ListaPrestamos" group="rutas">
                                        <div class="list-group-item" v-for="(element, index) in ListaPrestamos" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }} 
                                        </div>
                                    </draggable>
                                </div>
                                
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">
                                    <h3 class="mb-2">Asignados</h3>
                                    <draggable id="div2" class="droppable" :list="ListaAsignados" group="rutas">
                                        <div class="list-group-item" v-for="(element, index) in ListaAsignados" :key="element.NombreCompleto">
                                            {{ index+1 }} {{ element.NombreCompleto }} 
                                        </div>
                                    </draggable>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </template>
    </CLoader>
</template>

<script>
import moment from "moment"

export default {
    name:"FormBaja",
    props:['poBtnSave'],
    components:{
    },
    data() {
        return {
            ConfigLoad:{
                ShowLoader: true,
                ClassLoad: false,
            },
            objBaja:{
                IdUsuario: 0,
                FechaBaja: '',
                IdCobratario: 0,
                IdPerfil: 0,
            },
            masks: {
                input: "YYYY-MM-DD",
            },
            ListUsersArr:   [],
            ListaPrestamos: [],
            ListaAsignados: [],
            errorvalidacion: [],
            Emit: this.poBtnSave.EmitSeccion,
            EsCobratario: false,
            ObjUser: {}
        }
    },
    methods :
    {
        async Guardar()
        {
            let Fecha1 = '';
			if(this.objBaja.FechaBaja!=''){
                Fecha1 = moment(this.objBaja.FechaBaja).format('YYYY-MM-DD');
            }

            this.objBaja.FechaBaja = Fecha1;
            this.objBaja.Prestamos = this.ListaAsignados;

            if(this.ObjUser.IdPerfil == 4 && this.ListaAsignados.length==0){
                this.$toast.warning('Debe Transferir al menos 1 cliente');
                return false;
            }

            this.errorvalidacion = [];
            this.poBtnSave.toast = 0; 
            this.poBtnSave.DisableBtn = true;

            this.$http.post(
                'ReasignarPrestamosUsuario',
                this.objBaja 
            ).then((res)=>{                 
                this.EjecutaConExito(res);
            }).catch(err=>{   
                this.EjecutaConError(err);
            });
        },
        EjecutaConExito(res)
        {
            this.poBtnSave.DisableBtn = false;  
            this.poBtnSave.toast = 1;
            this.bus.$emit('CloseModal_'+this.Emit);
            this.bus.$emit('List_'+this.Emit);
        },
        EjecutaConError(err)
        {
            this.poBtnSave.DisableBtn = false;
            
            if(err.response.data.errors){
                this.errorvalidacion = err.response.data.errors;
                this.poBtnSave.toast = 2;
            }
            else{
                this.$toast.error(err.response.data.message);
            }
        },
        Recuperar()
        {
            this.$http.get(
                "getPrestamosAsignados",{
                params:{
                    IdUsuario: this.objBaja.IdUsuario
                },
            }).then( (res) => {
                this.ListaPrestamos = res.data.data;
            }).finally(()=>{
                this.ConfigLoad.ShowLoader = false;
            });
        },
        Limpiar()
        {
            this.objBaja = {
                IdUsuario: 0,
                FechaBaja: '',
                IdCobratario: 0,
                IdPerfil: 0,
            };
            this.errorvalidacion = [];
        },
        async ListaCobratarios()
        {
            await this.$http.get('usuariosCobratarios', {
                params:{
                    Tipo: 4,
                    IdUsuario: this.ObjUser.IdUsuario,
                }
            }).then((res) => {
                this.ListUsersArr = res.data.data;
            });
        },
        Transferir()
        {
            this.ListaAsignados = this.ListaPrestamos;
            this.ListaPrestamos = [];
        }
    },
    created() {
        this.poBtnSave.toast = 0;

        this.bus.$off('Recovery_'+this.Emit);
        this.bus.$on('Recovery_'+this.Emit,(obj)=>
        {
            this.ConfigLoad.ShowLoader = true;    
            this.poBtnSave.DisableBtn = false;

            this.bus.$off('Save_'+this.Emit);
            this.bus.$on('Save_'+this.Emit,()=>
            {
                this.Guardar();
            });
            this.Limpiar();
            this.ObjUser = obj;
            this.ListaCobratarios();
            
            if(obj.IdUsuario!='') 
            {
                this.objBaja.IdUsuario = obj.IdUsuario;
                this.objBaja.IdPerfil = obj.IdPerfil;
                
                if(obj.IdPerfil == 4){
                    this.EsCobratario = true;
                    this.Recuperar();
                }
                this.ConfigLoad.ShowLoader = false;
            }
            else
            {
                this.ConfigLoad.ShowLoader = false;
            }
        });
    },
}
</script>