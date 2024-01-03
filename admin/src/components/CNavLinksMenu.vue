<template>
    <ul class="navbar-nav h-100">
        
        <li v-for="(itemMenu,index) in pArrayMenus" :key="index" :class="itemMenu.SubMenus.length>0?'nav-item dropdown':'nav-item'">
            <template v-if="itemMenu.TipoBoton==0">
                <span v-if="itemMenu.SubMenus.length>0">
                    <a class="nav-link dropdown-toggle" href="#" :id="'dropdown'+index" data-toggle="dropdown" aria-expanded="false">
                        <i :class="itemMenu.Icono"></i>
                        <span>
                            {{itemMenu.Menu}} <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" :aria-labelledby="'dropdown'+index">
                        <router-link v-for="(itemSubmenu,index2) in itemMenu.SubMenus" :key="index2"  :to="{ name: itemSubmenu.Router, params: itemSubmenu.Parametros}" class="dropdown-item"> 
                            {{itemSubmenu.NombreSubMenu}}
                        </router-link>
                    </div>
                </span>

                <span v-else>
                    <router-link :to="{ name: itemMenu.Router, params: itemMenu.Parametros}" class="nav-link">
                        <i :class="itemMenu.Icono"></i>
                        {{itemMenu.Menu}}
                    </router-link>
                </span>
            </template>

            <template v-else>
                <router-link :to="{ name: itemMenu.Router, params: {pListaSubMenus:itemMenu.SubMenus, parametros:itemMenu.Parametros}}" class="nav-link">
                    <i :class="itemMenu.Icono"></i>
                    {{itemMenu.Menu}}
                </router-link>
            </template>
        </li>
        <slot name="MenuDerecho"></slot>
    </ul>
</template>
<script>
import Template from '../views/template/Template.vue'
export default {
  components: { Template },
    props:['pArrayMenus'],
    name:'htmlArregloMenus',
    data() {
        return {
        }
    },
}
</script>