<template>
    <div>
        <div class="form-row">
            <div  class="col-lg-12 form-group">
                <label> Motivo de Cancelacion del prestamo </label>
                <textarea :readonly="blockEdit" placeholder=" Coloque sus Observaciones" v-model="cancelacionPrestamo.MotivoCancelacion" class="form-control" cols="12" rows="3"></textarea>
                <CValidation v-if="this.errorvalidacion.MotivoCancelacion" :Mensaje="'*'+errorvalidacion.MotivoCancelacion[0]"></CValidation>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="avatar-upload">
                    <div class="avatar-edit">
                        <input :disabled="blockEdit" id="file1" @change="$uploadImagePreview($event,ValidElement, Array('Img','imagePreview1'))"
                            ref="file1" type="file" name="file1" accept=".png, .jpg, .jpeg">
                        <label for="file1"></label>
                    </div>
                    <div class="avatar-preview">
                        <div id="imagePreview1" :style="'background-image: url('+RutaFile+cancelacionPrestamo.Imagen1+');'" :src="RutaFile+cancelacionPrestamo.Imagen1"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="avatar-upload">
                    <div class="avatar-edit">
                        <input :disabled="blockEdit" id="file2" @change="$uploadImagePreview($event,ValidElement, Array('Img','imagePreview2'))"
                            ref="file2" type="file" name="file2" accept=".png, .jpg, .jpeg">
                        <label for="file2"></label>
                    </div>
                    <div class="avatar-preview">
                        <div id="imagePreview2" :style="'background-image: url('+RutaFile+cancelacionPrestamo.Imagen2+');'" :src="RutaFile+cancelacionPrestamo.Imagen2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Configs     from "@/helpers/VarConfig.js";
    import CValidation from '@/components/CValidation.vue'

    export default {
        name:  "CancelarTotalPrestamo",
        props: ['poBtnSave','Operacion'],
        components: {CValidation},
        data() {
            return {
                blockEdit:      false,
                RutaFile:       '',
                ValidElement:   Configs.validImage2m,
                Emit:           this.poBtnSave.EmitSeccion,
                errorvalidacion: [],
                objPorspectoxPrestamo:{
                    IdCliente:              0,
                    IdPrestamo:             0,
                    IdPrestamoxCancelacion: 0,
                },
                cancelacionPrestamo: {
                    IdPrestamo:             0,
                    IdPrestamoxCancelacion: 0,
                    IdUsuario:              0,
                    Imagen1:                "",
                    Imagen2:                "",
                    MotivoCancelacion:      "",
                    TipoCancelacion:        2  
                },
            };
        },
        methods: {
            Guardar() {
                this.poBtnSave.toast      = 0;
                this.poBtnSave.DisableBtn = true;
                this.errorvalidacion      = [];

                let formData = new FormData();
                formData.set("IdPrestamo",        this.objPorspectoxPrestamo.IdPrestamo);
                formData.set("IdCliente",         this.objPorspectoxPrestamo.IdCliente);
                formData.set("TipoCancelacion",   '2');
                formData.set("MotivoCancelacion", this.cancelacionPrestamo.MotivoCancelacion);

                // EVIDENCIA 1
                let Imagen1 = this.$refs.file1.files[0] !== undefined ? this.$refs.file1.files[0] : '' ;
                formData.append("Imagen1", Imagen1);

                // EVIDENCIA 2
                let Imagen2 = this.$refs.file2.files[0] !== undefined ? this.$refs.file2.files[0] : '';
                formData.append("Imagen2", Imagen2);

                this.$http.post('prestamosCancelacion', formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then( (res) => {
                    this.EjecutaConExito(res);
                }).catch((err) => {
                    this.EjecutaConError(err);
                });
            },
            EjecutaConExito(res) {
                this.poBtnSave.DisableBtn = false;
                this.poBtnSave.toast      = 1;
                this.bus.$emit('CloseModal_' + this.Emit);
                this.bus.$emit('List_seccionPrestamoAutorizar');
            },
            EjecutaConError(err) {
                this.poBtnSave.DisableBtn = false;

                if (err.response.data.errors) {
                    this.errorvalidacion = err.response.data.errors;
                    this.poBtnSave.toast = 2;
                } else {
                    this.$toast.error(err.response.data.message);
                }

            },
            async recoveryCancelacion(){
                await this.$http.get("prestamocancelrecovery",{
                    params: {
                        Id:              this.objPorspectoxPrestamo.IdPrestamoxCancelacion,
                        TipoCancelacion: 1
                    }
                }).then( (res) => {
                    this.cancelacionPrestamo = res.data.data;
                    this.RutaFile            = res.data.rutaImagen;
                });
            },
            limpiar() {
                this.objPorspectoxPrestamo = {
                    IdPrestamo:             0,
                    IdCliente:              0,
                    IdPrestamoxCancelacion: 0,
                }
                this.cancelacionPrestamo = {
                    IdPrestamo:             0,
                    IdPrestamoxCancelacion: 0,
                    IdUsuario:              0,
                    Imagen1:                "",
                    Imagen2:                "",
                    MotivoCancelacion:      "",
                    TipoCancelacion:        2
                }
            },
        },
            created() {
                this.limpiar();
                this.poBtnSave.toast = 0;
                this.bus.$off('Recovery_' + this.Emit);
                this.bus.$on('Recovery_' + this.Emit, (obj) => {

                    this.bus.$off('Save_' + this.Emit);
                    this.bus.$on('Save_' + this.Emit, () => {
                        this.Guardar();
                    });

                    if (obj !== '') {

                        this.objPorspectoxPrestamo = obj;

                        if(parseInt(this.Operacion) === 2) {
                            this.blockEdit = true;
                            this.recoveryCancelacion();
                        }
                    }

                });
            },

            mounted() {
            },

        // beforeDestroy() {
        //     this.bus.$off('List');
        // },
    }

</script>
