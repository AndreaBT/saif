<template>
    <div>
    
        <NavLinks></NavLinks>

        <section class="mt-2">
            <div class="container-fluid">
                <transition name="fade" mode="out-in">
                    <router-view :key="$route.fullPath"/>
                </transition>
            </div>
        </section>

    </div>
</template>

<script>

import NavLinks from '@/views/template/NavLinks.vue';

export default {
    name:'Template',
    props:[""],
    components:{
        NavLinks
    },
    data() {
        return {
            SessionStorage: {},
        }
    },
    methods: {
        getJquery() { // INICIO DE USO ESTRICTO
            (function($) { "use strict";
                $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    if (!$(this).next().hasClass('show')) {
                        $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
                    }

                    $(this).siblings().toggleClass("show");

                    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                        $('.dropdown-submenu .show').removeClass("show");
                    });
                });
            })(jQuery);
		},
        Getdatauser() { // DATOS DE SESSIÓN 
            this.SessionStorage = JSON.parse(sessionStorage.getItem('user'));
        },
    },
    created() {
		this.Getdatauser();
    },
    mounted() {
        this.getJquery();
    },
}
</script>
