<template>
    <div>
        <div class="form-row">
            <div  class="col-lg-12 form-group">
                <label>Motivo de Rechazo del <template v-if="isPropecto === 'AceptaProspecto'"> Prestamo </template> <template v-else-if="isPropecto === 'RechazoAmbos'"> Prospecto </template></label>
                <textarea readonly placeholder="Motivo de Rechazo" v-model="MotivoRechazoGeneral" class="form-control" cols="12" rows="3">

                </textarea>

            </div>
        </div>
   </div>
</template>
<script>
import CValidation from '@/components/CValidation.vue';
import Configs     from "@/helpers/VarConfig.js";

export default {
	name: "RechazoObserRechazo",
    props:['poBtnSave'],
	components: {CValidation},
	data() {
		return {
			Autorizar: 1,
            errorvalidacion: [],
            Emit: this.poBtnSave.EmitSeccion,

            objPorspectoxPrestamo:{
                Estatus: "",
                EstatusP: "",
                FechaRechazo: "",
                IdCliente: 0,
                IdCobratario: 0,
                IdPrestamo: 0,
                MontoSolicitud: "",
                MotivoRechazo: "",
                NombreCompleto: "",
                Origen: "",
                PrestamoMotRechazo: "",
                Prospecto: 0,
                Telefono: ""
            },

            MotivoRechazoGeneral: '',
            isPropecto: null

		};
	},
	methods: {

	},

	created() {
         this.poBtnSave.toast = 0;
        this.bus.$off('Recovery_'+this.Emit);
        this.bus.$on('Recovery_'+this.Emit,(obj)=>
        {

            this.bus.$off('Save_'+this.Emit);
            this.bus.$on('Save_'+this.Emit,()=> {
            });

            if(obj !== '') {
                this.objPorspectoxPrestamo = obj;

                if ( this.objPorspectoxPrestamo.Estatus === 'Rechazado' && this.objPorspectoxPrestamo.Prospecto == 1 ) {
                    this.MotivoRechazoGeneral=obj.MotivoRechazo;
                    this.isPropecto = 'RechazoAmbos';
                }else{
                    this.MotivoRechazoGeneral=obj.PrestamoMotRechazo;
                    this.isPropecto = 'AceptaProspecto';
                }

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
