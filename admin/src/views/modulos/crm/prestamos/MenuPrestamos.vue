<template>
    <div>
        <CList :p-config-list="ConfigList">
            <template slot="bodyForm">
                <div class="container-flex justify-content-center">
                    <div class="container-box m-2">
                        <router-link :to="{name:'prestamos'}" class="color_text">
                            <div class="flexbox-01 text-center">
                                <i class="fal fa-money-bill-alt"></i>
                            </div>
                            <p class="text-center text-box">Pendientes</p>
                        </router-link>
                    </div>
                    <div class="container-box m-2">
                        <router-link :to="{name:'autorizados'}" class="color_text">
                            <div class="flexbox-01 text-center">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="text-center text-box">Autorizados</p>
                        </router-link>
                    </div>
                    <div class="container-box m-2">
                        <router-link :to="{name:'porentregar'}" class="color_text">
                            <div class="flexbox-01 text-center">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <p class="text-center text-box">Por Entregar</p>
                        </router-link>
                    </div>
                    <div class="container-box m-2">
                        <router-link :to="{name:'rechazados'}" class="color_text">
                            <div class="flexbox-01 text-center">
                                <i class="fas fa-ban"></i>
                            </div>
                            <p class="text-center text-box">Rechazados</p>
                        </router-link>
                    </div>
                </div>
            </template>
        </CList>
    </div>
</template>

<script>

    const EmitEjecuta = 'seccionMenus';

    export default {
        name:       "FormMenus",
        props:      [''],
        components: { },
        data() {
            return {
                emmit:           EmitEjecuta,
                ConfigList: {
                    ShowTitleFirst:    false,
                    Title:             "Menús Prestamos",
                    IsModal:           false,
                    BtnNewShow:        false,
                    BtnReturnShow:     false,
                    TypeBody:          "Form",
                    ShowFiltros:       false,
                    ShowFiltrosInline: false,
                    ShowTitleInline:   false,
                    ShowPaginador:     false,
                    EmitSeccion:       EmitEjecuta
                },
            }
        }
    }

</script>
