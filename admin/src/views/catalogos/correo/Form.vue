<template>
    <div>
        <CList :p-config-list="ConfigList">
            <template slot="bodyForm">
                <CLoader :pConfigLoad="ConfigLoad">
                    <template slot="BodyFormLoad">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7 mt-3">
                                    <fieldset>
                                        <legend class="col-form-label">Datos De Configuración</legend>
                                        <div class="form-group form-row">
                                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>HOST</label>
                                                <input v-model="proveedor.Host" placeholder="Ingrese el host, ejemplo (smtp.gmail.com)" type="text" class="form-control">
                                                <label>
                                                    <CValidation v-if="this.errorvalidacion.Host" :Mensaje="'*'+errorvalidacion.Host[0]"></CValidation>
                                                </label>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-1 col-lg-1 colxl-1">
                                                <label>PUERTO</label>
                                                <input v-model="proveedor.Port" placeholder="Ingrese el puerto, ejemplo (678)" type="text" class="form-control">
                                                <label>
                                                    <CValidation v-if="this.errorvalidacion.Port" :Mensaje="'*'+errorvalidacion.Port[0]"></CValidation>
                                                </label>
                                            </div>                                            
                                            <div class="col-12 col-sm-12 col-md-2 col-lg-2 colxl-2">
                                                <label>ENCRIPTACIÓN</label>
                                                <select v-model="proveedor.Encryption" class="form-control form-select">
                                                    <option value="">--Seleccionar--</option>
                                                    <option value="ssl">SSL</option>
                                                    <option value="tls">TLS</option>
                                                </select>
                                                <label>
                                                    <CValidation v-if="this.errorvalidacion.Encryption" :Mensaje="'*'+errorvalidacion.Encryption[0]"></CValidation>
                                                </label>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 colxl-6">
                                                <label>NOMBRE DEL SISTEMA <small>(Nombre que aparecerá en el correo)</small></label>
                                                <input v-model="proveedor.NameApp" placeholder="Ingrese el nombre del sistema, ejemplo (Financiera F)" type="text" class="form-control">
                                                <label>
                                                    <CValidation v-if="this.errorvalidacion.NameApp" :Mensaje="'*'+errorvalidacion.NameApp[0]"></CValidation>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group form-row">
                                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 colxl-4">
                                                <label>USUARIO</label>
                                                <input v-model="proveedor.Username" placeholder="Ingrese el usuario, ejemplo (financieraF@gmail.com)" type="text" class="form-control">
                                                <label>
                                                    <CValidation v-if="this.errorvalidacion.Username" :Mensaje="'*'+errorvalidacion.Username[0]"></CValidation>
                                                </label>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 colxl-4">
                                                <label>CONTRASEÑA</label>
                                                <input v-model="proveedor.Password" :type="type" placeholder="Ingrese la contraseña, ejemplo (123financieraFPass)" class="form-control">
                                                <button v-if="proveedor.Password !== ''" @click="ToggleShow" class="button btn-password-formulario-recovery" type="button" id="button-addon2">
                                                    <i class="far icono-password" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                                </button>
                                                <label>
                                                    <CValidation v-if="this.errorvalidacion.Password" :Mensaje="'*'+errorvalidacion.Password[0]"></CValidation>
                                                </label>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 colxl-4">
                                                <label>REMITENTE</label>
                                                <input v-model="proveedor.Remitente" placeholder="Ingrese el usuario, ejemplo (financieraF@gmail.com)" type="text" class="form-control">
                                                <label>
                                                    <CValidation v-if="this.errorvalidacion.Remitente" :Mensaje="'*'+errorvalidacion.Remitente[0]"></CValidation>
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <CBtnSave :poBtnSave="oBtnSave" />
                                </div>
                            </div>
                        </div>
                    </template>
                </CLoader>
            </template>
        </CList>
    </div>

</template>

<script>

    const EmitEjecuta = 'seccionCorreo';

    export default {
        name:       "FormCorreo",
        props:      [''],
        components: { },
        data() {
            return {                
                type:            'password',
                showPassword:    false,
                password:        null,
                emmit:           EmitEjecuta,
                errorvalidacion: [],
                proveedor: {
                    IdMailServeConfig: 0,
                    TipoServicio:      '1',
                    KeyService:        'PROVIDER',
                    Driver:            'smtp',
                    Host:              '',
                    Port:              '',
                    Username:          '',
                    Password:          '',
                    Encryption:        '',
                    Remitente:         '',
                    NameApp:           '',
                    Alias:             'Recuperar Contraseña'
                },
                ConfigList: {
                    ShowTitleFirst:    false,
                    Title:             "Correo Electrónico",
                    ShowLoader:        true,
                    IsModal:           false,
                    BtnNewShow:        false,
                    BtnReturnShow:     false,
                    TypeBody:          "Form",
                    ShowFiltros:       false,
                    ShowFiltrosInline: false,
                    ShowTitleInline:   false,
                    ShowPaginador:     false,
                    EmitSeccion:       EmitEjecuta
                },
                ConfigLoad: {
                    ShowLoader: false,
                    ClassLoad:  false,
                },
                oBtnSave: {
                    toast:         0,
                    IsModal:       false,
                    ShowBtnSave:   true,
                    ShowBtnCancel: false,
                    DisableBtn:    false,
                    EmitSeccion:   EmitEjecuta,
                },
            }
        },
        methods: {            
            async Guardar() {
                this.errorvalidacion     = [];
				this.oBtnSave.DisableBtn = true;

                await this.$http.put(`mailserviceconf/${this.proveedor.IdMailServeConfig}`, this.proveedor)
                .then( (res) => {
                    this.proveedor = res.data.data;
                    this.EjecutaConExito(res);
                })
                .catch( err => {
                    this.EjecutaConError(err);
                });
            },
            async InfoCorreo() {
                
                let resquest = {
                    KeyService: "PROVIDER"
                }

                this.$http.post("mailserviceconf", resquest).then( (res) => {
                    this.proveedor = res.data.data;
                })
                .catch( err => {
                    
                    if(err.response.data.errors){
                        this.errorvalidacion=err.response.data.errors;
                    } else {
                        this.$toast.error(err.response.data.message);
                    }

                })
				.finally(() => {
					this.ConfigLoad.ShowLoader = false;
				});
            },
            Limpiar() {
                this.proveedor.IdMailServeConfig = 0;
                this.proveedor.TipoServicio      = '1';
                this.proveedor.KeyService        = 'PROVIDER',
                this.proveedor.Host              = '';
                this.proveedor.Port              = '';
                this.proveedor.Username          = '';
                this.proveedor.Password          = '';
                this.proveedor.Encryption        = 0;
                this.proveedor.Remitente         = '';
                this.proveedor.NameApp           = '';
                this.proveedor.Alias             = '';
                this.errorvalidacion             = [];
            },      			
            EjecutaConExito(res) {
                this.oBtnSave.DisableBtn = false;  
                this.bus.$emit("CloseModal_" + this.Emit);
                this.bus.$emit("List_" + this.Emit);
                this.$toast.success(res.data.message,'Tus datos de configuración han sido actualizados');
            },	
            EjecutaConError(err) {
                this.oBtnSave.DisableBtn = false;	                
                this.$toast.error(err.response.data.message,'Tus datos de configuración no se actualizaron');				
                
                if  (err.response.data.errors) {
                    this.errorvalidacion = err.response.data.errors;
                }

            },          
            ToggleShow() {

                if (this.showPassword = !this.showPassword) {                    
                    this.type = 'text'
                } else {
                    this.type = 'password'
                }
                
            },
        },
        created() {
            this.bus.$off("Save_"+this.emmit);
            this.bus.$off("List_" + EmitEjecuta);
        },
        mounted() {
            this.InfoCorreo();
			this.ConfigLoad.ShowLoader = true;  
			this.oBtnSave.DisableBtn   = false;   

            this.bus.$on("Save_"+this.emmit, () => {
                this.Guardar();
            });

        },  
    }

</script>

