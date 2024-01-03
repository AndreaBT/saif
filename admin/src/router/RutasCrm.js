import MenuPrestamos       from "@/views/modulos/crm/prestamos/MenuPrestamos.vue";
import FormularioPrestamos from "@/views/modulos/crm/prestamos/FormPrestamos.vue";
import Prestamos 		   from "@/views/modulos/crm/prestamos/ListPrestamos.vue";
import Autorizados         from "@/views/modulos/crm/prestamos/ListAutorizados.vue";
import PorEntregar         from "@/views/modulos/crm/prestamos/ListPorEntregar.vue";
import Rechazados          from "@/views/modulos/crm/prestamos/ListRechazos.vue";
import Prospectos          from "@/views/modulos/crm/prospectos/List.vue";
import ProspectosDetalles  from "@/views/modulos/crm/prospectos/Form.vue";
import FormReasignacion    from "@/views/modulos/crm/reasignacion/FormReasignacion.vue";

const VarRutasCrm = [
    {
        path: "/MenusPrestamos",
        name: "menuprestamos",
        component: MenuPrestamos,
        props: true,
    }, {
        path: "/Prestamos",
        name: "prestamos",
        component: Prestamos,
        props: true,
    }, {
        path: "/FormularioPrestamos",
        name: "formularioprestamos",
        component: FormularioPrestamos,
        props: true,
    }, {
		path: "/ProspectosPreAutorizados",
		name: "autorizados",
		component: Autorizados,
		props: true,
	}, {
		path: "/PorEntregar",
		name: "porentregar",
		component: PorEntregar,
		props: true,
	}, {
		path: "/Rechazados",
		name: "rechazados",
		component: Rechazados,
		props: true,
	}, {
        path: "/Prospectos",
        name: "prospectos",
        component: Prospectos,
        props: true,
    }, {
        path: "/FormularioProspectos",
        name: "prospectosdetalles",
        component: ProspectosDetalles,
        props: true,
    }, {
        path: "/Reasignacion",
        name: "reasignacion",
        component: FormReasignacion,
        props: true,
    },
];

export default {
    VarRutasCrm,
}
