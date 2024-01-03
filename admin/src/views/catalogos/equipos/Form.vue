<template>
    <CLoader :pConfigLoad="ConfigLoad">
        <template slot="BodyFormLoad">
            <form id="Formestado" class="form-horizontal" method="post" autocomplete="off" onSubmit="return false">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input  type="file" @change="$uploadImagePreview($event,ValidElement,Array('Img','imagePreview'))" id="file" name="myfile"  ref="file" accept=".png, .jpg, .jpeg">
                                            <label for="file"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview" :style="'background-image: url('+RutaFile+objEquipos.Imagen+');'" :src="RutaFile+objEquipos.Imagen">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                                    <div class="form-group row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <label for="Nombre">Nombre del Equipo</label>
                                            <input type="text" v-model="objEquipos.Nombre"   maxlength="250" class="form-control" placeholder="Ingresar Nombre" />
                                            <CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*'+errorvalidacion.Nombre[0]"/>
                                        </div>
                                    </div>

                                    <div class="form-group form-row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="item7">Tipo De Equipo</label>
                                            <select name="item7" id="item7" class="form-control form-select" v-model="objEquipos.TipoEquipo">
                                                <option value="">--Seleccionar--</option>
                                                <option v-for="(item, index) in TipoEquipo" :key="index" :value="item.id">{{item.label}}</option>
                                            </select>
                                            <CValidation v-if="this.errorvalidacion.TipoEquipo" :Mensaje="'*'+errorvalidacion.TipoEquipo[0]"/>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="">Marca</label>
                                            <input type="text" v-model="objEquipos.Marca"   maxlength="250" class="form-control" placeholder="Ingresar una Marca" />
                                            <CValidation v-if="this.errorvalidacion.Marca" :Mensaje="'*'+errorvalidacion.Marca[0]"/>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="">Modelo</label>
                                            <input type="text" v-model="objEquipos.Modelo"   maxlength="250" class="form-control" placeholder="Ingresar un Modelo" />
                                            <CValidation v-if="this.errorvalidacion.Modelo" :Mensaje="'*'+errorvalidacion.Modelo[0]"/>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="">Color</label>
                                            <input type="text" v-model="objEquipos.Color"   maxlength="250" class="form-control" placeholder="Ingresar un Color" />
                                            <CValidation v-if="this.errorvalidacion.Color" :Mensaje="'*'+errorvalidacion.Color[0]"/>
                                        </div>
                                    </div>

                                    <div class="form-group form-row" v-if="objEquipos.TipoEquipo == 1">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="">Numero de Serie</label>
                                            <input type="text" v-model="objEquipos.Serie"   maxlength="250" class="form-control" placeholder="Ingresar el Numero de Serie" />
                                            <CValidation v-if="this.errorvalidacion.Serie" :Mensaje="'*'+errorvalidacion.Serie[0]"/>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="">Placa</label>
                                            <input type="text" v-model="objEquipos.Placa"   maxlength="250" class="form-control" placeholder="Ingresar la Placa" />
                                            <CValidation v-if="this.errorvalidacion.Placa" :Mensaje="'*'+errorvalidacion.Placa[0]" />
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-2">
                                            <label for="">Numero Economico</label>
                                            <input type="text" v-model="objEquipos.NumeroEconomico"   maxlength="250" class="form-control" placeholder="Ingresar un Numero Economico" />
                                            <CValidation v-if="this.errorvalidacion.NumeroEconomico" :Mensaje="'*'+errorvalidacion.NumeroEconomico[0]" />
                                        </div>
                                    </div>

                                    <div class="form-group form-row" v-if="objEquipos.TipoEquipo == 2">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="">Numero Teléfonico</label>
                                            <input type="text" v-model="objEquipos.Telefono" @input="$onlyNums($event,objEquipos,'Telefono');" maxlength="10" class="form-control" placeholder="Ingresar Teléfono" />
                                            <CValidation v-if="this.errorvalidacion.Telefono" :Mensaje="'*'+errorvalidacion.Telefono[0]"/>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <label for="">IMEI</label>
                                            <input type="text" v-model="objEquipos.Imei" class="form-control" placeholder="Ingresar el IMEI del Teléfono" />
                                            <CValidation v-if="this.errorvalidacion.Imei" :Mensaje="'*'+errorvalidacion.Imei[0]"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!--fin col-12-->
                    </div>
                </div>
            </form>
        </template>
    </CLoader>
</template>

<script>

    import $$             from "jquery"
    import Configs        from '@/helpers/VarConfig.js';
    import StaticComboBox from '@/helpers/StaticComboBox.js';

    export default {
        name:  "FormEquipos",
        props: ['poBtnSave'],
        components:{
            //CBtnSave
        },
        data() {
            return {
                RutaFile:        '',
                Img:             null,
                Emit:            this.poBtnSave.EmitSeccion,
                ValidElement:    Configs.validImage2m,
                TipoEquipo:      StaticComboBox.Equipo,
                errorvalidacion: [],
                ConfigLoad: {
                    ShowLoader: true,
                    ClassLoad: false,
                },
                objEquipos: {
                    IdEquipo:        0,
                    Nombre:          '',
                    Marca:           '',
                    Modelo:          '',
                    Color:           '',
                    Serie:           '',
                    Placa:           '',
                    Telefono:        '',
                    Imei:            '',
                    Anio:            '',   
                    TipoEquipo:      '',             
                    NumeroEconomico: '',
                    Asignado:        'NO',    
                },
            }
        },
        methods :
        {
            async Guardar()
            {
                this.errorvalidacion=[];
                this.poBtnSave.toast = 0;
                this.poBtnSave.DisableBtn = true;

                let formData = new FormData();
                formData.set('IdEquipo',        this.objEquipos.IdEquipo);
                formData.set('Nombre',          this.objEquipos.Nombre);
                formData.set('Marca',           this.objEquipos.Marca);
                formData.set('Modelo',          this.objEquipos.Modelo);
                formData.set('Color',           this.objEquipos.Color);
                formData.set('Serie',           this.objEquipos.Serie);
                formData.set('Placa',           this.objEquipos.Placa);
                formData.set('Telefono',        this.objEquipos.Telefono);
                formData.set('Imei',            this.objEquipos.Imei);
                formData.set('Anio',            this.objEquipos.Anio);
                formData.set('TipoEquipo',      this.objEquipos.TipoEquipo);
                formData.set('NumeroEconomico', this.objEquipos.NumeroEconomico);
                formData.set('Imagen',          this.objEquipos.Imagen);
                formData.set('Asignado',        this.objEquipos.Asignado);

                let picture = this.$refs.file.files[0];
                formData.append('Imagen',picture);

                this.$http.post('equipos', formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((res)=>{
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
            Recuperar() {
                this.$http.get("equipos/"+this.objEquipos.IdEquipo
                ).then( (res) => {
                    this.objEquipos = res.data.data;
                    this.RutaFile   = res.data.rutaFile;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.objEquipos = {
                    IdEquipo:        0,
                    Nombre:          '',
                    Marca:           '',
                    Modelo:          '',
                    Color:           '',
                    Serie:           '',
                    Placa:           '',
                    Telefono:        '',
                    Imei:            '',
                    Anio:            '',   
                    TipoEquipo:      '',             
                    NumeroEconomico: '',
                    Asignado:        'NO'
                };
                this.errorvalidacion = [];
            },
        },
        created() {
            this.poBtnSave.toast = 0;

            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(Id) => {
                this.ConfigLoad.ShowLoader = true;
                this.poBtnSave.DisableBtn = false;

                this.bus.$off('Save_'+this.Emit);

                this.bus.$on('Save_'+this.Emit,() => {
                    this.Guardar();
                });

                this.Limpiar();

                if(Id!='') {
                    this.objEquipos.IdEquipo = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }

            });
        },
    }

</script>
