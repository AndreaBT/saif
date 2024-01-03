<template>
    <div>           
        <b-pagination class="later-derecho mt-3 pagination justify-content-center" v-model="CurrentPage" :total-rows="Validate" :perPage="FiltroP.Entrada" size="sm"></b-pagination>
    </div>
</template>

<script>

export default {
    name: "CPagina",
    props: ['Filtro'],
    data() {
        return {
            CurrentPage: 1,
            FiltroP:{
                Pagina: 1,
                Entrada: 10,
                TotalItem: '',
            }
        }
    },
    methods: {
    },
    created() {
    },
    computed: {
        Validate() 
        {
            if (this.Filtro != undefined)
            {
                // CANTIDAD DE REGISTROS POR PAGINA
                if (this.Filtro.Entrada != undefined) {
                    this.FiltroP.Entrada = this.Filtro.Entrada;
                }
                // TOTAL DE REGISTROS
                if (this.Filtro.TotalItem != undefined) {
                    this.FiltroP.TotalItem = this.Filtro.TotalItem;
                }
                // NUMERO DE PAGINA ACTUAL
                if (this.Filtro.Pagina != undefined) {
                    this.CurrentPage = this.Filtro.Pagina;
                }
            }
            
            return this.FiltroP.TotalItem;
        }
    },
    watch: { 
        // RECIBE LA PAGINA ACTUAL CLICKEADA
        CurrentPage(newPage)
        {
            this.Filtro.Pagina = newPage;
            // EJECUTA EL EMIT DEL @PAGINA, QUE SE ENCUENTRA EN EL COMPONENTE CPAGINA EN EL CLIST PARA QUE REALICE EL FILTRADO
            this.$emit('Pagina');
        }
    }
}
</script>