import PanelSubMenus   from "@/views/template/SubMenus.vue";
import MontosCredito   from "@/views/catalogos/montosCredito/List.vue";
import Taza            from "@/views/catalogos/tazaInteres/List.vue";
import Perfiles        from "@/views/catalogos/perfiles/List.vue";
import Equipos         from "@/views/catalogos/equipos/List.vue";
import Puestos         from "@/views/catalogos/puestos/List.vue";
import Estados         from "@/views/catalogos/estados/List.vue";
import Municipios      from "@/views/catalogos/municipios/List.vue";
import Rutas           from "@/views/catalogos/rutas/List.vue";
import Permisos        from "@/views/catalogos/permisos/List.vue";
import Paneles         from "@/views/catalogos/paneles/List.vue";
import AsigPermisos    from "@/views/catalogos/menusxPerfil/AsignarList.vue";
import Correo          from "@/views/catalogos/correo/Form.vue";
import BitacoraEquipos from "@/views/catalogos/equipos/bitacora/viewBitacora"

const VarRutasConfiguracion = [{
        path: '/PanelDetalle',
        name: 'panelSubMenus',
        component: PanelSubMenus,
        props: true
    }, {
        path: '/MontosCredito',
        name: 'montoscredito',
        component: MontosCredito,
        props: true
    }, {
        path: "/TazaInteres",
        name: "taza",
        component: Taza,
        props: true
    }, {
        path: '/Equipos',
        name: 'equipos',
        component: Equipos,
        props: true
    }, {
        path: '/BitacoraEquipos',
        name: 'bitacoraequipos',
        component: BitacoraEquipos,
        props: true
    }, {
        path: '/Perfiles',
        name: 'perfiles',
        component: Perfiles,
        props: true
    }, {
        path: '/Puestos',
        name: 'puestos',
        component: Puestos,
        props: true
    }, {
        path: '/Paneles',
        name: 'paneles',
        component: Paneles,
        props: true
    }, {
        path: '/Estados',
        name: 'estados',
        component: Estados,
        props: true
    }, {
        path: '/Municipios',
        name: 'municipios',
        component: Municipios,
        props: true
    }, {
        path: '/Rutas',
        name: 'rutas',
        component: Rutas,
        props: false
    }, {
        path: '/Permisos',
        name: 'permisos',
        component: Permisos,
        props: false
    }, {
        path: '/AsignarPermiso',
        name: 'asignarPermisos',
        component: AsigPermisos,
        props: true
    }, {
        path: '/ConfiguracionCorreoElectronico',
        name: 'correo',
        component: Correo,
        props: true
    },

];

export default {
    VarRutasConfiguracion,
}
