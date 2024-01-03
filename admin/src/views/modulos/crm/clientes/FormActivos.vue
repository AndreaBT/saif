<template>
<div>
    <CList :pConfigList="ConfigList" >
		<template slot="bodyForm">
			<CLoader :pConfigLoad="ConfigLoad">
				<template slot="BodyFormLoad">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
								General
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
								Préstamos
							</a>
						</li>
					</ul>
					<div class="tab-content shadow-sm" id="myTabContent">
                        <!-- Parate uno -->
                        <div class="tab-pane fade active show" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 mt-3">
                                        <fieldset>
                                            <legend class="col-form-label">Datos Personales</legend>
                                            <div class="form-row mt-4">
                                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                                                    <div class="avatar-upload">
                                                        <div class="avatar-edit">
                                                                <input id="file" @change="$uploadImagePreview($event,ValidElement, Array('Img','imagePreview'))" ref="file" type="file" name="file" accept=".png, .jpg, .jpeg">
                                                            <label for="file"></label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview" :style="'background-image: url('+RutaFile+objCliente.UrlImg+');'" :src="RutaFile+objCliente.UrlImg">
                                                            </div>
                                                                
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
                                                    <!-- <legend class="col-form-label">Datos personales</legend> -->
                                                    <div class="form-group form-row">
                                                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            <label for="Nombre">Nombre</label>
                                                            <b-form-input id="Nombre" v-model="objCliente.Nombre" type="text" placeholder="Ingrese Nombre" ></b-form-input>
                                                            <CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*' + errorvalidacion.Nombre[0]"/>
                                                        </div>

                                                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            <label for="ApellidoPat">Apellido Paterno</label>
                                                            <b-form-input id="ApellidoPat" v-model="objCliente.ApellidoPat" type="text" placeholder="Ingrese Apellido Paterno" ></b-form-input>
                                                            <CValidation v-if="this.errorvalidacion.ApellidoPat" :Mensaje="'*' + errorvalidacion.ApellidoPat[0]"/>
                                                        </div>

                                                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            <label for="ApellidoMat">Apellido Materno</label>
                                                            <b-form-input id="ApellidoMat" v-model="objCliente.ApellidoMat" type="text" placeholder="Ingrese Apellido Materno" ></b-form-input>
                                                            <CValidation v-if="this.errorvalidacion.ApellidoMat" :Mensaje="'*' + errorvalidacion.ApellidoMat[0]"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-row">
                                                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label for="FechaNacimiento">Fecha de Nacimiento</label>
                                                            <v-date-picker :masks="masks" :popover="{ visibility: 'focus' }" locale="mx" v-model="objCliente.FechaNacimiento">
                                                                <template v-slot="{ inputValue, inputEvents }">
                                                                    <input class="form-control cal" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
                                                                </template>
                                                            </v-date-picker>
                                                            <CValidation v-if="this.errorvalidacion.FechaNacimiento" :Mensaje="'*' + errorvalidacion.FechaNacimiento[0]"/>
                                                        </div>

                                                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label for="Telefono">Teléfono</label>
                                                            <input
                                                                id="Telefono"
                                                                v-model="objCliente.Telefono"
                                                                type="text"
                                                                placeholder="Ingrese Teléfono"
                                                                @input="$onlyNums($event,objCliente,'Telefono');"
                                                                class="form-control"/>
                                                            <CValidation v-if="this.errorvalidacion.Telefono" :Mensaje="'*' + errorvalidacion.Telefono[0]"/>
                                                        </div>

                                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                            <label for="Correo">Correo</label>
                                                            <b-form-input id="Correo" v-model="objCliente.Correo" type="email" placeholder="Ingrese Correo " ></b-form-input>
                                                            <CValidation v-if="this.errorvalidacion.Correo" :Mensaje="'*' + errorvalidacion.Correo[0]"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                </div>
                                            </div>
                                        </fieldset>

                                        <fieldset class="mt-2">
                                            <legend class="col-form-label">Negocio</legend>
                                            <div class="form-group form-row">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                    <label for="DescripcionEmpresa">Descripción Empresa</label>
                                                    <b-form-textarea
                                                        id="DescripcionEmpresa"
                                                        v-model="objCliente.DescripcionEmpresa"
                                                        placeholder="Ingrese Descripción Empresa"
                                                    ></b-form-textarea>
                                                    <CValidation v-if="this.errorvalidacion.DescripcionEmpresa" :Mensaje="'*' + errorvalidacion.DescripcionEmpresa[0]"/>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <fieldset class="mt-2">
                                            <legend class="col-form-label">Domicilio</legend>
                                            <div class="form-group form-row">
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <label for="Calle">Calle</label>
                                                    <b-form-input
                                                        id="Calle"
                                                        v-model="objCliente.Calle"
                                                        type="text"
                                                        placeholder="Ingrese Calle"
                                                    ></b-form-input>
                                                    <CValidation v-if="this.errorvalidacion.Calle" :Mensaje="'*' + errorvalidacion.Calle[0]"/>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label for="NoExt">Número Exterior</label>
                                                    <b-form-input
                                                        id="NoExt"
                                                        v-model="objCliente.NoExt"
                                                        type="text"
                                                        placeholder="Ingrese Número Exterior"
                                                    ></b-form-input>
                                                    <CValidation v-if="this.errorvalidacion.NoExt" :Mensaje="'*' + errorvalidacion.NoExt[0]"/>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label for="NoInt">Número Interior</label>
                                                    <b-form-input
                                                        id="NoInt"
                                                        v-model="objCliente.NoInt"
                                                        type="text"
                                                        placeholder="Ingrese Número Interior"
                                                    ></b-form-input>
                                                    <CValidation v-if="this.errorvalidacion.NoInt" :Mensaje="'*' + errorvalidacion.NoInt[0]"/>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label for="Cp">Código Postal</label>
                                                    <input
                                                        id="Cp"
                                                        v-model="objCliente.Cp"
                                                        type="text"
                                                        placeholder="Ingrese Código Postal"
                                                        @input="$onlyNums($event,objCliente,'Cp');"
                                                        class="form-control"
                                                    />
                                                    <CValidation v-if="this.errorvalidacion.Cp" :Mensaje="'*' + errorvalidacion.Cp[0]"/>
                                                </div>
                                            </div>

                                            <div class="form-group form-row">
                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="Colonia">Colonia</label>
                                                    <b-form-input
                                                        id="Colonia"
                                                        v-model="objCliente.Colonia"
                                                        type="text"
                                                        placeholder="Ingrese Colonia"
                                                    ></b-form-input>
                                                    <CValidation v-if="this.errorvalidacion.Colonia" :Mensaje="'*' + errorvalidacion.Colonia[0]"/>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="Estado">Estado</label>
                                                    <select id="Estado" class="form-control form-select" v-model="objCliente.IdEstado" @change="ListaMunicipios()">
                                                        <option :value="0">--Seleccionar--</option>
                                                        <option v-for="(item, index) in ListaEstadosArray" :key="index" :value="item.IdEstado">
                                                            {{ item.Nombre }}
                                                        </option>
                                                    </select>
                                                    <CValidation v-if="this.errorvalidacion.IdEstado" :Mensaje="'*' + errorvalidacion.IdEstado[0]"/>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="Municipio">Municipio</label>
                                                    <select v-model="objCliente.IdMunicipio" id="Municipio" class="form-control form-select">
                                                        <option :value="0">--Seleccionar--</option>
                                                        <option v-for="(item, index) in ListaMunicipiosArray" :key="index" :value="item.IdMunicipio" >
                                                            {{ item.Nombre }}
                                                        </option>
                                                    </select>
                                                    <CValidation v-if="this.errorvalidacion.IdMunicipio" :Mensaje="'*' + errorvalidacion.IdMunicipio[0]"/>
                                                </div>
                                            </div>

                                            <div class="form-group form-row">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                    <label for="Referencias">Referencias</label>
                                                    <b-form-textarea
                                                        id="Referencias"
                                                        v-model="objCliente.Referencias"
                                                        placeholder="Ingrese Referencias"
                                                    ></b-form-textarea>
                                                    <CValidation v-if="this.errorvalidacion.Referencias" :Mensaje="'*' + errorvalidacion.Referencias[0]"/>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <!--GESTOR ASIGNADO-->
                                        <fieldset  class="mt-2">
                                            <legend class="col-form-label">Gestor Asignado</legend>
                                            <div class="form-group form-row">
                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Sucursal</label>
                                                    <b-form-input readonly v-model="Sucursales" type="text" ></b-form-input>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Ruta</label>
                                                    <b-form-input readonly v-model="NombreRuta" type="text" ></b-form-input>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Cobratario</label>
                                                    <b-form-input readonly v-model="NombreCompleto" type="text" ></b-form-input>
                                                </div>
                                                
                                            </div>
                                            <!-- <label >Ruta</label><label for="">{{NombreRuta}}</label> -->
                                            <!-- <input type="text" name="" v-model="NombreRuta" id=""> -->
                                            
                                        </fieldset>
                                    </div>
                                </div>
                                <CBtnSave :poBtnSave="oBtnSave" />

                            </div>
                        </div>
                        <!-- fin parte uno -->

                        <!-- Parte dos -->
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <div class="container-fluid">
                                <fieldset>
                                    <legend class="col-form-label">Préstamo</legend>
                                    <div class="row">
                                        <div class="col-md-12 jutify-content-end">
                                            <button type="button" @click="openPrestamo()" class="btn btn-primary btn-sm float-right mt-1 mb-3">
                                                <i class="fas fa-plus-circle"></i> Nuevo Prestamo
                                            </button>
                                        </div>
                                    </div>
                                    <CTablita :pConfigList="ConfigList3">
                                        <template slot="header">
                                            <th>#</th>
                                            <th>Estatus</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                        </template>
                                        <template slot="body">      
                                            <tr  v-for="(item,index) in ListaPrestamoCliente"  :key="index">
                                                <td><strong>{{index+1}}</strong></td>
                                                <td>
                                                    <b-badge v-if="item.Estatus=='Entregado'" pill variant="success">{{ item.Estatus }}</b-badge>
                                                    <b-badge v-if="item.Estatus=='Cobranza'" pill variant="success">{{ item.Estatus }}</b-badge>
                                                    <b-badge v-if="item.Estatus=='Asignado'" pill variant="success">{{ item.Estatus }}</b-badge>
                                                    <b-badge v-if="item.Estatus=='Rechazado'" pill variant="danger">{{ item.Estatus }}</b-badge>
                                                    <b-badge v-if="item.Estatus=='Pendiente'" pill variant="primary">{{ item.Estatus }}</b-badge>
                                                    <b-badge v-if="item.Estatus=='PreAutorizado'" pill variant="warning">{{ item.Estatus }}</b-badge>
                                                </td>
                                                
                                                <td>
                                                    {{ $formatNumber(item.MontoSolicitud,'$') }}
                                                </td>
                                                
                                                <td>
                                                    {{formato(item.created_at)}}
                                                </td>
                                                
                                            </tr>
                                        </template>
                                    </CTablita>
                                </fieldset>
                            </div>
                        </div>
                        <!-- fin Parte dos -->
                    </div>
                                            
                </template>
            </CLoader>
        </template>
    </CList>

    <CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave2">
        <template slot="Form">
            <FormPrestamo :poBtnSave="oBtnSave2"></FormPrestamo>
        </template>
	</CModal>
</div>
	
</template>

<script>

    import CBtnSave     from "@/components/CBtnSave";
    import Configs      from "@/helpers/VarConfig.js";
    import CValidation  from "@/components/CValidation.vue"
    import FormPrestamo from "@/views/modulos/crm/clientes/FormPrestamo.vue";
    import moment       from "moment";
    const EmitEjecuta   =    "seccionClienteActivo";
    const EmitEjecuta2  =    "seccionClienteActivo2";

    export default {
        name:  "DetallesCliente",
        props: ["Id"],
        components: {
            CBtnSave,CValidation,FormPrestamo
        },
        data() {
            return {
                NombreRuta:                 "",
                Sucursales:                 "",
                NombreCompleto:             "",
                Img:                        null,
                RutaFile:                   "",
                RutaEvid:                   '',
                ValidElement:               Configs.validImage2m,
                IneArrayRows:               [],
                LocalArrayRows:             [],
                errorvalidacion:            [],
                ListaRutasArray:            [],
                ListaEstadosArray:          [],
                DomilicioArrayRows:         [],
                ListaMunicipiosArray:       [],
                ListaSucursalesArray:       [],
                ListaRutasEmpleadosArray:   [],
                ListaMontosPrestamosArray:  [],
                ListaINEArray:              [],
                ListaDomicilioArray:        [],
                ListaEmpresaArray:          [],
                ListaPrestamoCliente:       [],
                MontoSeleccionado:          0,
                ListaRuta:                  [],
                segurity:                   {},
                ConfigList: {
                    ShowTitleFirst: false,
                    Title:             "Detalles Prospecto",
                    ShowLoader:        true,
                    IsModal:           false,
                    BtnNewShow:        false,
                    BtnReturnShow:     true,
                    TypeBody:          "Form",
                    ShowFiltros:       false,
                    ShowFiltrosInline: true,
                    ShowTitleInline:   false,
                    ShowPaginador:     false,
                },
                oBtnSave: {
                    toast:         0,
                    IsModal:       false,
                    ShowBtnSave:   true,
                    ShowBtnCancel: false,
                    DisableBtn:    false,
                    EmitSeccion:   EmitEjecuta,
                },
                ConfigLoad: {
                    ShowLoader: true,
                    ClassLoad:  false,
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
                    IdRuta:             0
                },
                masks: {
                    input: 'YYYY-MM-DD',
                },
                Filtro: {
                    Nombre:         "",
                    Pagina:         1,
                    Entrada:        10,
                    TotalItem:      0,
                    Placeholder:    "Buscar..",
                },
                oBtnSave2: {
                    toast:       0,
                    IsModal:     true,
                    DisableBtn:  false,
                    EmitSeccion: EmitEjecuta2,
                },
                ConfigList2:{
                    Title:         'Prestamos',
                    IsModal:       true,
                    ShowLoader:    true,
                    BtnReturnShow: false,
                    EmitSeccion:   EmitEjecuta2, 
                },
                ConfigModal: {
                    ModalTitle:  "Nuevo Prestamo",
                    ModalNameId: "ModalForm",
                    EmitSeccion: EmitEjecuta2,
                    ModalSize:   "md",
                },
                ConfigList3: {
                    ShowLoader:     false,
                    IsModal:        false,
                    BtnReturnShow:  true,
                    ShowSearch:     false,
                    ShowPaginador:  false,
                    ShowEntradas:   false,
                    BtnNewShow:     false,
                    TypeBody:       'List',
                    ShowTitleFirst: false,
                    EmitSeccion:    EmitEjecuta,
                },
                objRuta: {
                    NombreRuta: ""
                },
            };
        },
        methods: {
            async Guardar()
            {
                this.errorvalidacion     = [];
                this.oBtnSave.toast      = 0;
                this.oBtnSave.DisableBtn = true;

                let Fecha1 = '';
                if(this.objCliente.FechaNacimiento!=''){
                    Fecha1 = moment(this.objCliente.FechaNacimiento).format('YYYY-MM-DD');
                }

                // CLIENTE
                let formData = new FormData();
                formData.set("origin", "web");
                formData.set("IdCliente",           this.objCliente.IdCliente);
                formData.set("Nombre",              this.objCliente.Nombre);
                formData.set("ApellidoPat",         this.objCliente.ApellidoPat);
                formData.set("ApellidoMat",         this.objCliente.ApellidoMat);
                formData.set("Telefono",            this.objCliente.Telefono);
                formData.set("FechaNacimiento",     Fecha1);
                formData.set("Correo",              this.objCliente.Correo);
                formData.set("DescripcionEmpresa",  this.objCliente.DescripcionEmpresa);
                formData.set("IdEstado",            this.objCliente.IdEstado);
                formData.set("IdMunicipio",         this.objCliente.IdMunicipio);

                formData.set("Calle",               this.objCliente.Calle);
                formData.set("NoInt",               this.objCliente.NoInt);
                formData.set("NoExt",               this.objCliente.NoExt);
                formData.set("Colonia",             this.objCliente.Colonia);
                formData.set("Cp",                  this.objCliente.Cp);
                formData.set("Referencias",         this.objCliente.Referencias);

                formData.set("Prospecto",           this.objCliente.Prospecto);
                formData.set("Estatus",             this.objCliente.Estatus);
                formData.set("Latitud",             this.objCliente.Latitud);
                formData.set("Longitud",            this.objCliente.Longitud);

                formData.set("ImagenPrevious",      this.objCliente.Imagen);

                let Imagen = this.$refs.file.files[0];
                formData.append("Imagen", Imagen);

                await this.$http.post("clientesActivosUp", formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((res) => {
                    this.EjecutaConExito(res);
                    this.objCliente = res.data.data.cliente;
                })
                .catch((err) => {
                    this.EjecutaConError(err);
                });
            },
            EjecutaConExito(res) {
                this.oBtnSave.DisableBtn = false;
                this.oBtnSave.toast      = 1;
                this.Regresar();
            },
            EjecutaConError(err) {
                this.oBtnSave.DisableBtn = false;

                if (err.response.data.errors) {
                    this.errorvalidacion = err.response.data.errors;
                    this.oBtnSave.toast  = 2;
                } else {
                    this.$toast.error(err.response.data.message);
                }

            },
            Recuperar() {
                this.$http.get("clientesActivos/"+this.objCliente.IdCliente).then((res) => {
                        this.objCliente                 = res.data.data.Cliente;
                        this.RutaFile                   = res.data.RutaFile;
                        let Fecha1                      = this.objCliente.FechaNacimiento.replace(/-/g,'\/');
                        this.objCliente.FechaNacimiento = Fecha1;
                        this.ListaMunicipios(this.objCliente.IdEstado);
                    })
                    .finally(() => {
                        this.ConfigLoad.ShowLoader = false;
                    });
            },

            RecuperarPrestamos(){
                this.$http.get("clientesPrestamosActivos/"+this.objCliente.IdCliente).then((res) => {
                    this.ListaPrestamoCliente = res.data.data.cliente;
                })
                .finally(() => {
                    this.ConfigLoad.ShowLoader = false;
                });
            },

            RecuperarRutas() {
                this.$http.get("clientesRutasActivos/"+this.objCliente.IdCliente).then((res) => {
                    this.objRuta        = res.data.data.cliente;
                    this.NombreRuta     = this.objRuta[0].NombreRuta;
                    this.Sucursales     = this.objRuta[0].Nombre;
                    this.NombreCompleto = this.objRuta[0].NombreCompleto;
                })
            },
            Limpiar() {
                this.objCliente = {
                    IdCliente:          0,
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
                    IdEstado:           0,
                    IdMunicipio:        0,
                    Latitud:            0,
                    Longitud:           0,
                    Imagen:             "",
                };

                
            },
            Regresar() {
                this.$router.push({name:'clientesActivos',params:{}});
            },
            async ListaMunicipios(id) {
                if (typeof id != "undefined") {
                    this.objCliente.IdEstado = id;
                }
                await this.$http
                    .get("municipios", {
                        params: {
                            IdEstado: this.objCliente.IdEstado,
                        },
                    })
                    .then((res) => {
                        if (typeof id == "undefined") {
                            this.ListaMunicipiosArray   = []
                            this.objCliente.IdMunicipio = 0;
                        }
                        this.ListaMunicipiosArray = res.data.data;
                    });
            },
            openPrestamo() {
                this.bus.$emit('NewModal_'+this.ConfigList2.EmitSeccion,this.objCliente.IdCliente,this.ConfigList.Obj);
            },
            formato(fecha){
                let formato = moment(fecha).format('DD-MM-YYYY');
                
                if(fecha!=null){
                    return formato;
                }

            },
        
        },
        created() {
            this.bus.$off("Save_"+EmitEjecuta);
            this.bus.$off("EmitReturn");
            this.bus.$off("List_" + EmitEjecuta2);

            this.Limpiar();
            this.ListaEstadosArray = JSON.parse(sessionStorage.getItem("ListaEstadosArray"));
            this.oBtnSave.toast    = 0;
        },
        mounted() {
            this.oBtnSave.DisableBtn = false;

            if (this.Id > 0) {
                this.objCliente.IdCliente = this.Id;
                this.Recuperar();
                this.RecuperarPrestamos();
                this.RecuperarRutas();
            } else {
                this.ConfigLoad.ShowLoader = false;
            }

            this.bus.$on("List_" + EmitEjecuta2, () => {
                this.RecuperarPrestamos();
            });

            this.bus.$on("Save_"+EmitEjecuta, () => {
                this.Guardar();
            });

            this.bus.$on("EmitReturn", () => {
                this.Regresar();
            });
        },
    };

</script>