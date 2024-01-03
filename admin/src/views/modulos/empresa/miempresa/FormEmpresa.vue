<template>
    <div>
        <CList :p-config-list="ConfigList">
            <template slot="bodyForm">
                <CLoader :p-config-load="ConfigLoad">
                    <template slot="BodyFormLoad">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
                                    <fieldset>
                                        <legend class="col-form-label">Datos Generales</legend>
                                        <div class="form-row mt-4">
                                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input id="file"  @change="$uploadImagePreview($event,ValidElement,Array('Img','imagePreview'))"  ref="file"  type="file" name="myfile"  accept=".png, .jpg, .jpeg">
                                                        <label for="file"></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview" :style="'background-image: url('+RutaFile+Empresa.Imagen+');'" :src="RutaFile+Empresa.Imagen">
                                                        </div>    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">

                                                <div class="form-group form-row">
                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="NombreComercial">Nombre Comercial</label>
                                                        <input  class="form-control" id="NombreComercial" v-model="Empresa.NombreComercial" type="text" placeholder="Ingrese Nombre Comercial"/>
                                                        <CValidation v-if="this.errorvalidacion.NombreComercial" :Mensaje="'*' + errorvalidacion.NombreComercial[0]" />
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-43">
                                                        <label for="RazonSocial">Razón Social</label>
                                                        <input class="form-control" id="RazonSocial" v-model="Empresa.RazonSocial" type="text" placeholder="Ingrese Razón Social"/>
                                                        <CValidation v-if="this.errorvalidacion.RazonSocial" :Mensaje="'*' + errorvalidacion.RazonSocial[0]" />
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Rfc">RFC</label>
                                                        <input class="form-control" id="Rfc" v-model="Empresa.Rfc" type="text" placeholder="Ingrese RFC"/>
                                                        <CValidation v-if="this.errorvalidacion.Rfc" :Mensaje="'*' + errorvalidacion.Rfc[0]"/>
                                                    </div>
                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Calle">Calle</label>
                                                        <input class="form-control" id="Calle" v-model="Empresa.Calle" type="text" placeholder="Ingrese Calle"/>
                                                        <CValidation v-if="this.errorvalidacion.Calle" :Mensaje="'*' + errorvalidacion.Calle[0]"/>
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="NoExt">Número Exterior</label>
                                                        <input class="form-control" id="NoExt" v-model="Empresa.NoExt" type="text" placeholder="Ingrese Número Exterior"/>
                                                        <CValidation v-if="this.errorvalidacion.NoExt" :Mensaje="'*' + errorvalidacion.NoExt[0]"/>
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="NoInt">Número Interior</label>
                                                        <input class="form-control" id="NoInt" v-model="Empresa.NoInt" type="text" placeholder="Ingrese Número Interior"/>
                                                        <CValidation v-if="this.errorvalidacion.NoInt" :Mensaje="'*' + errorvalidacion.NoInt[0]"/>
                                                    </div>
                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Cp">Código Postal</label>
                                                        <input class="form-control" id="Cp" v-model="Empresa.Cp" type="text" @input="$onlyNums($event,Empresa,'Cp')" placeholder="Ingrese Cp" maxlength="10"/>
                                                        <CValidation v-if="this.errorvalidacion.Cp" :Mensaje="'*' + errorvalidacion.Cp[0]"/>
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Colonia">Colonia</label>
                                                        <input class="form-control" id="Colonia" v-model="Empresa.Colonia" type="text" placeholder="Ingrese Colonia"/>
                                                        <CValidation v-if="this.errorvalidacion.Colonia" :Mensaje="'*' + errorvalidacion.Colonia[0]"/>
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Referencias">Referencias</label>
                                                        <textarea class="form-control" id="Referencias" v-model="Empresa.Referencias" placeholder="Ingrese Referencias" rows="1"/>
                                                        <CValidation v-if="this.errorvalidacion.Referencias" :Mensaje="'*' + errorvalidacion.Referencias[0]"/>
                                                    </div>
                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Telefono">Telefono</label>
                                                        <input class="form-control" id="Telefono" v-model="Empresa.Telefono" type="text" maxlength="10" @input="$onlyNums($event,Empresa,'Telefono')" placeholder="Ingrese Telefono"/>
                                                        <CValidation v-if="this.errorvalidacion.Telefono" :Mensaje="'*' + errorvalidacion.Telefono[0]"/>
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Estado">Estado</label>
                                                        <select id="Estado" class="form-control form-select" v-model="Empresa.IdEstado" @change="getMunicipios()">
                                                            <option :value="0">--Seleccionar--</option>
                                                            <option v-for="(item, index) in estados" :key="index" :value="item.IdEstado">
                                                                {{ item.Nombre }}
                                                            </option>
                                                        </select>
                                                        <CValidation v-if="this.errorvalidacion.IdEstado" :Mensaje="'*' + errorvalidacion.IdEstado[0]"/>
                                                    </div>

                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="Municipio">Municipio</label>
                                                        <select v-model="Empresa.IdMunicipio" id="Municipio" class="form-control form-select">
                                                            <option :value="0">--Seleccionar--</option>
                                                            <option v-for="(item, index) in municipios" :key="index" :value="item.IdMunicipio">
                                                                {{ item.Nombre }}
                                                            </option>
                                                        </select>
                                                        <CValidation v-if="this.errorvalidacion.IdMunicipio" :Mensaje="'*' + errorvalidacion.IdMunicipio[0]"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <CBtnSave :poBtnSave="oBtnSave" />
                                </div>
                            </div>

                        </div>
                    </template>
                </CLoader>
            </template>
        </CList>

        <CList @FiltrarC="Lista" :pConfigList="ConfigList2" :pFiltro="Filtro" :segurity="segurity" >
			<template slot="header">
				<th class="td-sm"></th>
				<th>#</th>
				<th>Nombre</th>
				<th>Teléfono</th>
                <th>Calle</th>
				<th class="text-center">Acciones</th>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaArrayRows" :key="index">
					<td class="td-sm"></td>
					<td>{{ index + 1 }}</td>
					<td>{{ lista.Nombre }}</td>
					<td>{{ lista.Telefono }}</td>
                    <td>{{ lista.Calle }}</td>
					<td class="text-center">
						<CBtnAccion :pShowBtnEdit="true" :pShowBtnDelete="true" :pIsModal="true" :pId="lista.IdSucursal" :pEmitSeccion="ConfigList2.EmitSeccion" >
							<template slot="btnaccion"> </template>
						</CBtnAccion>
					</td>
				</tr>
				<CSinRegistro :pContIF="ListaArrayRows.length" :pColspan="6" ></CSinRegistro>
			</template>
		</CList>

		<CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave2">
			<template slot="Form">
				<FormSucursal :poBtnSave="oBtnSave2"></FormSucursal>
			</template>
		</CModal>
    </div>
</template>

<script>

    import FormSucursal from '@/views/modulos/empresa/miempresa/FormSucursal.vue';
    import Configs      from '@/helpers/VarConfig.js';
    const  emmitEmpresa =    'seccionEmpresa';
    const  EmitEjecuta  =    'seccionSucursal';

    export default {
        name:  'FormMiEmpresa',
        props: [],
        components:{
            FormSucursal
        },
        data() {
            return {
                ConfigList: {
                    ShowTitleFirst:    false,
                    Title:             "Mi Empresa",
                    ShowLoader:        true,
                    IsModal:           false,
                    BtnNewShow:        false,
                    BtnReturnShow:     false,
                    TypeBody:          "Form",
                    ShowFiltros:       false,
                    ShowFiltrosInline: true,
                    ShowTitleInline:   false,
                    ShowPaginador:     false,
                    EmitSeccion:       emmitEmpresa
                },
                ConfigList2: {
                    Title:         "Listado Sucursales",
                    IsModal:       true,
                    ShowLoader:    true,
                    BtnReturnShow: false,
                    EmitSeccion:   EmitEjecuta,
                },
                oBtnSave: {
                    toast:         0,
                    IsModal:       false,
                    ShowBtnSave:   true,
                    ShowBtnCancel: false,
                    DisableBtn:    false,
                    EmitSeccion:   emmitEmpresa,
                },
                oBtnSave2: {
                    toast: 0,
                    IsModal: true,
                    DisableBtn: false,
                    EmitSeccion: EmitEjecuta,
                },
                ConfigLoad: {
                    ShowLoader: false,
                    ClassLoad:  false,
                },
                Filtro: {
                    Nombre: "",
                    Pagina: 1,
                    Entrada: 10,
                    TotalItem: 0,
                    Placeholder: "Buscar..",
                },
                ConfigModal: {
                    ModalTitle: "Formulario Sucursal",
                    ModalNameId: "ModalForm",
                    EmitSeccion: EmitEjecuta,
                    ModalSize: "lg",
                },
                errorvalidacion: [],
                Empresa:{
                    IdEmpresa: 0,
                    NombreComercial: '',
                    RazonSocial: '',
                    Rfc: '',
                    Calle: '',
                    NoInt: '',
                    NoExt: '',
                    Colonia: '',
                    Cp: '',
                    Referencias: '',
                    IdEstado: '0',
                    IdMunicipio: '0',
                    Telefono: ''
                },
                estados:[],
                municipios:[],
                emmit: emmitEmpresa,
                segurity: {},
                obj: {},
                ListaArrayRows: [],
                ListaHeader: [],
                ValidElement: Configs.validImage2m,
                RutaFile: '',
            }
        },
        methods:{
            /*
            * Listado de estados del combo
            */
            async recuperaEmpresa() {
                let usr = JSON.parse(sessionStorage.getItem('user'));

                await this.$http.get('empresas/'+usr.IdEmpresa).then(res => {
                    this.Empresa = res.data.data;
                    this.RutaFile          = res.data.rutaFile;
                    this.getMunicipios();

                }).catch(err =>{
                    this.limpiar();
                })
				.finally(() => {
					this.ConfigLoad.ShowLoader = false;
				});
            },
            /*
            * Desvuelve al estado inicial el objeto Empresa
            */
            limpiar(){
                this.Empresa = {
                    IdEmpresa: 0,
                    NombreComercial: '',
                    RazonSocial: '',
                    Rfc: '',
                    Calle: '',
                    NoInt: '',
                    NoExt: '',
                    Colonia: '',
                    Cp: '',
                    Referencias: '',
                    IdEstado: '0',
                    IdMunicipio: '0',
                    Telefono: ''
                };
            },
            /*
            * Listado de estados del combo
            */
        async getEstados() {
                await this.$http.get('estados').then(res => {
                    this.estados = res.data.data;
                }).catch(err =>{
                    this.estados = [];
                }).finally( () => {
                });
        },

            /*
            * Listado de municipios del combo
            */
            getMunicipios() {
                if (parseInt(this.Empresa.IdEstado) > 0)
                {
                    this.municipios = [];

                    this.$http.get('municipios',{
                        params:{
                            IdEstado: this.Empresa.IdEstado
                        }
                    }).then(res => {
                        this.municipios = res.data.data;

                    }).catch(err =>{
                        this.municipios = [];
                    });
                }else {
                    this.municipios = [];
                }
            },
            /*
            * Save de empresa
            */
            UpdateEmpresa(){
                let formData = new FormData();
                formData.set('IdEmpresa',this.Empresa.IdEmpresa);
                formData.set('NombreComercial',this.Empresa.NombreComercial);
                formData.set('RazonSocial',this.Empresa.RazonSocial);
                formData.set('Rfc',this.Empresa.Rfc);
                formData.set('Calle',this.Empresa.Calle);
                formData.set('NoInt',this.Empresa.NoInt);
                formData.set('NoExt',this.Empresa.NoExt);
                formData.set('Colonia',this.Empresa.Colonia);
                formData.set('Cp',this.Empresa.Cp);
                formData.set('Referencias',this.Empresa.Referencias);
                formData.set('IdEstado',this.Empresa.IdEstado);
                formData.set('IdMunicipio',this.Empresa.IdMunicipio);
                formData.set('Telefono',this.Empresa.Telefono);
                formData.set('Imagen',this.Empresa.Imagen);

                let picture = this.$refs.file.files[0];
                formData.append('Imagen',picture);

                this.errorvalidacion = [];
                this.oBtnSave.toast = 0;
                this.oBtnSave.DisableBtn = true;

                this.$http
                .post(
                    "empresasup",
                    formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
                )
                .then((res) => {
                    this.EjecutaConExito(res);
                })
                .catch((err) => {
                    this.EjecutaConError(err);
                });
            },
            EjecutaConExito(res) {
                this.oBtnSave.DisableBtn = false;
                this.oBtnSave.toast = 1;
                this.bus.$emit("CloseModal_" + this.Emit);
                this.bus.$emit("List_" + this.Emit);
            },
            EjecutaConError(err) {
                this.oBtnSave.DisableBtn = false;

                if (err.response.data.errors) {
                    this.errorvalidacion = err.response.data.errors;
                    this.oBtnSave.toast = 2;
                } else {
                    this.$toast.error(err.response.data.message);
                }
            },
            /*
            * Listado de Sucursales
            */
            async Lista() {
                this.ConfigList2.ShowLoader = true;

                await this.$http
                .get("sucursales", {
                    params: {
                        txtBusqueda: this.Filtro.Nombre,
                        Entrada: this.Filtro.Entrada,
                        Pag: this.Filtro.Pagina,
                    },
                })
                .then((res) => {
                    this.ListaArrayRows = res.data.data.data;
                    /* this.ListaHeader        = res.data.headers; */
                })
                .finally(() => {
                    this.ConfigList2.ShowLoader = false;
                });
            },
            /*
            * Eliminado de Sucursales
            */
            Eliminar(Id) {
                this.$swal(Configs.configEliminar).then((result) => {
                    if (result.value) {
                        this.$http
                        .delete("sucursales/" + Id)
                        .then((res) => {
                            this.$swal(Configs.configEliminarConfirm);
                            this.Lista();
                        })
                        .catch((err) => {
                            this.$toast.error(err.response.data.message);
                        });
                    }
                });
            },
        },
        created() {
            this.bus.$off("Save_"+this.emmit);
            this.bus.$off("List_" + EmitEjecuta);
            this.bus.$off("Delete_" + EmitEjecuta);
        },
        mounted() {
			this.ConfigLoad.ShowLoader = true;  
            this.oBtnSave.DisableBtn   = false; 
            this.Lista();
            this.getEstados();
            this.recuperaEmpresa();


            this.bus.$on("Save_"+this.emmit, () => {
                // this.GuardarEvidencia();
                this.UpdateEmpresa();
            });

            this.bus.$on("Delete_" + EmitEjecuta, (Id) => {
                this.Eliminar(Id);
            });

            this.bus.$on("List_" + EmitEjecuta, () => {
                this.Lista();
            });
        },
        beforeDestroy() {
        }
    }

</script>
