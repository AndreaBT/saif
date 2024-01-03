<template>
    <div>
        <div class="form-row">
            <div  class="col-lg-12 form-group">

                <div class="form-check">
                    <input v-model="Accion" value="AceptaAmbos" class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Aceptar ambos (Prospecto y Préstamo)
                    </label>
                </div>

                <div class="form-check">
                    <input v-model="Accion" value="AceptaProspecto" class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Aceptar solo Prospecto
                    </label>
                </div>

                <div class="form-check">
                    <input v-model="Accion" value="RechazoAmbos" class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                    <label class="form-check-label" for="flexRadioDefault3">
                       Rechazar ambos (Prospecto y Préstamo)
                    </label>
                </div>

                <div  v-if="Accion === 'AceptaProspecto' || Accion === 'RechazoAmbos'" >
                    <hr>
                    <label> Motivo de Rechazo del <template v-if="Accion === 'AceptaProspecto'"> Prestamo </template> <template v-else-if="Accion === 'RechazoAmbos'"> Prospecto </template> </label>
                    <textarea placeholder=" Coloque sus Observaciones" v-model="MotivoRechazo" class="form-control" cols="12" rows="3"></textarea>
                    <CValidation v-if="this.errorvalidacion.MotivoRechazo" :Mensaje="'*'+errorvalidacion.MotivoRechazo[0]"></CValidation>
                </div>

            </div>
        </div>
   </div>
</template>
<script>
import Configs from "@/helpers/VarConfig.js";
import CValidation from '@/components/CValidation.vue'

export default {
	name: "ModalAutorizar",
    props:['poBtnSave'],
	components: {CValidation},
	data() {
		return {
            objPorspectoxPrestamo:{
                IdCliente       : 0,
				Prospecto       : 0,
				Estatus         : '',
                MotivoRechazo   : '',
                FechaRechazo    : '',
                IdPrestamo      : 0,
                clienteRechazo  : '',
                PrestamoRechazo : '',
            },

            Emit: this.poBtnSave.EmitSeccion,
            errorvalidacion: [],
            Accion:"AceptaAmbos",
            MotivoRechazo:''
		};
	},
	methods: {

        Acciones() {
            this.poBtnSave.toast = 0;
            this.poBtnSave.DisableBtn = true;
            this.errorvalidacion = [];

            let request = {
                IdCliente: this.objPorspectoxPrestamo.IdCliente,
                IdPrestamo: this.objPorspectoxPrestamo.IdPrestamo,
                Operacion: this.Accion,
                MotivoRechazo: this.MotivoRechazo
            };

            this.$http.post('prospestoPrestamoEtapas', request).then( (res) => {
                this.EjecutaConExito(res);

            }).catch((err) => {
                this.EjecutaConError(err);
            });
        },

        EjecutaConExito(res) {
            this.poBtnSave.DisableBtn = false;
            this.poBtnSave.toast = 1;
            this.bus.$emit('CloseModal_' + this.Emit);
            this.bus.$emit('List_' + this.Emit);
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

        limpiar() {
            this.objPorspectoxPrestamo = {
                IdCliente: 0,
                Prospecto: 0,
                Estatus: '',
                MotivoRechazo: '',
                FechaRechazo: '',
                IdPrestamo: 0,
                clienteRechazo: '',
                PrestamoRechazo: '',
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
                    this.Acciones();
                });

                if (obj.IdPrestamo !== '') {
                    this.objPorspectoxPrestamo = obj;
                }
            });
        },

        mounted() {

        },

	// beforeDestroy() {
	//     this.bus.$off('List');
	// },
};
</script>
