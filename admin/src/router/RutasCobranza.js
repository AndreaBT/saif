
import CorteDia  	 from "@/views/modulos/cobranza/cortedeldia/List.vue";
import CorteDiaPagos from "@/views/modulos/cobranza/cortediapago/List.vue";

const VarRutasCobranza = [
	{
		path: "/CorteDelDia",
		name: "corteDia",
		component: CorteDia,
		props: true,
	}, {
		path: "/CorteDiaPagos",
		name: "corteDiaPagos",
		component: CorteDiaPagos,
		props: true,
	},
];

export default {
	VarRutasCobranza,
};
