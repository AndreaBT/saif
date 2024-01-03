<template>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card border-radius">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">                            
                        <b>Tipo de Operación:</b> {{oBitacora.TipoBitacora}}
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">                            
                        <b>Fecha de Modificación:</b> {{oBitacora.FechaEvento}}
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush list-icon list-01">
                <li class="list-group-item">                        
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">                            
                            <b>Modificó:</b>
                        </div>
                        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">                            
                            {{oBitacora.Realizo}}
                        </div>
                    </div>
                </li>
            </ul>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">                            
                        <b>Modificación Realizada:</b>
                    </div>
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">                            
                        {{oBitacora.Descripcion}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name:  "ResumenBitacora",
        props: ['poBtnSave'],
        data() {
            return {
                errorvalidacion: [],
                Emit:            this.poBtnSave.EmitSeccion,
                ConfigLoad:{
                    ShowLoader: true,
                    ClassLoad:  false,
                },
                oBitacora: {
                    IdBitacoraEquipo: 0,
                    IdRealizo:        0,
                    Realizo:          '',
                    IdEquipo:         '',
                    IdAfectado:       '',
                    TipoBitacora:     '',
                    FechaEvento:      '',
                    Descripcion:      '',
                    afectado:{
                        IdUsuario:      0,
                        NombreCompleto: ''
                    }
                }
            }
        },
        methods: {
            Recuperar() {
                this.$http.get("recoveryequipobitacora/"+this.oBitacora.IdBitacoraEquipo).then( (res) => {
                    this.oBitacora = res.data.data;
                }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
            },
            Limpiar() {
                this.oBitacora =  {
                    IdBitacoraEquipo: 0,
                    IdRealizo:        0,
                    Realizo:          '',
                    IdEquipo:         '',
                    IdAfectado:       '',
                    TipoBitacora:     '',
                    FechaEvento:      '',
                    Descripcion:      '',
                    afectado:{
                        IdUsuario:      0,
                        NombreCompleto: ''
                    }
                }
                this.errorvalidacion = [];
            },
        },
        created() {
            this.bus.$off('Recovery_'+this.Emit);
            this.bus.$on('Recovery_'+this.Emit,(Id)=> {
                this.ConfigLoad.ShowLoader = true;

                if(Id !== '')  {
                    this.oBitacora.IdBitacoraEquipo = Id;
                    this.Recuperar();
                } else {
                    this.ConfigLoad.ShowLoader = false;
                }
                
            });
        }

    }

</script>
