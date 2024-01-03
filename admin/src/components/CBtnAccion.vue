<template>
    <span>
        {{GetVal}}

        <span v-if="ConfigAccion.ShowBtnEdit">
            <button type="button" v-b-tooltip.hover.Top title="Editar" class="btn btn-primary btn-icon mr-1" @click="EditRegister(ConfigAccion.Id)" v-if="ConfigAccion.IsModal">
                <i class="fas fa-pencil"></i>
            </button>

            <button type="button" v-b-tooltip.hover.Top title="Editar" class="btn btn-primary btn-icon mr-1" @click="EditRegister(ConfigAccion.Id)" v-else>
                <i class="fas fa-pencil"></i>
            </button>    
        </span>

        <button type="button" v-b-tooltip.hover.Top title="Eliminar" class="btn btn-danger btn-icon mr-1" @click="DeleteRegister(ConfigAccion.Id)" v-if="ConfigAccion.ShowBtnDelete">
            <i class="fas fa-trash-alt"></i>
        </button>

        <slot name="btnaccion"></slot>
    </span>
</template>

<script>

export default {
    name:  "CBtnAccion",
    props:['pShowBtnEdit','pShowBtnDelete' ,'pIsModal','pId','pGoRoute','pObj','pHide','pEmitSeccion'],
    data() {
        return {
            ConfigAccion:{
                Id:            '',
                GoRoute:       '',
                IsModal:       true,
                ShowBtnEdit:   true,
                ShowBtnDelete: true,
                EmitSeccion:   '',
                Hide:          {},
                Obj:           {}
            }
        }
    },
    methods: {
        EditRegister(Id)
        {
            if (this.ConfigAccion.IsModal == true)
            {
                this.bus.$emit('NewModal_'+this.ConfigAccion.EmitSeccion,Id,this.ConfigAccion.Obj);
                if (this.Hide != undefined){
                    this.HideAction(this.Hide.Ocultar,this.Hide.Accion);
                }
            }
            else
            {
                this.$router.push({name:this.ConfigAccion.GoRoute, params:{Id:Id,Obj:this.ConfigAccion.Obj}})
            }
        },
        DeleteRegister(Id)
        {
            this.bus.$emit('Delete_'+this.ConfigAccion.EmitSeccion,Id); 
        },
        HideAction(Ocultar,Accion)
        {
            // SOLO RECIBE 2 ACCIONES, UNICAMENTE SIRVE PARA EL CASO DE MODAL 
            // 1-Save PARA OCULTAR BOTON GUARDAR
            // 2-Footer PARA OCULTAR TODO EL PIE
            if(Accion == 'Save'){
                this.bus.$emit('HideSave_'+this.ConfigAccion.EmitSeccion,Ocultar);
            }
            else
            {
                this.bus.$emit('HideFooter_'+this.ConfigAccion.EmitSeccion,Ocultar);
            }
        }
    },
    created(){
    },
    mounted(){
    },
    computed:{
        GetVal()
        {
            if (this.pShowBtnEdit != undefined){
                this.ConfigAccion.ShowBtnEdit = this.pShowBtnEdit;
            }
            if (this.pShowBtnDelete != undefined){
                this.ConfigAccion.ShowBtnDelete = this.pShowBtnDelete;
            }
            if (this.pIsModal != undefined){
                this.ConfigAccion.IsModal = this.pIsModal;
            }
            if (this.pId != undefined){
                this.ConfigAccion.Id = this.pId;
            }
            if (this.pGoRoute != undefined){
                this.ConfigAccion.GoRoute = this.pGoRoute;
            }
            if (this.pObj != undefined){
                this.ConfigAccion.Obj = this.pObj;
            }
            if (this.pEmitSeccion != undefined){
                this.ConfigAccion.EmitSeccion = this.pEmitSeccion;
            }
            if (this.pHide != undefined){
                this.Hide = this.pHide
            }

            return '';
        }
    },
}
</script>