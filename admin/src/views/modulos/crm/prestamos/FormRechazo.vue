<template>
    <div class="form-row">
        <div  class="col-lg-12 form-group">
            <label>Motivo del Rechazo</label>
                <textarea placeholder="Ingrese un motivo" v-model="MotivoRechazo" class="form-control" rows="3"></textarea>
                <label>
                    <CValidation v-if="this.errorvalidacion.MotivoRechazo" :Mensaje="'*'+errorvalidacion.MotivoRechazo[0]"></CValidation>
                </label>

        </div>
    </div>
</template>

<script>

    import CValidation  from '@/components/CValidation.vue';

    export default {
        name: "ModalAutorizar",
        props:['poBtnSave','prestamos'],
        components: {CValidation},
        data() {
            return {
                Emit:            this.poBtnSave.EmitSeccion,
                errorvalidacion: [],
                objPrestamo:{},

                MotivoRechazo:''
            };
        },
        methods: {
            async Guardar(){
                this.poBtnSave.toast      = 0;
                this.poBtnSave.DisableBtn = true;
                this.errorvalidacion      = [];

                let request = {
                    IdCliente: this.objPrestamo.IdCliente,
                    IdPrestamo: this.objPrestamo.IdPrestamo,
                    Operacion: 'RechazoPrestamo',
                    MotivoRechazo: this.MotivoRechazo
                };

                this.$http.post('prospestoPrestamoEtapas', request).then((res)=>{
                    this.EjecutaConExito(res);
                }).catch(err=>{
                    this.EjecutaConError(err);
                });
            },
            EjecutaConExito(res) {
                this.poBtnSave.DisableBtn = false;
                this.poBtnSave.toast      = 1;
                this.bus.$emit('CloseModal_'+this.Emit);
                this.bus.$emit('List_'+this.Emit);
            },
            EjecutaConError(err) {
                this.poBtnSave.DisableBtn = false;

                if(err.response.data.errors){
                    this.errorvalidacion = err.response.data.errors;
                    this.poBtnSave.toast = 2;
                }
                else{
                    this.$toast.error(err.response.data.message);
                }
            },


        },
        created() {
            this.poBtnSave.toast = 0;
            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(obj)=> {

                this.bus.$off('Save_'+this.Emit);

                this.bus.$on('Save_'+this.Emit,()=> {
                    this.Guardar();

                });

                if (obj.IdPrestamo !== '') {
                    this.objPrestamo = obj;
                }

            });

        },
    };

</script>
