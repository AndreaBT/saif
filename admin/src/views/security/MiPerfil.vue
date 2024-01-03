<template>
    <div>
		<CLoader :pConfigLoad="ConfigLoad">
			<template slot="BodyFormLoad">
                <form class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group form-row justify-content-center">                    
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="avatar-upload">
									<div class="avatar-edit">
										<input type="file" @change="$uploadImagePreview($event,ValidElement,Array('Img','imagePreviewPerfil'))" id="filePerfil" name="myfile" ref="file" accept=".png, .jpg, .jpeg">
										<label for="filePerfil"></label>
									</div>
									<div class="avatar-preview">
										<div id="imagePreviewPerfil" :style="'background-image: url('+RutaFile+usuario.Imagen+');'" :src="RutaFile+usuario.Imagen">
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label>Nombres: </label>
                                <input v-model="usuario.Nombre" type="text" class="form-control form-control-xl" placeholder="Ingresa un Nombre">
                                <label>
									<CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*'+errorvalidacion.Nombre[0]"></CValidation>
								</label>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Apellido Paterno: </label>
                                <input v-model="usuario.ApellidoPat" type="text" class="form-control form-control-xl" placeholder="Ingresa un Apellido">
                                <label>
									<CValidation v-if="this.errorvalidacion.ApellidoPat" :Mensaje="'*'+errorvalidacion.ApellidoPat[0]"></CValidation>
								</label>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Apellido Materno: </label>
                                <input v-model="usuario.ApellidoMat" type="text" class="form-control form-control-xl" placeholder="Ingresa un Apellido">
                                <label>
									<CValidation v-if="this.errorvalidacion.ApellidoMat" :Mensaje="'*'+errorvalidacion.ApellidoMat[0]"></CValidation>
								</label>
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Correo Electronico:</label>
                                <input v-model="usuario.Correo" type="text" class="form-control form-control-xl" placeholder="Ingresa un Correo Electróico">                                
                                <label>
									<CValidation v-if="this.errorvalidacion.Correo" :Mensaje="'*'+errorvalidacion.Correo[0]"></CValidation>
								</label>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Telefóno:</label>
                                <input v-model="usuario.Telefono" type="text" class="form-control form-control-xl" placeholder="Ingresa un Numero de Telefono">                                
                                <label>
									<CValidation v-if="this.errorvalidacion.Telefono" :Mensaje="'*'+errorvalidacion.Telefono[0]"></CValidation>
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

	import Template from '../template/Template.vue';
	import Configs  from '@/helpers/VarConfig.js';

    export default {
        name:       'FormularioMiPerfil',
        props:      ['poBtnSave'],
        components: {Template},
        data() {
            return {
				Emit: 			 this.poBtnSave.EmitSeccion,
				Img:             null,
				ValidElement:    Configs.validImage2m,
				RutaFile:        '',
				errorvalidacion: [],
				ConfigLoad:{
					ShowLoader: true,
					ClassLoad:  false,
				},
				usuario:         {
					Nombre:      '',
					ApellidoPat: '',
                    ApellidoMat: '',
                    Correo:      '',
					Telefono:    '',
					Imagen:      '',
				},
            }
        },
		methods:{
			Guardar(){
				this.errorvalidacion  	  = [];
				this.poBtnSave.DisableBtn = true;

				let formData = new FormData();
				formData.set('IdUsuario',   this.usuario.IdUsuario);
				formData.set('Nombre',      this.usuario.Nombre);
				formData.set('ApellidoPat', this.usuario.ApellidoPat);
				formData.set('ApellidoMat', this.usuario.ApellidoMat);
				formData.set('Correo',      this.usuario.Correo);
				formData.set('Telefono',    this.usuario.Telefono);
				formData.set('Imagen', 	 	this.usuario.Imagen,);

				let picture = this.$refs.file.files[0];
				formData.append('Imagen',picture);

                return new Promise((resolve, reject) => {

                    this.$http.post('usrupdaprofile',formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then( (res) => {

                        if (res.data.status) {
                            this.Limpiar();
                            this.EjecutaConExito(res);
                            sessionStorage.user = JSON.stringify(res.data.usuario);
                            this.$toast.success(res.data.message,'Tu perfil ha sido actualizado,');
                            resolve(res.data);
                        }
                        
                    })
                    .catch( err => {
                        reject(err.response.data);
                        this.EjecutaConError(err);
                    });

                });

			},
			InfoUsuario() {
				this.$http.get('usrsrchme').then( (res) => {
					this.usuario  = res.data.data;
					this.RutaFile = res.data.RutaFile;
				})
				.finally(() => {
					this.ConfigLoad.ShowLoader = false;
				});
			},
			Limpiar() {
				this.usuario.Nombre      = '';
				this.usuario.ApellidoPat = '';
				this.usuario.ApellidoMat = '';
				this.usuario.Correo      = '';
				this.usuario.Telefono    = '';
				this.usuario.Imagen      = '';
				this.errorvalidacion     = [];
			},			
			EjecutaConExito(res) {
				this.poBtnSave.DisableBtn = false;  
				this.bus.$emit('CloseModal_'+this.Emit);
				this.bus.$emit('List_'+this.Emit);
			},	
			EjecutaConError(err) {
				this.poBtnSave.DisableBtn = false;				

                if(err.response.data.errors) {
                    this.errorvalidacion = err.response.data.errors;
                }

			},
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