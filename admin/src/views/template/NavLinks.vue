<template>
	<div>
	
		<nav class="navbar navbar-expand-xl">
			<div class="container-fluid h-100">
				<a @click="Ir_a_inicio()" class="navbar-brand title-nombre" style="cursor: pointer;">Financiera F</a>
				
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample06" aria-controls="navbarsExample06" aria-expanded="false" aria-label="Toggle navigation" >
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse justify-content-end" id="navbarsExample06">
					<CNavLinks :pArrayMenus="ArrayMenu">
						<template slot="MenuDerecho">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="dropdown06" data-toggle="dropdown" aria-expanded="false">
									<img v-if="this.ObjUsuario.Imagen == ''" src="../../assets/style/image/001-Hombre.png" class="rounded-circle ma mr-2" alt="Imagen Demo" width="28" height="28"/>
									<img v-else :src="this.RutaFile"  class="rounded-circle ma mr-2" alt="Imagen Demo" width="28" height="28"/>
									<span class="mayuscula cursor">
										{{ this.ObjUsuario.username }} <i class="fas fa-angle-down"></i>
									</span>
								</a>
								<div class="dropdown-menu dropdown-menu-user dropdown-menu-right animate slideIn" aria-labelledby="dropdown06">
									<div class="dropdown-menu-header">
										<div class="widget-content p-0">
											<div class="widget-content-wrapper">
												<div class="widget-content-left mr-3">
													<img v-if="this.ObjUsuario.Imagen == ''" src="../../assets/style/image/001-Hombre.png" class="rounded-circle" alt="Imagen Demo" width="42">
													<img v-else :src="this.RutaFile" class="rounded-circle" alt="Imagen Demo" width="42">
												</div>
												<div class="widget-content-left">
													<p class="widget-nombre mayuscula">{{ this.ObjUsuario.Nombre }}<br>
														<span class="widget-puesto mayuscula" v-if="ObjUsuario.IdPerfil != 0">		
															<b>{{ this.ObjUsuario.perfil.Nombre }}</b>	
														</span>
														<span v-else class="widget-puesto mayuscula">											
															<b>Root</b> 
														</span>
													</p>
												</div>
											</div>
										</div>
									</div>
									<a @click="MiPerfil(0)" class="dropdown-item">
										Mi Perfil
									</a>
									<a @click="CambiarPassword(0)" class="dropdown-item">
										Cambiar Contraseña
									</a>
									<div class="dropdown-divider"></div>
									<div class="grid-menu">
										<div class="no-gutters row">
											<div class="col-sm-6 grid-menu-r">
												<button @click="Ir_a_inicio()" type="button" class="btn btn-outline-primary">
													<i class="fal fa-chart-pie"></i>
													Dashboard
												</button>
											</div>
											<div class="col-sm-6">
												<button @click="Ir_a_empleados()" type="button" class="btn btn-outline-primary">
													<i class="fal fa-address-book"></i>
													Empleados
												</button>
											</div>
										</div>
									</div>
									<div class="dropdown-divider"></div>
									<div class="row grid-menu-mess">
										<div class="col-sm-12 text-center">
											<button @click="CerrarSession()" type="button" class="btn btn-primary"> <i class="fas fa-door-open fa-fw-m"></i> Salir</button>
										</div>
									</div>
								</div>
							</li>
						</template>
					</CNavLinks>
				</div>
			</div>
		</nav>
		
		<CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave">
			<template slot="Form">
				<FormMiPerfil :poBtnSave="oBtnSave"></FormMiPerfil>
			</template>
		</CModal>

		<CModal :pConfigModal="ConfigModal2" :poBtnSave="oBtnSave2">
			<template slot="Form">
				<FormCambiarPassword :poBtnSave="oBtnSave2"></FormCambiarPassword>
			</template>
		</CModal>

	</div>
</template>

<script>

	import MenusConfiguracion  from '@/config/ConfigMenu.js';
	import Configs 			   from '@/helpers/VarConfig.js';
	import Template 		   from '@/views/template/Template.vue';
	import FormMiPerfil		   from '@/views/security/MiPerfil.vue';
	import FormCambiarPassword from '@/views/security/CambiarPassword.vue';
	const EmitEjecuta 		   =    'FormMiPerfil';
	const EmitEjecuta2		   =    'FormCambiarPassword';

	export default {
		name:  'NavLinks',
		props: [''],
		components: {Template,FormMiPerfil,FormCambiarPassword },
		data() {
			return {				
				RutaFile: 	  "",
				ArrayMenu: 	  MenusConfiguracion.ConfigMenus,
				ArrayMenuDer: MenusConfiguracion.ConfigMenuDer,
				ObjUsuario:   {},
				IsEmpleado:   false,
				IsRoot: 	  false,
				ConfigModal:{
					ModalTitle:  "Mi Perfil",
					ModalSize:   'lg',
					ModalNameId: 'ModalForm2',
					EmitSeccion:  EmitEjecuta,
				},
				oBtnSave: {
					toast:       0,
					IsModal:     true,
					DisableBtn:  false,
					EmitSeccion: EmitEjecuta,
				},
				ConfigModal2:{				
					ModalTitle:  "Cambiar Contraseña",
					ModalSize:   'lg',
					ModalNameId: 'ModalForm',
					EmitSeccion:  EmitEjecuta2,
				},
				oBtnSave2: {
					toast:       0,
					IsModal:     true,
					DisableBtn:  false,
					EmitSeccion: EmitEjecuta2,
				},
			}
		},
		methods: {
			Session() {
				this.ObjUsuario = JSON.parse(sessionStorage.getItem("user"));
				this.RutaFile 	= sessionStorage.getItem('RutaFile')+this.ObjUsuario.Imagen;

				if (this.ObjUsuario != null) {

					if (this.ObjUsuario.IdPerfil > 0) {
						this.IsEmpleado = true;
					} else if (this.ObjUsuario.IdPerfil == 0) {
						this.IsRoot = true;
					}

				}

			},
			CerrarSession(){
				
				this.$swal(Configs.configCerrarSession).then((result) => {
					
					if(result.value) {
						this.$store.dispatch("logout");
						this.$router.push({ name: "login" });
					} 

				});

			},
			MiPerfil() {
				this.bus.$emit('NewModal_'+EmitEjecuta,this.ObjUsuario.IdUsuario);
			},	
			CambiarPassword() {
				this.bus.$emit('NewModal_'+EmitEjecuta2,this.ObjUsuario.IdUsuario);
			},
			Ir_a_inicio() {
				this.$router.push({name:'inicio',params:{}});
			},		
			Ir_a_empleados() {
				this.$router.push({name:'empleados',params:{}});
			},    
			Substraer(Nombre){
				let name = '';

				if (Nombre != null && Nombre != ''){
					name = Nombre.substr(0,17);
				}

				return name;
			},
		},
		created() {
			this.Session();
		},
	}
	
</script>