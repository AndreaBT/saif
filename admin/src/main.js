//************************************************************ AQUI IMPORTAMOS HERRAMIENTAS IMPORTANTES *******************************************************************************
import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueTreeselect from "@riophae/vue-treeselect";
import vSelect from "vue-select";
import VueSweetalert2 from "vue-sweetalert2";
import VueIziToast from "vue-izitoast";
import VCalendar from "v-calendar";
import VueClipboard from "vue-clipboard2";
import VuePdfApp from "vue-pdf-app";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import draggable from "vuedraggable";


//************************************************************ AQUI IMPORTAMOS ARCHIVOS CSS, JAVASCRIPT, BOOSTRAP, FONTS, ICONOS, HELPERS, ETC ****************************************
// ESTILOS PRINCIPALES
import "bootstrap";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import "vue-select/dist/vue-select.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "izitoast/dist/css/iziToast.css";
import "vue-pdf-app/dist/icons/main.css";


// IMPORT DE COMPONENTES
import '@/import/Index';


// ESTILOS GLOBALES
// CONFIG
import MyPlugin from "@/config/General.js";
import HttpConfig from "@/config/HttpConfig";


// ESTILOS GENERALES
import "@/assets/style/plugin/bootstrap/css/bootstrap.min.css";
import "@/assets/style/plugin/bootstrap/js/bootstrap.min.js";
import "@/assets/style/plugin/popper/popper.js";
import "@/assets/style/plugin/popper/popper.min.js";
import "@/assets/style/plugin/font-awesome/css/font-awesome.min.css";
import "@/assets/style/plugin/font-awesome/css/all.css";
import "@/assets/style/plugin/fontello/css/secont.css";
import "@/assets/style/plugin/jquery/jquery-3.3.1.min.js";


// ESTILOS PROPIOS
import "@/assets/style/css/main.css";
import "@/assets/style/css/style.css";


//************************************************************ AQUI VA LA BASE URL LOCAL, SERVIDOR DEMO, SERVIDOR PRODUCCIÓN **********************************************************
// CONTROL DEL AXIOS
Vue.config.productionTip = false;
Vue.prototype.bus = new Vue;

let token = sessionStorage.getItem("token_user");
Vue.prototype.$http = HttpConfig(token);

//************************************************************ AQUI VA LA CONFIGURACIÓN DE LAS HERRAMIENTAS IMPORTANTES ***************************************************************

// TOAST
const options = {
    confirmButtonColor: "#41b882",
    cancelButtonColor: "#ff7674",
};

// CONFIG GENERAL
Vue.use(MyPlugin, Vue.prototype.$http);

// HELPERS GLOBALES
//Vue.use("VarConfig", VarConfig);

// COMPONENTES GLOBALES DE HERRAMIENTAS IMPORTANTES
library.add(fas);
Vue.component("treeselect", VueTreeselect);
Vue.component("v-select", vSelect);
Vue.component("font-awesome-icon", FontAwesomeIcon);
Vue.component("VuePdfApp", VuePdfApp);
Vue.component("draggable", draggable);

// VUE BOOTSTRAP 
Vue.use(BootstrapVue);

// OPCIONALMENTE, SE INSTALA EL COMPONENTE DEL ICONO DE BOOTSTRAP VUE
Vue.use(IconsPlugin);

// ALERT SWEET 
Vue.use(VueSweetalert2, options);

// VUE TOAST
Vue.use(VueIziToast, {
    theme: 'white', //black
    position: "topRight", //bottomRight, bottomLeft, bottomCenter, topRight, topLeft, topCenter, center
    timeout: 10000, //10000 //false
    close: true,
    overlay: false,
    toastOnce: true,
    balloon: false,
    closeOnEscape: true,
    closeOnClick: true,
});

// VUE CALENDAR
Vue.use(VCalendar);

// VUE CLIP BOARD
Vue.use(VueClipboard);

// VUE MOMENT
Vue.use(require("vue-moment"));

// JQUERY
window.$ = window.jQuery = require("jquery");

new Vue({
    router,
    store,
    render: function(h) { return h(App) }
}).$mount("#app")