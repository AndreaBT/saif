<template>
    <div>
        <img class="wave" src="@/assets/style/image/wave.png">
        <div class="container container-login">
            <div class="img-login">
                <img src="@/assets/style/image/bg.png">
            </div>
            <div class="login-content">
                <form class="form-Login">
                    <img src="@/assets/style/image/avatar-login.png">
                    <h2 class="title">Bienvenido</h2>
                    <div class="input-div one">
                        <div class="icono-login">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="div">
                            <input @keyup.enter="keyEnter(0)" v-model="Usuario.Username" type="text" placeholder="Nombre del usuario" class="input" ref="user">
                        </div>
                    </div>
                    <div class="input-div pass">
                        <div class="icono-login">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="div">
                            <input @keyup.enter="keyEnter(1)" v-model="Usuario.Password" :type="type" class="input input-password" placeholder="Contraseña" ref="pass">
                            <button v-if="Usuario.Password !== ''" @click="ToggleShow" class="button btn-password" type="button" id="button-addon2">
                                <i class="far icono-password" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                            </button>
                        </div>
                    </div>
                    <router-link :to="{ name: 'recuperarpassword'}">
                        ¿Has olvidado tu contraseña?
                    </router-link>
                    <button :disabled="disabled" @click="GetLogin" type="button" class="btn btn-login">
                        <i v-if="disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                        Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name:       'Login',
        props:      [""],
        components: {},
        data() {
            return {                
                type:            'password',
                showPassword:    false,
                password:        null,
                disabled:        false,
                errorvalidacion: [],
                Usuario: {
                    Username: '',
                    Password: '',
                }
            }
        },
        methods: {
            async GetLogin() {

                if (this.Usuario.Username !== '' && this.Usuario.Password !== '') {

                    this.disabled = true;

                    this.$http.post('login', this.Usuario).then((res) =>  {

                        this.disabled = false;

                        if (res.data.status) {
                            this.$store.dispatch('login',      res.data);
                            sessionStorage.setItem('RutaFile', res.data.RutaFile);
                            this.$router.push({name: "inicio", params: {}});
                        } else {
                            this.$toast.warning('Usuario o Contraseña Incorrectos');
                        }

                    })
                    .catch((err) => {
                        this.disabled = false;
                        this.$toast.error(err.response.data.message, 'Error');
                        this.$store.commit('auth_error');
                        this.$store.localStorage.removeItem('user_token');
                        this.$store.reject(err);
                    });
                } else {
                    this.$toast.error('Ingrese los campos faltantes', 'Campos Incompletos,');
                }

            },
            keyEnter(num) {

                if (this.Usuario.Username !== '' && this.Usuario.Password !== '') {
                    this.GetLogin();
                } else if (num === 0  && this.Usuario.Password === ''){
                    this.$refs.pass.focus();
                } else if (num === 1 && this.Usuario.Username === ''){
                    this.$refs.user.focus();
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
            // SI EXISTE SESSIÓN REGRESAMOS A LA VISTA PRINCIPAL (INICIO)
            var datos = JSON.parse( sessionStorage.getItem('user'));

            if(datos != null){
                this.$router.push({name: "inicio", params: {}});
            }

        },
        mounted() {
            this.$refs.user.focus();
        },
    }

</script>
