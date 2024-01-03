<template>
    <div>
        <img class="wave" src="../../assets/style/image/wave.png">
        <div class="container container-login">
            <div class="img-login">
                <img src="../../assets/style/image/bg.png">
            </div>
            <div class="login-content">
                <form class="form-Login">
                    <h2 class="title2">Recuperar contraseña.</h2>
                    <div class="input-div one">
                        <div class="icono-login">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="div">
                            <input v-model="correo" type="text" class="input" placeholder="Correo Electrónico">
                        </div>  
                    </div>      
                    <div class="div">                                              
                        <button :disabled="disabled" @click="EnviarCorreo" type="button" class="btn btn-block btn-login mt-5">
                            <i v-if="disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                            Recuperar
                        </button>   
                    </div>           
                    <div class="div text-lg fs-4">
                        <router-link class="text-center" :to="{ name: 'login'}">
                            Regresar al login.
                        </router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        name:       'RecuperarPassword',
        props:      [''],
        components: { },
        data() {
            return {
                disabled: false,
                correo:   '',
            }
        },
        methods: {
            EnviarCorreo() {
                this.errorvalidacion = [];

                if(this.correo.trim() ==='') {
                    this.$toast.warning('Ingresar un correo electrónico');
                    return false;
                }

                let resquest = {
                    correo: this.correo
                }
                
                this.disabled = true;

                this.$http.post('usresetacount', resquest).then( (res) => {
                    this.disabled = false;
                    this.$toast.info('Se ha enviado un correo electrónico para reestablecer su contraseña');
                    this.ir_a_login();
                })
                .catch( (err) => {
                    
                    this.disabled = false;

                    if(err.response.data.errors) {
                        this.errorvalidacion=err.response.data.errors;
                        this.$toast.info(JSON.stringify(err.response.data.errors.Correo[0]));
                    } else {
                        this.$toast.warning(err.response.data.message);
                    }

                });

            },
            ir_a_login() {
                this.$router.push({name:'login',params:{}});
            },
        },
    }

</script>