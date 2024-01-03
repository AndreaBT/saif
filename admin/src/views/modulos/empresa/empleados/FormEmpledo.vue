<template>
    <div>
        <CList :pConfigList="ConfigList">
            <template slot="bodyForm">
                <CLoader :pConfigLoad="ConfigLoad">
                    <template slot="BodyFormLoad">
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">D. Personales</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">D. Familiares</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link " id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">D. Empleado</a>
                                    </li>
                                    <li class="nav-item" role="presentation" style="display:none;">
                                        <a class="nav-link " id="tab4-tab" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">Evidencia</a>
                                    </li>
                                </ul>

                                <div class="tab-content shadow-sm" id="myTabContent">
                                    <!--Parte uno-->
                                    <div class="tab-pane fade active show" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                                                    <fieldset>
                                                        <legend class="col-form-label">Datos</legend>
                                                        <div class="form-row">
                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                                                                <div class="avatar-upload">
                                                                    <div class="avatar-edit">
                                                                        <input id="file" @change="$uploadImagePreview($event,ValidElement, Array('Img','imagePreview'))" ref="fileImg" type="file" name="file" accept=".png, .jpg, .jpeg">
                                                                        <label for="file"></label>
                                                                    </div>
                                                                    <div class="avatar-preview">
                                                                        <div id="imagePreview" :style="'background-image: url('+RutaFile+objUsuarioEmleado.UrlImg+');'" :src="RutaFile+objUsuarioEmleado.UrlImg">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
                                                                <div class="form-group form-row">
                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item1">Nombre(s)</label>
                                                                        <input type="text" name="item1" id="item1" class="form-control" placeholder="Nombre(s)" v-model="objUsuarioEmleado.Nombre">
                                                                        <CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*'+errorvalidacion.Nombre[0]"/>
                                                                    </div>

                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item2">Apellido Paterno</label>
                                                                        <input type="text" name="item2" id="item2" class="form-control" placeholder="Apellido Paterno" v-model="objUsuarioEmleado.ApellidoPat">
                                                                        <CValidation v-if="this.errorvalidacion.ApellidoPat" :Mensaje="'*'+errorvalidacion.ApellidoPat[0]"/>
                                                                    </div>

                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item3">Apellido Materno</label>
                                                                        <input type="text" name="item3" id="item3" class="form-control" placeholder="Apellido Materno" v-model="objUsuarioEmleado.ApellidoMat">
                                                                        <CValidation v-if="this.errorvalidacion.ApellidoMat" :Mensaje="'*'+errorvalidacion.ApellidoMat[0]"/>
                                                                    </div>

                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item4">Género</label>
                                                                        <select name="item4" id="item4" class="form-control form-select" v-model="objUsuarioEmleado.Genero">
                                                                            <option value="0">--Seleccionar--</option>
                                                                            <option v-for="(item, index) in ListaGeneros" :key="index" :value="item.id">{{item.label}}</option>
                                                                        </select>
                                                                        <CValidation v-if="this.errorvalidacion.Genero" :Mensaje="'*'+errorvalidacion.Genero[0]"/>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group form-row">
                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label>Fecha de Nacimiento</label>
                                                                        <v-date-picker  :masks="masks" :popover="{ visibility: 'focus' }" locale="es"  @input="CalcularEdad()" v-model="objUsuarioEmleado.FechaNacimiento" :max-date="Obten18Anios()"><!---->
                                                                            <template v-slot="{ inputValue, inputEvents }">
                                                                                <input class="form-control cal" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
                                                                            </template>
                                                                        </v-date-picker>
                                                                        <CValidation v-if="this.errorvalidacion.FechaNacimiento" :Mensaje="'*'+errorvalidacion.FechaNacimiento[0]"/>
                                                                    </div>

                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item5">Edad</label>
                                                                        <input type="text" name="item5" id="item5" class="form-control" readonly placeholder="Edad" v-model="objUsuarioEmleado.Edad">
                                                                    </div>

                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item6">Estado Civil</label>
                                                                        <select name="item6" id="item6" class="form-control form-select" v-model="objUsuarioEmleado.EstadoCivil">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <option v-for="(item, index) in estadosCiviles" :key="index" :value="item.id">{{item.label}}</option>
                                                                        </select>
                                                                        <CValidation v-if="this.errorvalidacion.EstadoCivil" :Mensaje="'*'+errorvalidacion.EstadoCivil[0]"/>
                                                                    </div>

                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item7">Nacionalidad</label>
                                                                        <input type="text" name="item7" id="item7" class="form-control" placeholder="Nacionalidad" v-model="objUsuarioEmleado.Nacionalidad">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group form-row">
                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item8">Teléfono</label>
                                                                        <input type="text" name="item8" id="item8" class="form-control" placeholder="Ejm. 9999999999 " v-model="objUsuarioEmleado.Telefono" @input="$onlyNums($event,objUsuarioEmleado,'Telefono')" maxlength="10">
                                                                        <CValidation v-if="this.errorvalidacion.Telefono" :Mensaje="'*'+errorvalidacion.Telefono[0]"/>
                                                                    </div>

                                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                                        <label for="item9">Correo</label>
                                                                        <input type="text" name="item9" id="item9" class="form-control" placeholder="example@example.com" v-model="objUsuarioEmleado.Correo" >
                                                                        <CValidation v-if="this.errorvalidacion.Correo" :Mensaje="'*'+errorvalidacion.Correo[0]"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset>
                                                        <legend class="col-form-label">Domicilio</legend>
                                                        <div class="form-row">
                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item10">Calle</label>
                                                                <input type="text" name="item10" id="item10" class="form-control" placeholder="Calle" v-model="objUsuarioEmleado.Calle" >
                                                                <CValidation v-if="this.errorvalidacion.Calle" :Mensaje="'*'+errorvalidacion.Calle[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item11">Número</label>
                                                                <input type="text" name="item11" id="item11" class="form-control" placeholder="Número" v-model="objUsuarioEmleado.NoInt">
                                                                <CValidation v-if="this.errorvalidacion.NoInt" :Mensaje="'*'+errorvalidacion.NoInt[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item12">Cruzamientos</label>
                                                                <input type="text"  name="item12" id="item12" class="form-control" placeholder="Cruzamientos" v-model="objUsuarioEmleado.NoExt">
                                                                <CValidation v-if="this.errorvalidacion.NoExt" :Mensaje="'*'+errorvalidacion.NoExt[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item13">Codigo Postal</label>
                                                                <input type="text" name="item13" id="item13" class="form-control" placeholder="Codigo Postal" v-model="objUsuarioEmleado.Cp" @input="$onlyNums($event,objUsuarioEmleado,'Cp')" maxlength="10">
                                                                <CValidation v-if="this.errorvalidacion.Cp" :Mensaje="'*'+errorvalidacion.Cp[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item14">Estado</label>
                                                                <select v-model="objUsuarioEmleado.IdEstado" name="item14" id="item14" class="form-control form-select" @change="GetMunicipios()">
                                                                    <option value="0">--Seleccionar--</option>
                                                                    <option v-for="(item, index) in ListaEstados" :key="index" :value="item.IdEstado">
                                                                        {{ item.Nombre }}
                                                                    </option>
                                                                </select>
                                                                <CValidation v-if="this.errorvalidacion.IdEstado" :Mensaje="'*'+errorvalidacion.IdEstado[0]"/>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item15">Municipio</label>
                                                                <select v-model="objUsuarioEmleado.IdMunicipio"  name="item15" id="item15" class="form-control form-select">
                                                                    <option :value="0">--Seleccionar--</option>
                                                                    <option v-for="(item, index) in ListaMunicipios" :key="index" :value="item.IdMunicipio">
                                                                        {{ item.Nombre }}
                                                                    </option>
                                                                </select>
                                                                <CValidation v-if="this.errorvalidacion.IdMunicipio" :Mensaje="'*'+errorvalidacion.IdMunicipio[0]"/>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset>
                                                        <legend class="col-form-label">Datos de acceso</legend>
                                                        <div class="form-row">
                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item16">Usuario</label>
                                                                <input type="text" name="item16" id="item16" class="form-control" placeholder="Nombre del Usuario" v-model="objUsuarioEmleado.username" >
                                                                <CValidation v-if="this.errorvalidacion.username" :Mensaje="'*'+errorvalidacion.username[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item17">Perfil</label>
                                                                <select @change="ListaPerfiles" v-model="objUsuarioEmleado.IdPerfil" name="item17" id="item17" class="form-control form-select">
                                                                    <option value="0">--Seleccionar--</option>
                                                                    <option  v-for="(item, index) in ListaDePerfiles" :key="index" :value="item.IdPerfil" >{{item.Nombre}}</option>
                                                                </select>
                                                                <CValidation v-if="this.errorvalidacion.IdPerfil" :Mensaje="'*'+errorvalidacion.IdPerfil[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item18">Contraseña</label>
                                                                <input  v-model="objUsuarioEmleado.password" :type="type1" name="item18" id="item18" class="form-control" placeholder="Contraseña">
                                                                <button v-if="objUsuarioEmleado.password !== ''" @click="ToggleShow" class="button btn-password-formulario" type="button" id="button-addon2">
                                                                    <i class="far icono-password" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                                                </button>
                                                                <CValidation v-if="this.errorvalidacion.password" :Mensaje="'*'+errorvalidacion.password[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item19">Confirmar Contraseña</label>
                                                                <input v-model="objUsuarioEmleado.password_confirmation" :type="type2" name="item19" id="item19" class="form-control" placeholder="Confirmar Contraseña">
                                                                <button v-if="objUsuarioEmleado.password_confirmation !== ''" @click="ToggleShowConfirm" class="button btn-password-formulario" type="button" id="button-addon2">
                                                                    <i class="far icono-password" :class="{ 'fa-eye-slash': showPasswordConfirm, 'fa-eye': !showPasswordConfirm }"></i>
                                                                </button>
                                                                <CValidation v-if="this.errorvalidacion.password_confirmation" :Mensaje="'*'+errorvalidacion.password_confirmation[0]"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                                                                <label for="item20">Accesos</label>
                                                                <b-form-checkbox class="mt-1" v-model="checked" name="check-button" id="item20" switch>
                                                                    APP
                                                                </b-form-checkbox>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Parte dos-->
                                    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                        <div class="container-fluid">
                                            <div class="row mt-2">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                                                    <fieldset>
                                                        <legend class="col-form-label">Datos Familiares</legend>
                                                        <div class="form-row">
                                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                                <div class="form-inline justify-content-end mb-2">
                                                                    <button type="button" @click="AgregarDatosFam" class="btn btn-primary btn-sm pill"><i class="fas fa-plus-circle"></i> Datos Familiares</button>
                                                                </div>

                                                                <CTablita :pConfigList="ConfigList2">
                                                                    <template slot="header">
                                                                        <th>#</th>
                                                                        <th>Tipo De familiar</th>
                                                                        <th>Nombre</th>
                                                                        <th>Teléfono</th>
                                                                        <th>Calle</th>
                                                                        <th>Número</th>
                                                                        <th>Cruzamientos</th>
                                                                        <th class="text-center">Acciones</th>
                                                                    </template>

                                                                    <template slot="body">
                                                                        <tr v-for="(item,index) in DatosFam" :key="index">
                                                                            <td>{{(index+1)}}</td>
                                                                            <td>
                                                                                <select  class="form-control form-select" v-model="item.TipoDeDato">
                                                                                    <option value="0">--Seleccionar--</option>
                                                                                    <option v-for="(itemDatFam, indexDatFam) in tipoRefFamiliar" :key="indexDatFam" :value="itemDatFam.id">{{itemDatFam.label}}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Nombre" v-model="item.NombreFam">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Teléfono" @input="$onlyNums($event,item,'TelefonoFam')" v-model="item.TelefonoFam" maxlength="10">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Calle" v-model="item.CalleFam">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Número" v-model="item.NoIntFam">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Cruzamientos" v-model="item.NoExtFam">
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <button type="button" @click="QuitarDatosFam(index)"  class="btn btn-danger btn-icon"><i class="fas fa-trash-alt"></i></button>
                                                                            </td>
                                                                        </tr>
                                                                        <CSinRegistro :pContIF="DatosFam.length" :pColspan="9"></CSinRegistro>
                                                                    </template>
                                                                </CTablita>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <fieldset class="form-group form-row justify-content-center">
                                                        <legend class="col-form-label">Datos de Referencia</legend>
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-9">
                                                            <div class="form-inline justify-content-end mb-2">
                                                                <button type="button" @click="AgregarDatosRef" class="btn btn-primary btn-sm pill"><i class="fas fa-plus-circle"></i> Referencia</button>
                                                            </div>

                                                            <CTablita :pConfigList="ConfigList2">
                                                                <template slot="header">
                                                                    <th>#</th>
                                                                    <th>Nombre</th>
                                                                    <th>Teléfono</th>
                                                                    <th class="text-center">Acciones</th>
                                                                </template>

                                                                <template slot="body">
                                                                    <tr v-for="(item,index) in Ref" :key="index">
                                                                        <td><strong>Referencia {{index+1}}</strong></td>
                                                                        <td>
                                                                            <input type="text" class="form-control" placeholder="Nombre" v-model="item.NombreRef">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control" placeholder="Teléfono" v-model="item.TelefonoRef" @input="$onlyNums($event,item,'TelefonoRef')" maxlength="10">
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button" @click="QuitarDatosRef(index)"  class="btn btn-danger btn-icon"><i class="fas fa-trash-alt"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                    <CSinRegistro :pContIF="Ref.length" :pColspan="4"></CSinRegistro>
                                                                </template>
                                                            </CTablita>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Parte tres-->
                                    <div class="tab-pane fade " id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                                        <div class="container-fluid">
                                            <div class="row justify-content-center">
                                                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-6">
                                                    <fieldset>
                                                        <legend class="col-form-label">Historial de actividad</legend>
                                                        <div class="form-group form-row">
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <label for="item20">Puesto</label>
                                                                <select @change="ListaPuestos" v-model="objUsuarioEmleado.IdPuesto" name="item20" id="item20" class="form-control form-select">
                                                                    <option value="0">--Seleccionar--</option>
                                                                    <option  v-for="(item, index) in ListaDePuestos" :key="index" :value="item.IdPuesto" >{{item.Nombre}}</option>
                                                                </select>
                                                                <CValidation v-if="this.errorvalidacion.IdPuesto" :Mensaje="'*'+errorvalidacion.IdPuesto[0]"/>
                                                            </div>
                                                        </div>

                                                        <div class="form-group form-row">
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <label for="">Fecha de Alta <i class="fas fa-arrow-alt-up text-success"></i></label>
                                                                <v-date-picker :masks="masks" :popover="{ visibility: 'focus'}" locale="es" v-model="objUsuarioEmleado.FechaAlta" :maxDate="new Date()">
                                                                    <template v-slot="{ inputValue, inputEvents }">
                                                                        <input class="form-control cal" name="FechaEntrega[]" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
                                                                    </template>
                                                                </v-date-picker>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <label for="">Fecha de Baja <i class="fas fa-arrow-alt-down text-danger"></i></label>
                                                                <input type="text" class="form-control" placeholder="0000-00-00" readonly v-model="objUsuarioEmleado.FechaBaja" >
                                                            </div>
                                                        </div>

                                                        <div class="form-group form-row">
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <label for="">Finiquito</label>
                                                                <input type="text" class="form-control" placeholder="$0.00" readonly v-model="objUsuarioEmleado.Finiquito">
                                                                <CValidation v-if="this.errorvalidacion.Finiquito" :Mensaje="'*'+errorvalidacion.Finiquito[0]"/>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" >
                                                                <label for="">Fecha de Finiquito</label>
                                                                <input type="text" class="form-control" placeholder="0000-00-00" readonly v-model="objUsuarioEmleado.FechaFiniquito">
                                                            </div>
                                                        </div>

                                                        <div class="form-group form-row">
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <label for="">PDF Huellas y Firma</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" @change="$uploadFileCustom($event,validElementFile,Array('Huella','NameFile'))" ref="huella" accept="application/pdf"  id="myInputH">
                                                                    <label for="myInputH" class="custom-file-label">{{NameFile}}</label>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <button type="button" v-if="objUsuarioEmleado.Huella!=''" class="btn btn-primary btn-sm mt-4" @click="OpenHuella(objUsuarioEmleado.Huella)"  data-toggle="modal" data-target="#staticBackdrop">Ver PDF <i class="fas fa-file-pdf"></i></button>
                                                            </div>
                                                        </div>

                                                        <div class="form-group form-row">
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <label for="">Evidencia</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" @change="$uploadFileCustom($event,validElementFile,Array('Evidencia','NameEvi'))" ref="evidencia" accept="application/pdf" id="myInputE">
                                                                    <label for="myInputE" class="custom-file-label">{{NameEvi}}</label>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                                <button type="button" v-if="objUsuarioEmleado.Evidencia!=''" class="btn btn-primary btn-sm mt-4" @click="OpenEvidencia(objUsuarioEmleado.Evidencia)" data-toggle="modal" data-target="#staticBackdrop">Ver PDF <i class="fas fa-file-pdf"></i></button>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="col-12 col-sm-12 col-md-10 col-lg-8 col-xl-6">
                                                    <fieldset>
                                                        <legend class="col-form-label">Herramientas de Trabajo</legend>
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
                                                            <div class="row justify-content-end">
                                                                <button type="button" @click="SetearHerramientas();" v-if="ArrayHerramientas.length == 0" class="btn btn-sm btn-primary" v-b-tooltip.hover.Top title="Agregar Herramientas">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Tipo</th>
                                                                    <th>Descripción</th>
                                                                    <th>Fecha de Entrega</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(item,index) in ArrayHerramientas" :key="index">
                                                                    <td v-if="item.TipoHerramienta == 'Vehiculo'">  Vehículo </td>
                                                                    <td v-else-if="item.TipoHerramienta == 'Telefono'"> Teléfono </td>
                                                                    <td v-else> {{item.TipoHerramienta}} </td>
                                                                    <td>
                                                                        <template v-if="item.TipoHerramienta == 'Vehiculo' || item.TipoHerramienta == 'Telefono'">
                                                                            <div class="input-group ">
                                                                                <input type="text" v-model="item.Descripcion" class="form-control" placeholder="Herramienta de trabajo">
                                                                                <div class="input-group-append">
                                                                                    <button class="btn-buscar btn-primary" @click="OpenEquipos(item)" type="button" id="button-addon2">
                                                                                        <i class="far fa-search"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                        <template v-else>
                                                                            <input type="text" v-model="item.Descripcion" name="Descripcion[]" class="form-control" placeholder="Descripción">
                                                                        </template>
                                                                    </td>
                                                                    <td>
                                                                        <v-date-picker :masks="masks" :popover="{ visibility: 'focus'}" locale="es" v-model="item.FechaEntrega" :maxDate="new Date()">
                                                                            <template v-slot="{ inputValue, inputEvents }">
                                                                                <input class="form-control cal" name="FechaEntrega[]" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
                                                                            </template>
                                                                        </v-date-picker>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12 text-right">
                                                    <CBtnSave :poBtnSave="oBtnSave" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Parte cuatro--->
                                    <div class="tab-pane fade " id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12">
                                                    <fieldset>
                                                        <legend class="col-form-label">Evidencias</legend>
                                                        <div class="form-row">
                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                                <label for="Ine1">Evidencia 1</label>
                                                                <input ref="file1" type="file" name="file1" id="file1" class="form-control" accept=".png, .jpg, .jpeg"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                                <label for="Ine1">Evidencia 2</label>
                                                                <input ref="file2" type="file" name="file2" id="file2" class="form-control" accept=".png, .jpg, .jpeg"/>
                                                            </div>

                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                                <label for="Ine1">Evidencia 3</label>
                                                                <input ref="file3" type="file" name="file3" id="file3" class="form-control" accept=".png, .jpg, .jpeg"/>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </CLoader>
            </template>
        </CList>

        <CModal :pConfigModal="ConfigModal" :poBtnSave="oBtnSave2">
            <template slot="Form">
                <ListEquipos :poBtnSave="oBtnSave2"></ListEquipos>
            </template>
        </CModal>
    </div>
</template>


<script>

import Configs        from '@/helpers/VarConfig.js';
import StaticComboBox from '@/helpers/StaticComboBox.js';
import moment         from 'moment'
import ListEquipos    from '@/views/modulos/empresa/empleados/ListEquipos.vue';
const  EmitEjecuta    =    'seccionEmpleado';
const  EmitEquipos    =    'seccionEquipos';

export default {
    name: 'FormEmpleados',
    props:['Id'],
    components:{
        ListEquipos
    },
    data() {
        return{
            RutaFile:            '',
            RutaPdf:             '',
            RutaPdfEvi:          '',
            type1:               'password',
            type2:               'password',
            showPassword:        false,
            showPasswordConfirm: false,
            password:            null,
            Img:                 null,
            checked:             false,
            ListaGeneros:        StaticComboBox.Genero,
            estadosCiviles:      StaticComboBox.EstadosCiviles,
            tipoRefFamiliar:     StaticComboBox.TipoFamiliar,
            ValidElement:        Configs.validImage2m,
            validElementFile:    Configs.validFile5m,
            NameFile:            Configs.validFile5m.NameFile,
            NameEvi:             Configs.validFile5m.NameFile,
            errorvalidacion:    [],
            ListaEstados:       [],
            ListaMunicipios:    [],
            ListaDePerfiles:    [],
            ListaDePuestos:     [],
            DatosFam:           [],
            Ref:                [],
            ArrayHerramientas:  [],
            ConfigList:{
                Title:          'Formulario Empleados',
                ShowLoader:     false,
                IsModal:        false,
                BtnReturnShow:  true,
                ShowSearch:     false,
                ShowPaginador:  false,
                ShowEntradas:   false,
                BtnNewShow:     false,
                TypeBody:       'Form',
                ShowTitleFirst: false,
                EmitSeccion:    EmitEjecuta,
            },
            ConfigList2: {
                ShowLoader:     false,
                IsModal:        false,
                BtnReturnShow:  true,
                ShowSearch:     false,
                ShowPaginador:  false,
                ShowEntradas:   false,
                BtnNewShow:     false,
                TypeBody:       'List',
                ShowTitleFirst: false,
                EmitSeccion:    EmitEjecuta,
            },
            oBtnSave: {
                toast:         0,
                IsModal:       false,
                ShowBtnSave:   true,
                ShowBtnCancel: false,
				DisableBtn:    false,
                EmitSeccion:   EmitEjecuta,
            },
            ConfigLoad:{
                ShowLoader: true,
                ClassLoad:  false,
            },
            ConfigModal: {
                ModalTitle:  "",
                ModalNameId: "ModalForm2",
                EmitSeccion: EmitEquipos,
                ModalSize:   "lg",
                ShowFooter:  false,
            },
            oBtnSave2: {
                toast: 0,
                IsModal: true,
                DisableBtn: false,
                EmitSeccion: EmitEquipos,
            },
            objUsuarioEmleado:{
                //!empleado
                IdEmpleado:      0,
                Rfc:             '',
                Calle:           '',
                NoInt:           '',
                NoExt:           '',
                Cp:              '',
                FechaNacimiento: '',
                EstadoCivil:     '',
                Finiquito:       '',
                FechaAlta:       '',
                FechaBaja:       '',
                FechaEntrega:    '',
                FechaFiniquito:  '',
                Genero:          0,
                Nacionalidad:    '',
                Edad:            '',

                //!usuario
                IdPerfil:              0,
                IdPuesto:              0,
                IdUsuario:             0,
                IdEstado:              0,
                IdMunicipio:           0,
                Nombre:                '',
                ApellidoPat:           '',
                ApellidoMat:           '',
                Telefono:              '',
                username:              '',
                password:              '',
                NombreCompleto:        '',
                Correo:                '',
                password_confirmation: '',
                Imagen:                '',
                UrlImg:                '',

                //!EmpDarosFam
                IdEmpdatosFam: 0,
                CalleFam:      '',
                TipoDeDato:    '',
                TelefonoFam:   '',
                NombreFam:     '',
                NoIntFam:      '',
                NoExtFam:      '',

                //!Empleados Ref
                IdEmpRefPer: 0,
                NombreRef:   '',
                TelefonoRef: '',

                //!Empleados evi
                IdEmpleadoEvidencia: 0,
                Evidencia:           '',
                Huella:              '',
                Anio:                '',
            },
            masks: {
                input: "YYYY-MM-DD",
            },
        }
    },
    methods:{
        async Guardar() {
            let formData = new FormData();
            formData.set('IdUsuario',  this.objUsuarioEmleado.IdUsuario);
            formData.set('IdEmpleado', this.objUsuarioEmleado.IdEmpleado);
            formData.set('Rfc',        this.objUsuarioEmleado.Rfc);

            // PESTAÑA 1
            formData.set('ImagenAntigua',this.objUsuarioEmleado.UrlImg);

            let Imagen = this.$refs.fileImg.files[0];
            formData.append('Imagen',Imagen);

            formData.set('Nombre',          this.objUsuarioEmleado.Nombre);
            formData.set('ApellidoPat',     this.objUsuarioEmleado.ApellidoPat);
            formData.set('ApellidoMat',     this.objUsuarioEmleado.ApellidoMat);
            formData.set('NombreCompleto',  this.objUsuarioEmleado.NombreCompleto);
            formData.set('Genero',          this.objUsuarioEmleado.Genero);
            formData.set('FechaNacimiento', moment(this.objUsuarioEmleado.FechaNacimiento).format("YYYY-MM-DD"));
            formData.set('Edad',            this.objUsuarioEmleado.Edad);
            formData.set('EstadoCivil',     this.objUsuarioEmleado.EstadoCivil);
            formData.set('Nacionalidad',    this.objUsuarioEmleado.Nacionalidad);
            formData.set('Telefono',        this.objUsuarioEmleado.Telefono);
            formData.set('Correo',          this.objUsuarioEmleado.Correo);

            formData.set('Calle',       this.objUsuarioEmleado.Calle);
            formData.set('NoInt',       this.objUsuarioEmleado.NoInt);
            formData.set('NoExt',       this.objUsuarioEmleado.NoExt);
            formData.set('Cp',          this.objUsuarioEmleado.Cp);
            formData.set('IdEstado',    this.objUsuarioEmleado.IdEstado);
            formData.set('IdMunicipio', this.objUsuarioEmleado.IdMunicipio);

            formData.set('IdPerfil',              this.objUsuarioEmleado.IdPerfil);
            formData.set('username',              this.objUsuarioEmleado.username);
            formData.set('password',              this.objUsuarioEmleado.password);
            formData.set('password_confirmation', this.objUsuarioEmleado.password_confirmation);

            if(this.checked==true) {
                formData.set('UsuarioApp',1);
            } else {
                formData.set('UsuarioApp',0);
            }

            // PESTAÑA 2
            formData.set('Ref',      JSON.stringify(this.Ref));
            formData.set('DatosFam', JSON.stringify(this.DatosFam));

            // PESTAÑA 3
            formData.set('IdPuesto',          this.objUsuarioEmleado.IdPuesto);
            formData.set('FechaAlta',         moment(this.objUsuarioEmleado.FechaAlta).format("YYYY-MM-DD"));
            formData.set('FechaBaja',         this.objUsuarioEmleado.FechaBaja);
            formData.set('Finiquito',         this.objUsuarioEmleado.Finiquito);
            formData.set('FechaFiniquito',    this.objUsuarioEmleado.FechaFiniquito);
            formData.set('FechaEntrega',      this.objUsuarioEmleado.FechaEntrega);
            formData.set('EvidenciaAnterior', this.objUsuarioEmleado.Evidencia);
            formData.set('HuellaAnterior',    this.objUsuarioEmleado.Huella);

            let Huellas = this.$refs.huella.files[0];
            formData.append('Huella',Huellas);

            let Evidence = this.$refs.evidencia.files[0];
            formData.append('Evidencia',Evidence);

            this.ArrayHerramientas.forEach((item,index)=>{
                if(item.FechaEntrega!=''){
                    item.FechaEntrega = moment(item.FechaEntrega).format('YYYY-MM-DD');
                }
            });

            formData.set('arrayHerramientas',JSON.stringify(this.ArrayHerramientas));


            this.errorvalidacion     = [];
            this.oBtnSave.toast      = 0;
            this.oBtnSave.DisableBtn = true;

            await this.$http.post('users', formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((res)=>{
                this.EjecutaConExito(res);
            }).catch(err=>{
                this.EjecutaConError(err);
            });
        },
        EjecutaConExito(res)
        {
            this.oBtnSave.DisableBtn = false;
            this.bus.$emit('RunAlerts_'+this.ConfigList.EmitSeccion,1);
            this.Regresar();
        },
        EjecutaConError(err)
        {
            this.oBtnSave.DisableBtn = false;

            if(err.response.data.errors){
                this.errorvalidacion = err.response.data.errors;
                this.oBtnSave.toast  = 2;
            } else {
                this.$toast.error(err.response.data.message);
            }

        },
        Recuperar() {
            this.$http.get("users/getUsuEmple/"+this.objUsuarioEmleado.IdEmpleado).then( (res) => {
                this.objUsuarioEmleado = res.data.data;
                this.DatosFam          = res.data.datosFam;
                this.Ref               = res.data.datosRef;
                this.ArrayHerramientas = res.data.datosHer;
                this.RutaFile          = res.data.rutaFile;
                this.RutaPdf           = res.data.rutaPDF;
                this.RutaPdfEvi        = res.data.rutaPDFevi;

                let FechaNac = this.objUsuarioEmleado.FechaNacimiento;
                let FechaAlt = this.objUsuarioEmleado.FechaAlta;

                if(FechaNac!='' && FechaNac != null){
                    let Fecha1 = FechaNac.replace(/-/g,'\/');
                    this.objUsuarioEmleado.FechaNacimiento = Fecha1;
                }

                if(FechaAlt!='' && FechaAlt != null){
                    let Fecha2 = FechaAlt.replace(/-/g,'\/');
                    this.objUsuarioEmleado.FechaAlta = Fecha2;
                }

                this.ArrayHerramientas.forEach((item,index)=>{
                    if(item.FechaEntrega!='' && item.FechaEntrega!=null){
                        item.FechaEntrega = item.FechaEntrega.replace(/-/g,'\/');
                    }
                });

                this.GetMunicipios();
                this.CalcularEdad();

                if (this.objUsuarioEmleado.UsuarioApp==0) {
                        this.checked=false
                } else {
                    this.checked=true
                }

                if(this.objUsuarioEmleado.Huella!=''){
                    this.NameFile = this.objUsuarioEmleado.Huella;
                }
                if(this.objUsuarioEmleado.Evidencia!=''){
                    this.NameEvi = this.objUsuarioEmleado.Evidencia;
                }
            }).finally(()=>{
                this.ConfigLoad.ShowLoader = false;
            });
        },
        async ListaPerfiles(){
            await this.$http.get('perfiles', {
                params:{
                    simple: 1
                }
            }).then( (res) => {
                this.ListaDePerfiles = res.data.data.perfiles;
            });
        },
        async ListaPuestos() {
            await this.$http.get('puestos', {
                params:{}
            }).then( (res) => {
                this.ListaDePuestos = res.data.data.Puesto.data;
            });
        },
        async GetEstados() {
            await this.$http.get('estados').then(res => {
                this.ListaEstados = res.data.data;

            }).catch(err =>{
                this.ListaEstados = [];
                console.error(err.response);
            });
        },
        GetMunicipios() {
            if (parseInt(this.objUsuarioEmleado.IdEstado) > 0) {
                this.ListaMunicipios = [];

                this.$http.get('municipios',{
                    params:{
                        IdEstado: this.objUsuarioEmleado.IdEstado
                    }
                }).then(res => {
                    this.ListaMunicipios = res.data.data;

                }).catch(err =>{
                    this.ListaMunicipios = [];
                    console.error(err.response);
                });
            }else {
                this.ListaMunicipios = [];
            }
        },
        Limpiar() {
            this.objUsuarioEmleado = {
                IdEmpleado:      0,
                Rfc:             '',
                Calle:           '',
                NoInt:           '',
                NoExt:           '',
                Cp:              '',
                FechaNacimiento: '',
                EstadoCivil:     '',
                Finiquito:       '',
                FechaAlta:       '',
                FechaBaja:       '',
                FechaEntrega:    '',
                FechaFiniquito:  '',
                Genero:          0,
                Nacionalidad:    '',
                Edad:            '',

                //!usuario
                IdPerfil:              0,
                IdPuesto:              0,
                IdUsuario:             0,
                IdEstado:              0,
                IdMunicipio:           0,
                Nombre:                '',    
                ApellidoPat:           '',    
                ApellidoMat:           '',    
                Telefono:              '',
                username:              '',
                password:              '',
                NombreCompleto:        '',
                Correo:                '',
                password_confirmation: '',
                Imagen:                '',
                Evidencia:             '',
                Huella:                '',
            };
        },
        CalcularEdad(){
            let edad     = this.objUsuarioEmleado.FechaNacimiento;
            var hoy      = new Date();
            var fechaNac = new Date(edad);
            var edadAct  = hoy.getFullYear() - fechaNac.getFullYear();
            var m        = hoy.getMonth() - fechaNac.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
                edad--;
            }

            this.objUsuarioEmleado.Edad=edadAct;
        },
        Obten18Anios() {
            let anio = moment(new Date()).subtract(18, 'years');
            anio     = new Date(anio);
            return anio
        },
        AgregarDatosFam() {
            this.DatosFam.push({TipoDeDato:0,NombreFam:'',TelefonoFam:'',CalleFam:'', NoIntFam:'',NoExtFam:''})
        },
        QuitarDatosFam(index) {
            this.$swal(Configs.configEliminarItem).then((result) => {

                if(result.value) {
                    this.$swal(Configs.configEliminarConfirm);
                    this.DatosFam.splice(index,1);
                }

            });
        },
        AgregarDatosRef() {
            this.Ref.push({NombreRef:'',TelefonoRef:''});
        },
        QuitarDatosRef(index) {
            this.$swal(Configs.configEliminarItem).then((result) => {

                if(result.value) {
                    this.$swal(Configs.configEliminarConfirm);
                    this.Ref.splice(index,1);
                }

            });
        },
        OpenHuella(File) {
            let pdfWindow = window.open(this.RutaPdf+File);
            pdfWindow.document.write("<iframe width='100%' height='100%' src='" + this.RutaPdf+File +"'></iframe>");
        },
        OpenEvidencia(File) {
            let pdfWindow = window.open(this.RutaPdfEvi+File);
            pdfWindow.document.write("<iframe width='100%' height='100%' src='" + this.RutaPdfEvi+File +"'></iframe>");
        },
        OpenEquipos(item) {
            let obj = {
                IdEquipo: item.IdEquipo,
                TipoHerramienta: item.TipoHerramienta
            }
            this.bus.$emit('NewModal_'+EmitEquipos,obj);
        },
        SetearHerramientas() {
            let Herramienta = ['Vehiculo','Telefono','Cargador','Casco','Impermeable','Chaleco','Uniformes','Marcador'];

            for(var i=0; i<8; i++) {

                let obj = {
                    IdEquipoxUsuario : 0,
                    IdUsuario:         0,
                    IdEquipo:          0,
                    TipoHerramienta:   Herramienta[i],
                    Descripcion:       '',
                    FechaEntrega:      '',
                };

                this.ArrayHerramientas.push(obj);
            }
        },
        SetearValorEquipo(obj)  {
            let arr = this.ArrayHerramientas.filter(function(item,index){
                if(item.TipoHerramienta == obj.TipoHerr){
                    return item;
                } else {
                    return '';
                }
            });

            if(arr[0]) {
                arr[0].IdEquipo    = obj.IdEquipo;
                arr[0].Descripcion = obj.Nombre;
            }

        },
        Obten18Anios()
        {
            let anio = new Date().getFullYear()+'-12-31';
            anio = moment(new Date(anio)).subtract(18, 'years').add(1,'day');
            anio = new Date(anio);
            return anio
        },
        ToggleShow() {

            if (this.showPassword = !this.showPassword) {
                this.type1 = 'text'
            } else {
                this.type1 = 'password'
            }

        },
        ToggleShowConfirm() {

            if (this.showPasswordConfirm = !this.showPasswordConfirm) {
                this.type2 = 'text'
            } else {
                this.type2 = 'password'
            }

        },
        Regresar() {
            this.$router.push({name:'empleados',params:{}});
        },
    },
    created(){
        this.oBtnSave.toast = 0;
        this.bus.$off('EmitReturn');
        this.bus.$off('pAsignarEquipo');
        this.bus.$off('Save_'+this.ConfigList.EmitSeccion);
    },
    mounted(){
        this.oBtnSave.DisableBtn = false;
        this.Limpiar();
        this.ListaPerfiles();
        this.ListaPuestos();
        this.GetEstados();

        this.bus.$on('Save_'+this.ConfigList.EmitSeccion,()=>
        {
            this.Guardar();
        });

        if(this.Id!=''){
            this.objUsuarioEmleado.IdEmpleado=this.Id;
            this.Recuperar();
        } else {
            this.SetearHerramientas();
            this.ConfigLoad.ShowLoader = false;
        }

        this.bus.$on('EmitReturn',()=>
        {
            this.Regresar();
        });

        this.bus.$on('pAsignarEquipo',(obj)=>
        {
            this.SetearValorEquipo(obj);
        });
    },
}

</script>
