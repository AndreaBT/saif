<template>
	<CLoader :pConfigLoad="ConfigLoad">
		<template slot="BodyFormLoad">
			<form class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
				<div class="container-fluid">
					<div class="row form-group">
                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="Nombre">Nombre</label>
                            <input  type="text" v-model="objSucursal.Nombre" maxlength="250" class="form-control" placeholder="Ingresar Nombre de la Sucursal"/>
                            <CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*' + errorvalidacion.Nombre[0]"/>
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="Telefono">Teléfono</label>
                            <input type="text" v-model="objSucursal.Telefono" @input="$onlyNums($event,objSucursal,'Telefono')" maxlength="10" class="form-control" placeholder="Ingresar Teléfono"/>
                            <CValidation v-if="this.errorvalidacion.Telefono" :Mensaje="'*' +errorvalidacion.Telefono[0]"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <label for="Calle">Calle</label>
                            <input type="text" v-model="objSucursal.Calle" maxlength="250" class="form-control" placeholder="Ingresar Calle" />
                            <CValidation v-if="this.errorvalidacion.Calle" :Mensaje="'*' + errorvalidacion.Calle[0]"/>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="NoExterior">No. Exterior</label>
                            <input type="text" v-model="objSucursal.NoExt" maxlength="250" class="form-control" placeholder="Ingresar No. Exterior" />
                            <CValidation v-if="this.errorvalidacion.NoExt" :Mensaje="'*' + errorvalidacion.NoExt[0]"/>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <label for="NoInterior">No. Interior</label>
                            <input type="text" v-model="objSucursal.NoInt" maxlength="250" class="form-control" placeholder="Ingresar No. Interior" />
                            <CValidation v-if="this.errorvalidacion.NoInt" :Mensaje="'*' + errorvalidacion.NoInt[0]"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <label for="Colonia">Colonia</label>
                            <input type="text" v-model="objSucursal.Colonia" maxlength="250" class="form-control" placeholder="Ingresar Colonia" />
                            <CValidation v-if="this.errorvalidacion.Colonia" :Mensaje="'*' + errorvalidacion.Colonia[0]"/>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <label for="Cp">Código Postal</label>
                            <input type="text" v-model="objSucursal.Cp" @input="$onlyNums($event,objSucursal,'Cp')" maxlength="10" class="form-control" placeholder="Ingresar Cp" />
                            <CValidation v-if="this.errorvalidacion.Cp" :Mensaje="'*' + errorvalidacion.Cp[0]"/>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <label for="item7">Estado</label>
                            <select v-model="objSucursal.IdEstado" class="form-control form-select" @change="getMunicipios()">
                                <option :value="0">--Seleccionar--</option>
                                <option v-for="(item, index) in estados" :key="index" :value="item.IdEstado">
                                    {{ item.Nombre }}
                                </option>
                            </select>
                            <CValidation v-if="this.errorvalidacion.IdEstado" :Mensaje="'*' +errorvalidacion.IdEstado[0]"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <label for="item7">Minicipio</label>
                            <select v-model="objSucursal.IdMunicipio" class="form-control form-select">
                                <option :value="0">--Seleccionar--</option>
                                <option v-for="(item, index) in municipios" :key="index" :value="item.IdMunicipio">
                                    {{ item.Nombre }}
                                </option>
                            </select>
                            <CValidation v-if="this.errorvalidacion.IdMunicipio" :Mensaje="'*' +errorvalidacion.IdMunicipio[0]"/>
                        </div>
                        <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                            <label for="Referencias">Referencias</label>
                            <textarea type="text" v-model="objSucursal.Referencias" maxlength="250" class="form-control" placeholder="Ingresar Referencias"/>
                            <CValidation v-if="this.errorvalidacion.Referencias" :Mensaje="'*' +errorvalidacion.Referencias[0]"/>
                        </div>
                    </div>
                </div>
			</form>
		</template>
	</CLoader>
</template>

<script>

export default {
	name: "FormSucursal",
	props: ["poBtnSave"],
	components: {
	},
	data() {
		return {
			ConfigLoad: {
				ShowLoader: true,
				ClassLoad:  false,
			},
			objSucursal: {
				IdSucursal: 0,
				IdEmpresa: 0,
				 IdEstado: '0',
                IdMunicipio: '0',
				Nombre: "",
				Calle: "",
				NoInt: 0,
				NoExt: 0,
				Colonia: "",
				Cp: 0,
				Referencias: "",
				Telefono: "",
				Latitud: 0,
				Longitud: 0,
			},
			estados:[],
            municipios:[],
			ListaEstadosArray: [],
			ListaMunicipiosArray: [],
			errorvalidacion: [],
			Emit: this.poBtnSave.EmitSeccion,
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
		};
	},
	methods: {
		async Guardar() {
			this.errorvalidacion = [];
			this.poBtnSave.toast = 0;
			this.poBtnSave.DisableBtn = true;

			if (this.objSucursal.IdSucursal == 0) {
				this.$http
					.post("sucursales", this.objSucursal)
					.then((res) => {
						this.EjecutaConExito(res);
					})
					.catch((err) => {
						this.EjecutaConError(err);
					});
			} else {
				this.$http.put(
                    "sucursales/" + this.objSucursal.IdSucursal,
                    this.objSucursal
                )
                .then((res) => {
                    this.EjecutaConExito(res);
                })
                .catch((err) => {
                    this.EjecutaConError(err);
                });
			}
		},
		EjecutaConExito(res) {
			this.poBtnSave.DisableBtn = false;
			this.poBtnSave.toast = 1;
			this.bus.$emit("CloseModal_" + this.Emit);
			this.bus.$emit("List_" + this.Emit);
		},
		EjecutaConError(err) {
			this.poBtnSave.DisableBtn = false;

			if (err.response.data.errors) {
				this.errorvalidacion = err.response.data.errors;
				this.poBtnSave.toast = 2;
			} else {
				this.$toast.error(err.response.data.message);
			}
		},
		Recuperar() {
			this.$http
				.get("sucursales/" + this.objSucursal.IdSucursal)
				.then((res) => {
					this.objSucursal = res.data.data;
					this.getMunicipios();
				})
				.finally(() => {
					this.ConfigLoad.ShowLoader = false;
				});
		},
		Limpiar() {
			this.objSucursal = {
				IdSucursal: 0,
				IdEmpresa: 0,
				IdEstado: 0,
				IdMunicipio: 0,
				Nombre: "",
				Calle: "",
				NoInt: 0,
				NoExt: 0,
				Colonia: "",
				Cp: 0,
				Referencias: "",
				Telefono: "",
				Latitud: 0,
				Longitud: 0,
			};
			this.errorvalidacion = [];
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
            if (parseInt(this.objSucursal.IdEstado) > 0)
            {
                this.municipios = [];

                this.$http.get('municipios',{
                    params:{
                        IdEstado: this.objSucursal.IdEstado
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
		async recuperaEmpresa() {
            let usr = JSON.parse(sessionStorage.getItem('user'));

            await this.$http.get('empresas/'+usr.IdEmpresa).then(res => {
                this.Empresa = res.data.data;
                this.getMunicipios();

            }).catch(err =>{
                this.Limpiar();
            });
        },
	},
	created() {
		this.getEstados();
		this.recuperaEmpresa();
		this.poBtnSave.toast = 0;

		this.ListaEstadosArray = JSON.parse(
			sessionStorage.getItem("ListaEstadosArray")
		);

		this.bus.$off("Recovery_" + this.Emit);
		this.bus.$on("Recovery_" + this.Emit, (Id) => {
			this.ConfigLoad.ShowLoader = true;
			this.poBtnSave.DisableBtn = false;

			this.bus.$off("Save_" + this.Emit);
			this.bus.$on("Save_" + this.Emit, () => {
				let usr = JSON.parse(sessionStorage.getItem('user'));
				this.objSucursal.IdEmpresa =  usr.IdEmpresa;
				this.Guardar();

			});
			this.Limpiar();

			if (Id != "") {
				this.objSucursal.IdSucursal = Id;
				this.Recuperar();
			} else {
				this.ConfigLoad.ShowLoader = false;
			}
		});
	},
};
</script>
