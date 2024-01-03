
import Empresa            from "@/views/modulos/empresa/miempresa/FormEmpresa.vue";
import Empleado           from "@/views/modulos/empresa/empleados/List.vue";
import FormularioEmpleado from "@/views/modulos/empresa/empleados/FormEmpledo.vue";

const VarRutasEmpresa = [
	{
		path: "/FormularioEmpresa",
		name: "empresa",
		component: Empresa,
		props: true,
	}, {
        path:'/Empleados',
        name: 'empleados',
        component: Empleado,
        props: true, 
    }, {
        path:'/FormularioEmpleado',
        name: 'empleadosave',
        component: FormularioEmpleado,
        props: true
    }
];

export default {
	VarRutasEmpresa,
};
