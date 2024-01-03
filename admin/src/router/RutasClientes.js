import ClientesActivos     		 from "@/views/modulos/crm/clientes/List.vue";
import FormularioClientesActivos from "@/views/modulos/crm/clientes/FormActivos.vue";


const VarRutasClientes = [
	{
		path: "/ClientesActivos",
		name: "clientesActivos",
		component: ClientesActivos,
		props: true,
	}, {
		path: "/FormularioClientesActivos",
		name: "formularioclientesactivos",
		component: FormularioClientesActivos,
		props: true,
	},
];

export default {
	VarRutasClientes,
};
