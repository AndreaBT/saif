

<template slot="BodyFormLoad">
    <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="MontoSolicitud">Monto Solicitud</label>
                        <select id="MontoSolicitud" v-model="objPrestamo.IdMontoSolicitud"  name="MontoSolicitud" class="form-control form-select" @change="GetMonto();">
                            <option value="0">--Seleccionar--</option>
                            <option v-for="(item,index) in ListaMontosPrestamosArray" :key="index" :value="item.IdPrestamoMonto">{{item.Monto}}</option>
                        </select>
                        <label>
                            <CValidation v-if="this.errorvalidacion.IdMontoSolicitud" :Mensaje="'*'+errorvalidacion.IdMontoSolicitud[0]"></CValidation>
                        </label>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="Observaciones">Observaciones</label>
                        <b-form-textarea id="Observaciones" placeholder="Ingrese Observaciones" v-model="objPrestamo.Observaciones"></b-form-textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>

    import CValidation  from '@/components/CValidation.vue';
    import CBtnSave     from "@/components/CBtnSave";
    import Configs      from '@/helpers/VarConfig.js';
    const EmitEjecuta   =    "seccionCliente";

    export default {
        name:  "DetallesCliente",
        props: ["Id","poBtnSave"],
        components: {
            CBtnSave,CValidation
        },
        data() {
            return {
                RutaFile:                   "",
                RutaEvid:                   '',
                IdMontoSolicitud:           '',
                Img:                        null,
                MontoSeleccionado:          0,
                ValidElement:               Configs.validImage2m,
                Emit:                       this.poBtnSave.EmitSeccion,
                ListaMontosPrestamosArray:  [],
                ListaPrestamoCliente:       [],
                errorvalidacion:            [],
                ConfigList: {
                    ShowTitleFirst:     false,
                    Title:              "Detalles Prospecto",
                    ShowLoader:         true,
                    IsModal:            false,
                    BtnNewShow:         false,
                    BtnReturnShow:      true,
                    TypeBody:           "Form",
                    ShowFiltros:        false,
                    ShowFiltrosInline:  true,
                    ShowTitleInline:    false,
                    ShowPaginador:      false,
                },
                oBtnSave: {
                    toast:          0,
                    IsModal:        true,
                    ShowBtnSave:    true,
                    ShowBtnCancel:  false,
                    EmitSeccion:    EmitEjecuta,
                },
                objCliente: {
                    IdCliente:          0,
                    IdEstado:           0,
                    IdMunicipio:        0,
                    Nombre:             "",
                    ApellidoPat:        "",
                    ApellidoMat:        "",
                    FechaNacimiento:    "",
                    Correo:             "",
                    Telefono:           0,
                    DescripcionEmpresa: "",
                    Calle:              "",
                    NoInt:              "",
                    NoExt:              "",
                    Colonia:            "",
                    Cp:                 0,
                    Referencias:        "",
                    Prospecto:          "",
                    Estatus:            "",
                    Latitud:            0,
                    Longitud:           0,
                    Imagen:             "",
                },
                objPrestamo: {
                    IdPrestamo:         0,
                    IdCliente:          0,
                    IdRuta:             0,
                    IdEmpleado:         0,
                    IdCobratario:       0,
                    IdAutorizo:         0,
                    IdValidador:        0,
                    IdSucursal:         0,
                    FechaAutorizacion:  "",
                    FechaEntrega:       "",
                    FechaLiquidacion:   "",
                    MontoSolicitud:     0,
                    IdMontoSolicitud:   0,
                    MontoAutorizado:    0,
                    AdeudoTotal:        0,
                    Estatus:            "Pendiente",
                    Observaciones:      "",
                    MontoRechazo:       0,
                },
                masks: {
                    input: 'YYYY-MM-DD',
                },
            };
        },
        methods: {
            async Guardar() {
                this.errorvalidacion      = [];
                this.poBtnSave.toast      = 0;
                this.poBtnSave.DisableBtn = true;

                this.objPrestamo.IdCliente      = this.objCliente.IdCliente;
                this.objPrestamo.MontoSolicitud = this.MontoSeleccionado;
                this.objPrestamo.IdRuta         = this.ListaPrestamoCliente[0].IdRuta;
                this.objPrestamo.IdEmpleado     = this.ListaPrestamoCliente[0].IdEmpleado;
                this.objPrestamo.IdSucursal     = this.ListaPrestamoCliente[0].IdSucursal;
                this.objPrestamo.IdCobratario   = this.ListaPrestamoCliente[0].IdCobratario;
                this.objPrestamo.Origen         = 'web';

                if(this.objPrestamo.IdPrestamo == 0) {
                    this.$http.post('addPrestamoClienteActivo',
                        this.objPrestamo
                    ).then((res)=>{
                        this.EjecutaConExito(res);
                    }).catch(err=>{
                        this.EjecutaConError(err);
                    });
                } else {
                    this.$http.put('prestamos/'+this.objPrestamo.IdPrestamo,this.objPrestamo).then((res)=>{
                        this.EjecutaConExito(res);
                    }).catch(err=>{
                        this.EjecutaConError(err);
                    });
                }
            },
            EjecutaConExito(res) {
                this.poBtnSave.DisableBtn = false;
                this.poBtnSave.toast      = 1;
                this.bus.$emit('CloseModal_'+this.Emit);
                this.bus.$emit('List_'+this.Emit);
            },
            EjecutaConError(err) {
                this.poBtnSave.DisableBtn = false;

                if(err.response.data.errors){
                    this.errorvalidacion = err.response.data.errors;
                    this.poBtnSave.toast = 2;
                } else {
                    this.$toast.error(err.response.data.message);
                }

            },
            RecuperarPrestamos(){
                this.$http.get("clientesPrestamosActivos/"+this.objCliente.IdCliente)
                .then((res) => {
                    this.ListaPrestamoCliente = res.data.data.cliente;
                });
            },
            ListaMontosPrestamo() {
                this.$http.get("prestamosmontos", {
                    params: {
                        simple: 1
                    },
                })
                .then((res) => {
                    this.ListaMontosPrestamosArray = res.data.data;
                });
            },
            GetMonto() {
                var cod                = document.getElementById("MontoSolicitud").value;
                var combo              = document.getElementById("MontoSolicitud");
                var selected           = combo.options[combo.selectedIndex].text;
                this.MontoSeleccionado = selected;
            },

        },
        created() {
            this.bus.$off("Save_"+EmitEjecuta);
            this.bus.$off("EmitReturn");
            this.poBtnSave.toast = 0;
        },
        mounted() {
            this.ListaMontosPrestamo();

            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(Id)=> {
                this.poBtnSave.DisableBtn = false;

                this.bus.$off('Save_'+this.Emit);
                this.bus.$on('Save_'+this.Emit,()=> {
                    this.Guardar();
                });

                if(Id!='') {
                    this.objCliente.IdCliente = Id;
                    this.RecuperarPrestamos();
                }
                
            });

        },
    };

</script>
