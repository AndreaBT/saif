/******************NOTAS******************/

// 1.- SEPARAR LAS VISTAS DE LOS CATALOGOS CON LOS DE LOS MODULOS.
// 2.- LOS NOMBRES DE LOS COMPONENTES SIEMPRE IRAN CON MAYUSCULAS.
//     EL PATH Y EL COMPONENT COMIENZAN CON MAYUSCULA CADA INICIO DE PALABRA.
//     EL NAME COMIENZA CON MINUSCULA.
// 3.- SI REQUIERE PROPS, ACTIVARLO CON UN TRUE.

/**************FIN DE NOTAS**************/

// -------------------------------------------------------------------------- IMPORTACIONES PRINCIPALES -----------------------------------------------------------
import Vue       from "vue";
import VueRouter from "vue-router";
import Store     from "../store/index";

// -------------------------------------------------------------------------- IMPORTACIONES GENERALES --------------------------------------------------------------
import Template          from "@/views/template/Template.vue";
import Login             from "@/views/security/Login.vue";
import RecuperarPassword from "@/views/security/RecuperarPassword.vue";
import RecoveryAccount   from "@/views/security/RestablecerPassword.vue";

// -------------------------------------------------------------------------- IMPORTACIONES HIJAS ------------------------------------------------------------------
import RouterImportInicio        from "./RutasInicio.js";
import RouterImportEmpresa       from "./RutasEmpresa.js";
import RouterImportClientes      from "./RutasClientes.js";
import RouterImportCrm           from "./RutasCrm.js";
import RouterImportCobranza      from "./RutasCobranza.js";
import RouterImportConfiguracion from "./RutasConfiguracion.js";


const ArrayRutas = RouterImportInicio.VarRutasInicio.concat(
    RouterImportEmpresa.VarRutasEmpresa,
    RouterImportClientes.VarRutasClientes,
    RouterImportCrm.VarRutasCrm,
    RouterImportCobranza.VarRutasCobranza,
    RouterImportConfiguracion.VarRutasConfiguracion,
);

Vue.use(VueRouter);

const routes = [{
    path: "/",
    name: "login",
    component: Login,
}, {
    // ------------------------------------------------ RUTAS GENERALES ------------------------------------------------
    path: "/Inicio",
    name: "template",
    component: Template,
    props: true,
    meta: {
        requiresAuth: true,
    },
    children: ArrayRutas,
}, {
    path: '/RecuperarPassword',
    name: 'recuperarpassword',
    component: RecuperarPassword,
    props: true,
}, {
    path: '/recoveryaccount/:key/:id/:num',
    name: 'recoveryaccount',
    component: RecoveryAccount,
}];

const router = new VueRouter({
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (Store.getters.isLoggedIn) {
            next();
            return;
        }
        next("/");
    } else {
        next();
    }
});

const originalPush       = VueRouter.prototype.push;

VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}

export default router;
