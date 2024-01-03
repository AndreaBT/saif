<template>
    <div>
        <input type="hidden" :value="ShowProps">
        <b-modal  class="modal" :ref="ConfigModal.ModalNameId" :id="ConfigModal.ModalNameId" @hidden="GetClose" @shown="GetData" :size="ConfigModal.ModalSize" hide-footer no-close-on-backdrop>
            <template v-slot:modal-header="{}" class="modal-header bg-modal">
                <h5 class="modal-title" id="exampleModalLabel">{{ConfigModal.ModalTitle}}</h5>
                <button @click="HideModal()" type="button" class="close close-2" aria-label="Close">
                    <i class="fad fa-times-circle"></i>
                </button>
            </template>

            <div class="modal-body">
                <slot name="Form"></slot>
            </div>

            <span v-if="ConfigModal.ShowFooter">
                <CBtnSave :poBtnSave="poBtnSave"></CBtnSave>
            </span>
        </b-modal>
    </div>
</template>

<script>

export default {
    name: "CModal",
    props: ['pConfigModal','poBtnSave'],
    data() {
        return {
            ConfigModal:{
                IdElement: 0,
                ModalNameId: 'ModalForm',
                ModalTitle: 'Formulario',
                ModalSize: 'md',
                ShowFooter: true,
                TypeModal: 1,
                CloseModal: true,
                EmitSeccion: '',
                Obj:{},
            },
        }
    },
    methods: {
        ShowModal(Id,Objeto)
        {
            // CUANDO ABRE EL MODAL EJECUTA ESTA ACCION
            this.$refs[this.ConfigModal.ModalNameId].show();
            this.ConfigModal.IdElement = Id;
            this.ConfigModal.Obj = Objeto;
        },
        HideModal()
        {
            this.$refs[this.ConfigModal.ModalNameId].hide()
            this.GetClose();
        },
        ToggleModal()
        {
            // We pass the ID of the button that we want to return focus to
            // when the modal has hidden
            this.$refs[this.ConfigModal.ModalNameId].toggle('#toggle-btn');  
        },
        GetData()
        {
            // CUANDO ABRE EL MODAL SE EJECUTA ESTA ACCION, PARA EL FORM
            this.bus.$emit('Recovery_'+this.ConfigModal.EmitSeccion,this.ConfigModal.IdElement,this.ConfigModal.Obj);
        },
        GetClose()
        {
            /* this.bus.$off('List');
            this.bus.$emit('List',objList); */
        },
        HideFooter(Ocultar)
        {
            // SI RECIBE LA BANDERA DESDE EL LIST OCULTA EL PIE DEL MODAL
            if(Ocultar === true){
                this.ConfigModal.ShowFooter = false;
            }
            else{
                this.ConfigModal.ShowFooter = true;
            }
        },
        HideSave(Ocultar)
        {
            // SI RECIBE LA BANDERA DESDE EL LIST OCULTA EL BOTON SAVE DEL MODAL
            if(Ocultar === true){
                this.poBtnSave.ShowBtnSave = false;
            }
            else{
                this.poBtnSave.ShowBtnSave = true;
            }
        }
    },
    created()
    {
    },
    mounted()
    {
        this.bus.$off('CloseModal_'+this.ConfigModal.EmitSeccion);
        this.bus.$off('NewModal_'+this.ConfigModal.EmitSeccion);
        this.bus.$off('HideFooter_'+this.ConfigModal.EmitSeccion);

        this.bus.$on('HideFooter_'+this.ConfigModal.EmitSeccion,(Ocultar)=> 
        {
            this.HideFooter(Ocultar);
        });

        this.bus.$on('CloseModal_'+this.ConfigModal.EmitSeccion,(data,Id)=>
        {
            this.HideModal();
        });

        this.bus.$on('NewModal_'+this.ConfigModal.EmitSeccion,(Id,obj)=> 
        {
            this.ShowModal(Id,obj);

            this.bus.$off('HideSave_'+this.ConfigModal.EmitSeccion);
            this.bus.$on('HideSave_'+this.ConfigModal.EmitSeccion,(Ocultar)=> 
            {
                this.HideSave(Ocultar);
            });

            /* if(Id>0)
            {
                if(this.pConfigModal.ModalTitle != undefined){
                    this.ConfigModal.ModalTitle = this.pConfigModal.ModalTitle;
                }
                else{
                    this.ConfigModal.ModalTitle = 'Editar';
                }
            }
            else
            {
                if(this.pConfigModal.ModalTitle != undefined){
                    this.ConfigModal.ModalTitle = this.pConfigModal.ModalTitle;
                }
                else{
                    this.ConfigModal.ModalTitle = 'Nuevo';
                }
            } */
        });
    },
    computed: {
        ShowProps()
        {
            if(this.pConfigModal != undefined)
            {
                if(this.pConfigModal.ModalNameId == undefined){
                    this.ConfigModal.ModalNameId = "ModalForm";
                }
                else
                {
                    this.ConfigModal.ModalNameId = this.pConfigModal.ModalNameId;
                }
                if(this.pConfigModal.ModalTitle != undefined){
                    this.ConfigModal.ModalTitle = this.pConfigModal.ModalTitle;
                }
                if(this.pConfigModal.ModalSize != undefined){
                    this.ConfigModal.ModalSize = this.pConfigModal.ModalSize;
                }
                if(this.pConfigModal.ShowFooter != undefined){
                    this.ConfigModal.ShowFooter = this.pConfigModal.ShowFooter;
                }
                if(this.pConfigModal.TypeModal != undefined){
                    this.ConfigModal.TypeModal = this.pConfigModal.TypeModal;
                }
                if(this.pConfigModal.CloseModal != undefined){
                    this.ConfigModal.CloseModal = this.pConfigModal.CloseModal;
                }
                if(this.pConfigModal.EmitSeccion != undefined){
                    this.ConfigModal.EmitSeccion = this.pConfigModal.EmitSeccion;
                }
            }

            return this.ConfigModal.ModalTitle;
        }
    },
}
</script>