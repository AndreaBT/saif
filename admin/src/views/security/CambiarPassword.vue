<template>
    <div>
		<CLoader :pConfigLoad="ConfigLoad">
			<template slot="BodyFormLoad">
				<form class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="form-group form-row">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<label>Usuario: </label>
								<input v-model="usuario.NombreCompleto" type="text" disabled class="form-control form-control-xl" placeholder="Usuario">
								<label>
									<CValidation v-if="this.errorvalidacion.NombreCompleto" :Mensaje="'*'+errorvalidacion.NombreCompleto[0]"></CValidation>
								</label>
							</div>
						</div>
						<div class="form-group form-row">
							<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
								<label>Contraseña Actual:</label>								
								<input v-model="tmpData.Old" :type="type1" class="form-control form-control-xl" placeholder="Ingresa la Contraseña Actual">
								<button v-if="tmpData.Old !== ''" @click="ToggleShow" class="button btn-password-formulario" type="button" id="button-addon2">
									<i class="far icono-password" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
								</button>
								<label>
									<CValidation v-if="this.errorvalidacion.Old" :Mensaje="'*'+errorvalidacion.Old[0]"></CValidation>
								</label>
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
								<label>Contraseña Nueva:</label>
								<input v-model="tmpData.New" :type="type2" class="form-control form-control-xl" placeholder="Ingresa la Contraseña Nueva">								
								<button v-if="tmpData.New !== ''" @click="ToggleShowNew" class="button btn-password-formulario" type="button" id="button-addon2">
									<i class="far icono-password" :class="{ 'fa-eye-slash': showPasswordNew, 'fa-eye': !showPasswordNew }"></i>
								</button>								
								<label>
									<CValidation v-if="this.errorvalidacion.New" :Mensaje="'*'+errorvalidacion.New[0]"></CValidation>
								</label>
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
								<label>Confirmar Contraseña Nueva:</label>								
								<input v-model="tmpData.ConfirmNew" :type="type3" class="form-control form-control-xl" placeholder="Confirma Contraseña Nueva">
								<button v-if="tmpData.ConfirmNew !== ''" @click="ToggleShowConfirm" class="button btn-password-formulario" type="button" id="button-addon2">
									<i class="far icono-password" :class="{ 'fa-eye-slash': showPasswordConfirm, 'fa-eye': !showPasswordConfirm }"></i>
								</button>
								<label>
									<CValidation v-if="this.errorvalidacion.ConfirmNew" :Mensaje="'*'+errorvalidacion.ConfirmNew[0]"></CValidation>
								</label>
							</div>
						</div>
					</div>
				</form>
			</template>
		</CLoader>
    </div>
</template>

<script>


    export default {
        name: 		'FormularioCambiarPassword',
        props:		['poBtnSave'],
        components: { },
        data() {
            return {
				type1:               'password', 
				type2:               'password', 
				type3:               'password', 
				showPassword:        false,
				showPasswordNew:     false,
				showPasswordConfirm: false,
				password:            null,
				Emit: 			 this.poBtnSave.EmitSeccion,
				errorvalidacion: [],
                usuario: {
					NombreCompleto: '------'
				},
				ConfigLoad:{
					ShowLoader: true,
					ClassLoad:  false,
				},
				tmpData: {
					Old: 		'',
					New: 		'',
					ConfirmNew: '',
				},
            }
        },
		methods: {
			Guardar() {
				this.errorvalidacion  	  = [];
				this.poBtnSave.DisableBtn = true;

				return new Promise((resolve, reject) => {
						
					this.$http.post('usrcredentials', this.tmpData).then((res) => {

						if (res.data.status) {
							this.Limpiar(); 
							this.EjecutaConExito(res);
							this.$toast.warning('Es Necesario que inicies sesion de nuevo');
							this.$toast.success(res.data.message,'Tu contraseña ha sido actualizada,');
							resolve(res.data);
						}

					})
					.catch((err) => {
						reject(err.response.data);
						this.EjecutaConError(err);
					});

				});

			},
			InfoUsuario() {
				this.$http.get('usrsrchme').then((res) => {
					this.usuario = res.data.data;
				})
				.finally(() => {
					this.ConfigLoad.ShowLoader = false;
				});
			},
			Limpiar() {
				this.tmpData.Old 		= '';
				this.tmpData.New 		= '';
				this.tmpData.ConfirmNew = '';
				this.errorvalidacion 	= [];
			},			
			EjecutaConExito(res) {
				this.poBtnSave.DisableBtn = false;  
				this.bus.$emit('CloseModal_'+this.Emit);
				this.bus.$emit('List_'+this.Emit);
				this.$store.dispatch("logout");
				this.$router.push({ name: "login" });
			},	
			EjecutaConError(err) {
				this.poBtnSave.DisableBtn = false;
				
				if (err.response.data.typemsj === 2) {
					this.errorvalidacion = err.response.data.errors;
				}

				if (err.response.data.typemsj === 3) {
					
					let a = {
						Old: ['Contraseña incorrecta']
					}

					this.errorvalidacion = a;
					this.$toast.warning(err.response.data.message,'Acción denegada');

				}

			},
			ToggleShow() {

				if (this.showPassword = !this.showPassword) {                    
					this.type1 = 'text'
				} else {
					this.type1 = 'password'
				}

			},
			ToggleShowNew() {            

				if (this.showPasswordNew = !this.showPasswordNew) {                    
					this.type2 = 'text'
				} else {
					this.type2 = 'password'
				}
			},
			ToggleShowConfirm() {      

				if (this.showPasswordConfirm = !this.showPasswordConfirm) {                    
					this.type3 = 'text'
				} else {
					this.type3 = 'password'
				}

			}
		},
		created() {
			this.poBtnSave.toast = 0;
			this.bus.$off('Recovery_'+this.Emit);
			this.bus.$off('Save_'+this.Emit);
		},
		mounted() {
			this.InfoUsuario();			
			this.ConfigLoad.ShowLoader = true;    
			this.poBtnSave.DisableBtn  = false;

			this.bus.$on('Save_'+this.Emit,()=> {
				this.Guardar();
			});

		},
    }
    
</script>