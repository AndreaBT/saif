<template>
    <CList :p-config-list="ConfigList">
        <template slot="bodyForm">
            <input type="hidden" :value="ValidarSubs"/>
            <div class="container-flex justify-content-center">
                <div v-for="(itemSubmenu,index2) in ListaSubMenus" :key="index2" class="container-box m-2">
                    <router-link :to="{name: itemSubmenu.Router, params: itemSubmenu.Parametros}" class="color_text">
                        <div class="flexbox-01 text-center">    
                            <i :class="itemSubmenu.Icono"></i>
                        </div>
                        <p class="text-center text-box">{{itemSubmenu.NombreSubMenu}}</p>
                    </router-link>
                </div>
            </div>
        </template>
    </CList>
</template>

<script>

export default {
    name:'SubMenus',
    props:['pListaSubMenus'],
    components:{
    },
    data() {
        return {
            ConfigList: {
                ShowTitleFirst: false,
                Title: "Menús",
                TypeBody: "Form",
                IsModal: false,
                ShowFiltros: false,
                BtnNewShow: false,
                ShowPaginador:  false,
                ShowLoader: false,
            },
            ListaSubMenus: []
        }
    },
    methods: {
    },
    created() {
    },
    mounted() {
    },
    computed: {
        ValidarSubs()
        {
            if(this.pListaSubMenus!== undefined){
                sessionStorage.setItem('oSubMenus',JSON.stringify(this.pListaSubMenus));
            }

            this.ListaSubMenus = JSON.parse(sessionStorage.getItem('oSubMenus'));

            return this.ListaSubMenus;
        }
    }
}
</script>
