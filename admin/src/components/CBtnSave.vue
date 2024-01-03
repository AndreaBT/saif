<template>
    <div class="modal-footer">
        <input type="hidden" :value="NameBtnSave">
        <slot name="btnsaveextra"></slot>

        <span v-if="oBtnSave.ShowBtnCancel">
            <button type="button" class="btn btn-danger" :disabled="oBtnSave.DisableBtn" @click="Clean" v-if="oBtnSave.IsModal">
                {{oBtnSave.TxtBtnCancel}}
            </button>
            <button type="button" class="btn btn-danger" :disabled="oBtnSave.DisableBtn" @click="Return" v-else >
                {{oBtnSave.TxtBtnCancel}}
            </button>
        </span>

        
        <button v-if="oBtnSave.ShowBtnSave" :disabled="oBtnSave.DisableBtn" type="button" class="btn btn-primary" @click="Saved">
            <i v-show="oBtnSave.DisableBtn" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
            <i v-show="oBtnSave.DisableBtn !== true" class="fa fa-plus-circle"></i>
            {{oBtnSave.TxtBtnSave}}
        </button>
        
    </div>
</template>

<script>

export default {
    name:'CBtnSave',
    props:['poBtnSave'],
    data() {
        return {
            oBtnSave:{
                toast:           0,
                IsModal:         true,
                ReturnRoute:     '',
                ShowBtnSave:     true,
                ShowBtnCancel:   true,
                DisableBtn:      false,
                TxtBtnSave:      'Guardar',
                TxtBtnCancel:    'Cancelar',
                ToastMsgSuccess: 'Información guardada',
                ToastMsgWarning: 'Complete los campos',
                EmitSeccion:     '',
            }
        }
    },
    methods:{
        Saved()
        {
            this.bus.$emit('Save_'+this.oBtnSave.EmitSeccion);
        },
        Return()
        {
            this.$router.push({name:this.oBtnSave.ReturnRoute}); 
        },
        Clean()
        {
            //this.bus.$emit('Limpiar_'+this.oBtnSave.EmitSeccion);
            this.bus.$emit('CloseModal_'+this.oBtnSave.EmitSeccion);
        },
        RunAlert()
        {
            if(this.oBtnSave.toast==1){
                this.$toast.success(this.oBtnSave.ToastMsgSuccess);
            }
            else if(this.oBtnSave.toast==2){
                this.$toast.warning(this.oBtnSave.ToastMsgWarning);
            }
            else if(this.oBtnSave.toast==3){
                this.$toast.error(this.poBtnSave.toastmsg);
            }
        }
    },
    created() {
    },
    mounted(){
        this.bus.$off('RunAlerts_'+this.oBtnSave.EmitSeccion);
        this.bus.$on('RunAlerts_'+this.oBtnSave.EmitSeccion,(val)=>
        {
            this.oBtnSave.toast = val;
            this.RunAlert();
        });
    },
    computed: {
        NameBtnSave()
        {
            if(this.poBtnSave!=undefined)
            {
                if(this.poBtnSave.IsModal != undefined){
                    this.oBtnSave.IsModal = this.poBtnSave.IsModal;
                }
                if(this.poBtnSave.ReturnRoute != undefined){
                    this.oBtnSave.ReturnRoute = this.poBtnSave.ReturnRoute;
                }
                if(this.poBtnSave.ShowBtnSave != undefined){
                    this.oBtnSave.ShowBtnSave = this.poBtnSave.ShowBtnSave;
                }
                if(this.poBtnSave.ShowBtnCancel != undefined){
                    this.oBtnSave.ShowBtnCancel = this.poBtnSave.ShowBtnCancel;
                }
                if(this.poBtnSave.DisableBtn != undefined){
                    this.oBtnSave.DisableBtn = this.poBtnSave.DisableBtn;
                }
                if(this.poBtnSave.TxtBtnSave != undefined){
                    this.oBtnSave.TxtBtnSave = this.poBtnSave.TxtBtnSave;
                }
                if(this.poBtnSave.TxtBtnCancel != undefined){
                    this.oBtnSave.TxtBtnCancel = this.poBtnSave.TxtBtnCancel;
                }
                if(this.poBtnSave.ToastMsgSuccess != undefined){
                    this.oBtnSave.ToastMsgSuccess = this.poBtnSave.ToastMsgSuccess;
                }
                if(this.poBtnSave.ToastMsgWarning != undefined){
                    this.oBtnSave.ToastMsgWarning = this.poBtnSave.ToastMsgWarning;
                }
                if(this.poBtnSave.EmitSeccion != undefined){
                    this.oBtnSave.EmitSeccion = this.poBtnSave.EmitSeccion;
                }
                if(this.poBtnSave.toast != undefined){
                    this.oBtnSave.toast = this.poBtnSave.toast;
                }

                this.RunAlert();
            }
            
            return this.oBtnSave.TxtBtnSave;
        }  
    },
}

</script>