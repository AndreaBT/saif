<template>
	<div>
		<h1></h1>
	</div>
</template>

<script>

	export default {
		name:  "ListaInicio",
		props: [],
		components: {
		},
		data() {
			return {
				ListaEstadosArray: [],
			};
		},
		methods: {
			async ListaEstados() {
				await this.$http
					.get("estados", {
						params: {},
					})
					.then((res) => {
						this.ListaEstadosArray = res.data.data;
						if (this.ListaEstadosArray != undefined) {
							sessionStorage.setItem("ListaEstadosArray", JSON.stringify(this.ListaEstadosArray));
						}
					});
			},
		},
		created() {
			this.ListaEstados();
		},
	};

</script>
