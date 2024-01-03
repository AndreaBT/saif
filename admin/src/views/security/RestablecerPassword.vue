<template>
    <div>    
        <nav class="navbar navbar-expand-xl">
            <div class="container-fluid h-100">
                <a @click="Ir_a_Login()" class="navbar-brand title-nombre" style="cursor: pointer;">Financiera F</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample06" aria-controls="navbarsExample06" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <section class="container">
            <div class="row mt-3">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <CLoader :pConfigLoad="ConfigLoad">
                        <template slot="BodyFormLoad">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <nav aria-label="breadcrumb">
                                                <ol class="breadcrumb">
                                                    <li @click="Ir_a_Login()" class="breadcrumb-item"><a href="">Login</a></li>
                                                    <li class="breadcrumb-item active" aria-current="page">Restablecer Contraseña</li>
                                                </ol>
                                            </nav>
                                        </div>
                                    </div>
                                                    
                                    <div class="row" v-if="usuarioAccount.visible">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label>Nueva Contraseña</label>
                                            <input v-model="usuarioAccount.password" :type="type1" class="form-control" placeholder="Ingresa Nueva Contraseña">
                                            <button v-if="usuarioAccount.password !== ''" @click="ToggleShow" class="button btn-password-formulario-recovery" type="button" id="button-addon2">
                                                <span class="icon is-small is-right">
                                                    <i class="far icono-password" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">                                
                                            <label>Confirmar Contraseña</label>
                                            <input v-model="usuarioAccount.confirmpassword" :type="type2" class="form-control" placeholder="Confirma la Contraseña">
                                            <button v-if="usuarioAccount.confirmpassword !== ''" @click="ToggleShowConfirm" class="button btn-password-formulario-recovery" type="button" id="button-addon2">
                                                <i class="far icono-password" :class="{ 'fa-eye-slash': showPasswordConfirm, 'fa-eye': !showPasswordConfirm }"></i>
                                            </button>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right mt-5"> 
                                            <button @click="Guardar()" :disabled="oBoton.Disablebtn" type="button" class="btn btn-primary mr-2">{{oBoton.NameOk}}</button>                                   
                                            <button @click="Ir_a_Login()" type="button" class="btn btn-danger">Cancelar</button>
                                        </div>
                                    </div>
                                    
                                    <div class="row" v-else>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="text-center">
                                                <img src="@/assets/style/image/tiempo.png" alt="">
                                                <h4>{{textDefault}}</h4> <br>
                                                <button type="button" @click="Ir_a_Login()" class="btn btn-primary mr-2 btn-block">Inicio</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </CLoader>
                </div>
            </div>
        </section>        
    </div>
</template>

<script>

    import Configs from '@/helpers/VarConfig.js';

    export default {
        name:'RestablecerPassword',
        props:[''],
        data() {
            return {  
                type1:               'password', 
                type2:               'password', 
                showPassword:        false,
                showPasswordConfirm: false,
                password:            null,              
                MasterKey:           '',
                MasterId:            0,
                MasterNum:           0,
                Disablebtn:          false,
                textDefault:         'Lo sentimos pero el link no es válido o ya expiro, por favor vuelva a intentarlo de nueva cuenta.',
                errorvalidacion:     [],
				ConfigLoad:{
					ShowLoader: true,
					ClassLoad:  false,
				},
                usuarioAccount:  {
                    Key:             this.MasterKey,
                    Id:              this.MasterId,
                    Num:             this.MasterNum,
                    password:        '',
                    confirmpassword: '',
                    visible:         false,
                },
                oBoton:          {
                    Disablebtn: false,
                    NameOk:     'Guardar'
                }
            }
        },
        methods: {
            validaToken() {
                this.usuarioAccount.visible = false;

                let resquest = {
                    Key: this.MasterKey,
                    Id:  this.MasterId,
                    Num: this.MasterNum
                }

                this.$http.post('usrvalidreset',resquest).then((res) => {
                    this.usuarioAccount.visible = true;
                    this.usuarioAccount.Key     = this.MasterKey;
                    this.usuarioAccount.Id      = this.MasterId;
                    this.usuarioAccount.Num     = this.MasterNum;
                })
                .catch( err => {
                    this.usuarioAccount.visible = false;
                })
				.finally(() => {
					this.ConfigLoad.ShowLoader = false;
				});

            },
            Guardar() {
                this.BloquearBotones(0);
                this.$http.post('usraceptchangepass', this.usuarioAccount,).then( (res) => {
                    this.BloquearBotones(1);
                    this.Ir_a_Login();
                })
                .catch( err => {
                    this.$toast.error(err.response.data.message);
                    this.BloquearBotones(2);

                    if(err.response.data.errors){
                        this.errorvalidacion = err.response.data.errors;
                    }

                });
            },  
            BloquearBotones(acc){
                this.DesbloquearBotones(false);

                if(acc===1) {

                    this.$swal(Configs.configRestablecerPassword).then((result) => {
                
                        if(result.value) {
                            this.usuarioAccount.password        = '';
                            this.usuarioAccount.confirmpassword = '';
                            this.usuarioAccount.visible         = false;
                            this.textDefault                    = 'Contraseña Actualizada, Gracias.'
                        } 

                    });

                } else if (acc===2) {
                    this.$toast.error('Complete los campos');
                } else {
                    this.DesbloquearBotones(true);
                }

            },
            DesbloquearBotones(bnd){
                this.oBoton.Disablebtn = false;
                this.oBoton.NameOk     = 'Guardar';

                if(bnd){
                    this.oBoton.Disablebtn = true;
                    this.oBoton.NameOk     = 'Espere...';
                }

            },          
            Ir_a_Login() {
                this.$router.push({name:'login',params:{}});
            },
        },
        created() {
            this.MasterKey = this.$route.params.key;
            this.MasterId  = this.$route.params.id;
            this.MasterNum = this.$route.params.num;
            this.validaToken();
        },
        beforeDestroy() {
            this.bus.$off('BloquearBtn');
        }
    }

</script>