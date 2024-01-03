<template>
	<CList :pConfigList="ConfigList">
		<template slot="bodyForm">
			<CLoader :pConfigLoad="ConfigLoad">
				<template slot="BodyFormLoad">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
								General
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
								Préstamos
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">
								Evidencia
							</a>
						</li>
					</ul>
					<div class="tab-content shadow-sm" id="myTabContent">
							<!--Parte uno-->
                            <div class="tab-pane fade active show" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <fieldset>
                                                <legend class="col-form-label">Datos Personales</legend>
                                                <div class="form-row mt-4">
                                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
														<div class="avatar-upload">
                                                            <div class="avatar-edit">
                                                                <input id="file" @change="$uploadImagePreview($event,ValidElement, Array('Img','imagePreview'))" ref="file" type="file" name="file" accept=".png, .jpg, .jpeg">
                                                                <label for="file"></label>
                                                            </div>
                                                            <div class="avatar-preview">
																<div id="imagePreview" :style="'background-image: url('+RutaFile+objCliente.UrlImg+');'" :src="RutaFile+objCliente.UrlImg"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
														<!-- <legend class="col-form-label">Datos personales</legend> -->
                                                        <div class="form-group form-row">
                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
																<label for="Nombre">Nombre</label>
																<b-form-input id="Nombre" v-model="objCliente.Nombre" type="text" placeholder="Ingrese Nombre" ></b-form-input>
																<CValidation v-if="this.errorvalidacion.Nombre" :Mensaje="'*' + errorvalidacion.Nombre[0]"/>
                                                            </div>

															<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            	<label for="ApellidoPat">Apellido Paterno</label>
																<b-form-input id="ApellidoPat" v-model="objCliente.ApellidoPat" type="text" placeholder="Ingrese Apellido Paterno" ></b-form-input>
																<CValidation v-if="this.errorvalidacion.ApellidoPat" :Mensaje="'*' + errorvalidacion.ApellidoPat[0]"/>
                                                            </div>

															<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                            	<label for="ApellidoMat">Apellido Materno</label>
																<b-form-input id="ApellidoMat" v-model="objCliente.ApellidoMat" type="text" placeholder="Ingrese Apellido Materno" ></b-form-input>
																<CValidation v-if="this.errorvalidacion.ApellidoMat" :Mensaje="'*' + errorvalidacion.ApellidoMat[0]"/>
                                                            </div>
														</div>

														<div class="form-group form-row">
															<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
																<label for="FechaNacimiento">Fecha de Nacimiento</label>
																<v-date-picker :masks="masks" :popover="{ visibility: 'focus' }" locale="mx" v-model="objCliente.FechaNacimiento">
																	<template v-slot="{ inputValue, inputEvents }">
																		<input class="form-control cal" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
																	</template>
																</v-date-picker>
																<CValidation v-if="this.errorvalidacion.FechaNacimiento" :Mensaje="'*' + errorvalidacion.FechaNacimiento[0]"/>
															</div>

															<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
																<label for="Telefono">Teléfono</label>
																<input id="Telefono" v-model="objCliente.Telefono" type="text" placeholder="Ingrese Teléfono" @input="$onlyNums($event,objCliente,'Telefono');" class="form-control" maxlength="15"/>
																<CValidation v-if="this.errorvalidacion.Telefono" :Mensaje="'*' + errorvalidacion.Telefono[0]"/>
                                                            </div>

															<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
																<label for="Correo">Correo</label>
																<b-form-input id="Correo" v-model="objCliente.Correo" type="email" placeholder="Ingrese Correo " ></b-form-input>
																<CValidation v-if="this.errorvalidacion.Correo" :Mensaje="'*' + errorvalidacion.Correo[0]"/>
                                                            </div>
                                                        </div>
                                                    </div>
													<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
													</div>
                                                </div>
                                            </fieldset>

											<fieldset class="mt-2">
                                                <legend class="col-form-label">Negocio</legend>
												<div class="form-group form-row">
													<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
														<label for="DescripcionEmpresa">Descripción Empresa</label>
														<b-form-textarea
															id="DescripcionEmpresa"
															v-model="objCliente.DescripcionEmpresa"
															placeholder="Ingrese Descripción Empresa"
														></b-form-textarea>
														<CValidation v-if="this.errorvalidacion.DescripcionEmpresa" :Mensaje="'*' + errorvalidacion.DescripcionEmpresa[0]"/>
													</div>
												</div>
                                            </fieldset>

											<fieldset class="mt-2">
                                                <legend class="col-form-label">Domicilio</legend>
												<div class="form-group form-row">
													<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
														<label for="Calle">Calle</label>
														<b-form-input
															id="Calle"
															v-model="objCliente.Calle"
															type="text"
															placeholder="Ingrese Calle"
														></b-form-input>
														<CValidation v-if="this.errorvalidacion.Calle" :Mensaje="'*' + errorvalidacion.Calle[0]"/>
													</div>

													<div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
														<label for="NoExt">Número Exterior</label>
														<b-form-input id="NoExt" v-model="objCliente.NoExt" type="text" placeholder="Ingrese Número Exterior" ></b-form-input>
														<CValidation v-if="this.errorvalidacion.NoExt" :Mensaje="'*' + errorvalidacion.NoExt[0]"/>
													</div>

													<div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
														<label for="NoInt">Número Interior</label>
														<b-form-input id="NoInt" v-model="objCliente.NoInt" type="text" placeholder="Ingrese Número Interior"
														></b-form-input>
														<CValidation v-if="this.errorvalidacion.NoInt" :Mensaje="'*' + errorvalidacion.NoInt[0]"/>
													</div>

													<div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
														<label for="Cp">Código Postal</label>
														<input id="Cp" v-model="objCliente.Cp" type="text" placeholder="Ingrese Código Postal" @input="$onlyNums($event,objCliente,'Cp');" class="form-control" maxlength="10" />
														<CValidation v-if="this.errorvalidacion.Cp" :Mensaje="'*' + errorvalidacion.Cp[0]"/>
													</div>
												</div>

												<div class="form-group form-row">
													<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="Colonia">Colonia</label>
														<b-form-input
															id="Colonia"
															v-model="objCliente.Colonia"
															type="text"
															placeholder="Ingrese Colonia"
														></b-form-input>
														<CValidation v-if="this.errorvalidacion.Colonia" :Mensaje="'*' + errorvalidacion.Colonia[0]"/>
													</div>

													<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="Estado">Estado</label>
														<select id="Estado" class="form-control form-select" v-model="objCliente.IdEstado" @change="ListaMunicipios()">
															<option :value="0">--Seleccionar--</option>
															<option v-for="(item, index) in ListaEstadosArray" :key="index" :value="item.IdEstado">
																{{ item.Nombre }}
															</option>
														</select>
														<CValidation v-if="this.errorvalidacion.IdEstado" :Mensaje="'*' + errorvalidacion.IdEstado[0]"/>
													</div>

													<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="Municipio">Municipio</label>
														<select v-model="objCliente.IdMunicipio" id="Municipio" class="form-control form-select">
															<option :value="0">--Seleccionar--</option>
															<option v-for="(item, index) in ListaMunicipiosArray" :key="index" :value="item.IdMunicipio" >
																{{ item.Nombre }}
															</option>
														</select>
														<CValidation v-if="this.errorvalidacion.IdMunicipio" :Mensaje="'*' + errorvalidacion.IdMunicipio[0]"/>
													</div>
												</div>

												<div class="form-group form-row">
													<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
														<label for="Referencias">Referencias</label>
														<b-form-textarea id="Referencias" v-model="objCliente.Referencias" placeholder="Ingrese Referencias"> </b-form-textarea>
														<CValidation v-if="this.errorvalidacion.Referencias" :Mensaje="'*' + errorvalidacion.Referencias[0]"/>
													</div>
												</div>
                                            </fieldset>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--Fin Parte uno-->

							<!--Parte dos-->
							<div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">

											<fieldset>
                                                <legend class="col-form-label">Préstamo</legend>
												<div class="form-row mt-3">
													<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
														<div class=" form-group form-row">
															<div class="col-12 col-sm-12 col-md-4 col-lg-4col-xl-4">
																<label for="IdSucursal">Sucursal</label>
																<select id="IdSucursal" v-model="objPrestamo.IdSucursal" class="form-control form-select" @change="ListaRutas()" >
																	<option :value="0">--Seleccionar--</option>
																	<option v-for="(item, index) in ListaSucursalesArray" :key="index" :value="item.IdSucursal" >
																		{{ item.Nombre }}
																	</option>
																</select>
																<label>
																	<CValidation v-if="this.errorvalidacion.IdSucursal" :Mensaje="'*' + errorvalidacion.IdSucursal[0]"></CValidation>
																</label>
															</div>
															<div class="col-12 col-sm-12 col-md-4 col-lg-4col-xl-4">
																<label for="IdRuta">Ruta</label>
																<select id="IdRuta" v-model="objPrestamo.IdRuta" class="form-control form-select" @change="ListaRutasEmpleados()" >
																	<option :value="0">--Seleccionar--</option>
																	<option v-for="(item, index) in ListaRutasArray" :key="index" :value="item.IdRuta" >
																		{{ item.NombreRuta }}
																	</option>
																</select>
																<label>
																	<CValidation v-if="this.errorvalidacion.IdRuta" :Mensaje="'*' + errorvalidacion.IdRuta[0]"></CValidation>
																</label>
															</div>
															<div class="col-12 col-sm-12 col-md-4 col-lg-4col-xl-4">
																<label for="IdEmpleado">Empleado</label>
																<select id="IdEmpleado" v-model="objPrestamo.IdCobratario" class="form-control form-select">
																	<option :value="0">--Seleccionar--</option>
																	<option v-for="(item, index) in ListaRutasEmpleadosArray" :key="index" :value="item.IdUsuario" >
																		{{ item.NombreCompleto }}
																	</option>
																</select>
																<label>
																	<CValidation v-if="this.errorvalidacion.IdCobratario" :Mensaje="'*' + errorvalidacion.IdCobratario[0]"></CValidation>
																</label>
															</div>
															<div style="display:none" class="col-12 border-top my-3"></div>
															<div style="display:none" class="col-12 col-sm-12 col-md-4 col-lg-4col-xl-4">
																<label for="FechaAutorizacion">Fecha Autorización</label>
																<v-date-picker
																	:masks="masks"
																	:popover="{ visibility: 'focus' }"
																	locale="es"
																	v-model="objPrestamo.FechaAutorizacion"
																>
																	<template v-slot="{ inputValue, inputEvents }">
																		<input class="form-control cal" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
																	</template>
																</v-date-picker>
																<label>
																	<CValidation v-if="this.errorvalidacion.FechaAutorizacion" :Mensaje="'*' + errorvalidacion.FechaAutorizacion[0]"></CValidation>
																</label>
															</div>
															<div style="display:none" class="col-12 col-sm-12 col-md-4 col-lg-4col-xl-4">
																<label for="FechaEntrega">Fecha Entrega</label>
																<v-date-picker :masks="masks" :popover="{ visibility: 'focus' }" locale="es" v-model="objPrestamo.FechaEntrega" >
																	<template v-slot="{ inputValue, inputEvents }">
																		<input class="form-control cal" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
																	</template>
																</v-date-picker>
																<label>
																	<CValidation v-if="this.errorvalidacion.FechaEntrega" :Mensaje="'*' + errorvalidacion.FechaEntrega[0]"></CValidation>
																</label>
															</div>
															<div style="display:none" class="col-12 col-sm-12 col-md-4 col-lg-4col-xl-4">
																<label for="FechaLiquidacion">Fecha Liquidación</label>
																<v-date-picker :masks="masks" :popover="{ visibility: 'focus' }" locale="es" v-model="objPrestamo.FechaLiquidacion" >
																	<template v-slot="{ inputValue, inputEvents }">
																		<input class="form-control cal" placeholder="0000-00-00" :value="inputValue" v-on="inputEvents" readonly/>
																	</template>
																</v-date-picker>
																<label>
																	<CValidation v-if="this.errorvalidacion.FechaLiquidacion" :Mensaje="'*' + errorvalidacion.FechaLiquidacion[0]"></CValidation>
																</label>
															</div>

															<div class="col-12 border-top my-3"></div>

															<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
																<label for="MontoSolicitud">Monto Solicitud</label>
																<select id="MontoSolicitud" v-model="objPrestamo.MontoSolicitud" name="MontoSolicitud" class="form-control form-select">
																	<option value="0">--Seleccionar--</option>
																	<option v-for="(item,index) in ListaMontosPrestamosArray" :key="index" :value="item.Monto">{{item.Monto}}</option>
																</select>
																<label>
																	<CValidation v-if="this.errorvalidacion.MontoSolicitud" :Mensaje="'*' + errorvalidacion.MontoSolicitud[0]"></CValidation>
																</label>
															</div>

															<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
																<label for="Observaciones">Observaciones</label>
																<b-form-textarea id="Observaciones" v-model="objPrestamo.Observaciones" placeholder="Ingrese Observaciones"> </b-form-textarea>
															</div>
														</div>
													</div>
												</div>
                                            </fieldset>

                                        </div>
                                    </div>
                                </div>
                            </div>
							<!--Fin Parte dos-->

							<!--Parte tres-->
                            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
											<fieldset>
												<legend class="col-form-label">INE (Anverso y Reverso)</legend>
												<div class="row">
													<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
														<button type="button" @click="AgregarItemEvidencia('INE')" v-if="ListaINEArray.length<2" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i></button>
													</div>
												</div>

												<div class="form-group form-row">
													<div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2"
													v-for="(item,index) in ListaINEArray" :key="index">
														<div class="box-imagen" v-if="item.Evidencia==''">
															<div class="avatar-upload image-button">
																<div class="avatar-preview">
																	<div :id="'imagePreviewIne_'+index" :style="'background-image: url('+item.ImagenTmp+');'" :src="(item.ImagenTmp!='')?item.ImagenTmp:''"></div>
																</div>

																<div class="icons-button">
																	<input type="file" :id="'fileIne'+index" name="file1[]" ref="file1" @change="$uploadImagePreviewArray($event,ValidElement,item, Array('Evidencia','ImagenTmp','RutaEvid'))" accept=".png, .jpg, .jpeg">
																	<label :for="'fileIne'+index"  class="mr-1"></label>

																	<button type="button" @click="EliminarItemEvidencia(item,index);">
																		<i class="fas fa-trash"></i>
																	</button>
																</div>
															</div>
														</div>

														<div v-else class="box-imagen">
															<div class="avatar-upload image-button">
																<div class="avatar-preview">
																	<div :id="'imagePreviewIne_'+index" :style="'background-image: url('+RutaEvid+item.Evidencia+');'" :src="RutaEvid+item.Evidencia"></div>
																</div>

																<div class="icons-button">
																	<button type="button" class="mr-1" @click="VerPreviewImagen(item)">
																		<i class="fas fa-search"></i>
																	</button>

																	<button type="button" @click="EliminarItemEvidencia(item,index);">
																		<i class="fas fa-trash"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </fieldset>

											<fieldset class="mt-2">
												<legend class="col-form-label">Comprobante de Domicilio</legend>
												<div class="row">
													<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right mt-1">
														<button type="button" @click="AgregarItemEvidencia('DOM')" v-if="ListaDomicilioArray.length<2" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i></button>
													</div>
												</div>

												<div class="form-group form-row">
													<div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2"
													v-for="(item2,index2) in ListaDomicilioArray" :key="index2">
														<div class="box-imagen" v-if="item2.Evidencia==''">
															<div class="avatar-upload image-button">
																<div class="avatar-preview">
																	<div :id="'imagePreviewDom_'+index2" :style="'background-image: url('+item2.ImagenTmp+');'" :src="(item2.ImagenTmp!='')?item2.ImagenTmp:''"></div>
																</div>

																<div class="icons-button">
																	<input type="file" :id="'fileDom'+index2" name="file2[]" ref="file2" @change="$uploadImagePreviewArray($event,ValidElement,item2, Array('Evidencia','ImagenTmp','RutaEvid'))" accept=".png, .jpg, .jpeg">
																	<label :for="'fileDom'+index2"  class="mr-1"></label>

																	<button type="button" @click="EliminarItemEvidencia(item2,index2);">
																		<i class="fas fa-trash"></i>
																	</button>
																</div>
															</div>
														</div>

														<div v-else class="box-imagen">
															<div class="avatar-upload image-button">
																<div class="avatar-preview">
																	<div :id="'imagePreviewDom_'+index2" :style="'background-image: url('+RutaEvid+item2.Evidencia+');'" :src="RutaEvid+item2.Evidencia"></div>
																</div>

																<div class="icons-button">
																	<button type="button" class="mr-1" @click="VerPreviewImagen(item2)">
																		<i class="fas fa-search"></i>
																	</button>

																	<button type="button" @click="EliminarItemEvidencia(item2,index2);">
																		<i class="fas fa-trash"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </fieldset>

											<fieldset class="mt-2">
												<legend class="col-form-label">Estalecimiento</legend>
												<div class="row">
													<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right mt-1">
														<button type="button" @click="AgregarItemEvidencia('EMP')" v-if="ListaEmpresaArray.length<5" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i></button>
													</div>
												</div>

												<div class="form-group form-row">
													<div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2"
													v-for="(item3,index3) in ListaEmpresaArray" :key="index3">
														<div class="box-imagen" v-if="item3.Evidencia==''">
															<div class="avatar-upload image-button">
																<div class="avatar-preview">
																	<div :id="'imagePreviewEmp_'+index3" :style="'background-image: url('+item3.ImagenTmp+');'" :src="(item3.ImagenTmp!='')?item3.ImagenTmp:''"></div>
																</div>

																<div class="icons-button">
																	<input type="file" :id="'fileEmp'+index3" name="file3[]" ref="file3" @change="$uploadImagePreviewArray($event,ValidElement,item3, Array('Evidencia','ImagenTmp','RutaEvid'))" accept=".png, .jpg, .jpeg">
																	<label :for="'fileEmp'+index3"  class="mr-1"></label>

																	<button type="button" @click="EliminarItemEvidencia(item3,index3);">
																		<i class="fas fa-trash"></i>
																	</button>
																</div>
															</div>
														</div>

														<div v-else class="box-imagen">
															<div class="avatar-upload image-button">
																<div class="avatar-preview">
																	<div :id="'imagePreviewEmp_'+index3" :style="'background-image: url('+RutaEvid+item3.Evidencia+');'" :src="RutaEvid+item3.Evidencia"></div>
																</div>

																<div class="icons-button">
																	<button type="button" class="mr-1" @click="VerPreviewImagen(item3)">
																		<i class="fas fa-search"></i>
																	</button>

																	<button type="button" @click="EliminarItemEvidencia(item3,index3);">
																		<i class="fas fa-trash"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>
												</div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Fin Parte dos-->
							<CBtnSave :poBtnSave="oBtnSave" class="mt-3"/>
                        </div>
				</template>
			</CLoader>
		</template>
	</CList>
</template>

<!-- </CLoader> -->

<script>
import $$ from "jquery";
// El props Id es cuando no es modal y se mando con props
// El export de btnsave es por si no se usa el modal
import CBtnSave from "@/components/CBtnSave";
import Configs from '@/helpers/VarConfig.js';
const EmitEjecuta = "seccionCliente";
import moment from 'moment';

export default {
	name: "DetallesCliente",
	props: ["Id"],
	components: { },
	data() {
		return {
			RutaFile: 	      		    "",
			RutaEvid: 				    '',
			MontoSeleccionado:			0,
			Img: 						null,
			ValidElement: 				Configs.validImage5m,
			IneArrayRows: 				[],
			LocalArrayRows: 			[],
			errorvalidacion: 			[],
			ListaRutasArray: 			[],
			ListaEstadosArray: 			[],
			DomilicioArrayRows: 		[],
			ListaMunicipiosArray: 		[],
			ListaSucursalesArray: 		[],
			ListaRutasEmpleadosArray: 	[],
			ListaMontosPrestamosArray:	[],
			ListaINEArray: 				[],
			ListaDomicilioArray: 		[],
			ListaEmpresaArray: 			[],
			ConfigList: {
				ShowTitleFirst: false,
				Title: "Detalles Prospecto",
				ShowLoader: true,
				IsModal: false,
				BtnNewShow: false,
				BtnReturnShow: true,
				TypeBody: "Form",
				ShowFiltros: false,
				ShowFiltrosInline: true,
				ShowTitleInline: false,
				ShowPaginador: false,
				EmitSeccion: EmitEjecuta,
			},
			oBtnSave: {
				toast:         0,
				IsModal:       false,
				ShowBtnSave:   true,
				ShowBtnCancel: false,
				DisableBtn:    false,
				EmitSeccion:   EmitEjecuta,
			},
			ConfigLoad: {
				ShowLoader: true,
				ClassLoad: false,
			},
			objCliente: {
				IdCliente: 0,
				IdEstado: 0,
				IdMunicipio: 0,
				Nombre: "",
				ApellidoPat: "",
				ApellidoMat: "",
				FechaNacimiento:"",
				Correo: "",
				Telefono: '',
				DescripcionEmpresa: "",
				Calle: "",
				NoInt: "",
				NoExt: "",
				Colonia: "",
				Cp: '',
				Referencias: "",
				Prospecto: "",
				Estatus: "",
				Latitud: 0,
				Longitud: 0,
				Imagen: "",
			},
			objPrestamo: {
				IdPrestamo: 0,
				IdCliente: 0,
				IdRuta: 0,
				Creador: 0,
				IdCobratario: 0,
				IdAutorizo: 1,
				IdValidador: 0,
				IdSucursal: 0,
				FechaAutorizacion: "",
				FechaEntrega: "",
				FechaLiquidacion: "",
				MontoSolicitud: 0,
				MontoAutorizado: 0,
				AdeudoTotal: 0,
				Estatus: "Pendiente",
				Observaciones: "",
				MontoRechazo: 0,
			},
			objClienteEvidencia: {
				IdClienteEvidencia: 0,
				IdCliente: 1,
				Evidencia: "",
				Observaciones: ""
			},
			masks: {
				input: 'YYYY-MM-DD',
			},
		};
	},
	methods: {
		async Guardar() {
			this.errorvalidacion 	 = [];

			let Fecha1 = '';
			if(this.objCliente.FechaNacimiento!=''){
                Fecha1 = moment(this.objCliente.FechaNacimiento).format('YYYY-MM-DD');
            }

			// CLIENTE
			let formData = new FormData();
			formData.set("Origen", 	 	        "web");
			formData.set("IdCliente", 			this.objCliente.IdCliente);
			formData.set("Nombre", 	 			this.objCliente.Nombre);
			formData.set("ApellidoPat", 		this.objCliente.ApellidoPat);
			formData.set("ApellidoMat", 		this.objCliente.ApellidoMat);
			formData.set("Telefono", 	 		this.objCliente.Telefono);
			formData.set("FechaNacimiento", 	Fecha1);
			formData.set("Correo", 				this.objCliente.Correo);
			formData.set("DescripcionEmpresa", 	this.objCliente.DescripcionEmpresa);
			formData.set("IdEstado", 			this.objCliente.IdEstado);
			formData.set("IdMunicipio", 		this.objCliente.IdMunicipio);
			formData.set("Calle", 				this.objCliente.Calle);
			formData.set("NoInt", 				this.objCliente.NoInt);
			formData.set("NoExt", 				this.objCliente.NoExt);
			formData.set("Colonia", 			this.objCliente.Colonia);
			formData.set("Cp", 					this.objCliente.Cp);
			formData.set("Referencias", 		this.objCliente.Referencias);
			formData.set("Prospecto", 			this.objCliente.Prospecto);
			formData.set("Estatus", 			this.objCliente.Estatus);
			formData.set("Latitud", 			this.objCliente.Latitud);
			formData.set("Longitud", 			this.objCliente.Longitud);
			formData.set("ImagenPrevious", 		this.objCliente.Imagen);

			let Imagen = this.$refs.file.files[0];
			formData.append("Imagen", Imagen);

			// SOLICITUD
			formData.set('IdPrestamo', 			this.objPrestamo.IdPrestamo);
			formData.set('IdRuta', 				this.objPrestamo.IdRuta);
			formData.set('Creador', 			this.objPrestamo.Creador);
			formData.set('IdCobratario', 		this.objPrestamo.IdCobratario);
			formData.set('IdSucursal', 			this.objPrestamo.IdSucursal);
			formData.set('MontoSolicitud', 		this.objPrestamo.MontoSolicitud);
			formData.set('Estatus', 			this.objPrestamo.Estatus);
			formData.set("EstatusCancelacion", 	"Pendiente");
			formData.set('Observaciones', 		this.objPrestamo.Observaciones);

			// EVIDENCIAS
			let files1 = document.getElementsByName('file1[]');
			let files2 = document.getElementsByName('file2[]');
			let files3 = document.getElementsByName('file3[]');

			formData.append('ListaINEArray', 		JSON.stringify(this.ListaINEArray));
			formData.append('ListaDomicilioArray', 	JSON.stringify(this.ListaDomicilioArray));
			formData.append('ListaEmpresaArray', 	JSON.stringify(this.ListaEmpresaArray));

			if(this.ListaINEArray.length < 2){
				this.$toast.warning('Debe agregar ambas fotografías del INE');
				return false;
			}

			if(this.ListaDomicilioArray.length == 0){
				this.$toast.warning('Debe agregar al menos una fotografía del domicilio');
				return false;
			}

			if(this.ListaEmpresaArray.length == 0){
				this.$toast.warning('Debe agregar al menos una fotografía del local');
				return false;
			}

			for(var i=0;i<this.ListaINEArray.length;i++) {

				let file = (files1[i])?files1[i].files[0]:'';
				// PRIMERO VALIDAMOS QUE LA PRIMERA VEZ EXISTA IMAGEN COMO CAMPO OBLIGATORIO
				if(this.ListaINEArray[i].Evidencia =='' && file===undefined && this.ListaINEArray[i].IdClienteEvidencia == 0) {
					this.$toast.warning('El INE debe contener la fotografía del anverso y reverso');
					return false;
				} else {
					if(file!=undefined){
						formData.append('FileINE[]',file);
					}
				}
			}

			for(var i=0;i<this.ListaDomicilioArray.length;i++) {

				let file = (files2[i])?files2[i].files[0]:'';
				// PRIMERO VALIDAMOS QUE LA PRIMERA VEZ EXISTA IMAGEN COMO CAMPO OBLIGATORIO
				if(this.ListaDomicilioArray[i].Evidencia =='' && file===undefined && this.ListaDomicilioArray[i].IdClienteEvidencia == 0) {
					this.$toast.warning('El Domicilio debe contener ambas fotografías');
					return false;
				} else {
					if(file!=undefined){
						formData.append('FileDom[]',file);
					}
				}
			}

			for(var i=0;i<this.ListaEmpresaArray.length;i++) {
				let file = (files3[i])?files3[i].files[0]:'';
				// PRIMERO VALIDAMOS QUE LA PRIMERA VEZ EXISTA IMAGEN COMO CAMPO OBLIGATORIO
				if(this.ListaEmpresaArray[i].Evidencia =='' && file===undefined && this.ListaEmpresaArray[i].IdClienteEvidencia == 0) {
					this.$toast.warning('La fotografía '+(i+1)+' de la empresa debe ser agregada');
					return false;
				} else {
					if(file!=undefined){
						formData.append('FileEmp[]',file);
					}
				}
			}
			
			this.oBtnSave.DisableBtn = true;

			await this.$http.post("clientes",formData,{
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}).then((res) => {
				this.EjecutaConExito(res);
				this.objCliente = res.data.data.cliente;
				this.ListaSucursales(this.objCliente.IdEstado, this.objCliente.IdMunicipio);
			})
			.catch((err) => {
				this.EjecutaConError(err);
			});
		},
		EjecutaConExito(res) {
			this.oBtnSave.DisableBtn = false;
			this.bus.$emit('RunAlerts_'+this.ConfigList.EmitSeccion,1);
			this.Regresar();
		},
		EjecutaConError(err) {
			this.oBtnSave.DisableBtn = false;

			if (err.response.data.errors) {
				this.errorvalidacion = err.response.data.errors;
				this.oBtnSave.toast = 2;
			} else {
				this.$toast.error(err.response.data.message);
			}
		},
		Recuperar()
		{
			this.$http.get(
				"clientes/"+this.objCliente.IdCliente)
			.then((res) => {
				this.objCliente = res.data.data.Cliente;
				this.RutaFile = res.data.RutaFile;
				this.ListaMunicipios(this.objCliente.IdEstado);

				this.objPrestamo = res.data.data.Prestamo;
				this.ListaRutas();

				this.RutaEvid = res.data.RutaEvid;
				this.ListaINEArray = res.data.data.EvidenciaIne;
				this.ListaDomicilioArray = res.data.data.EvidenciaDom;
				this.ListaEmpresaArray = res.data.data.EvidenciaEmp;

				let Fecha1 = this.objCliente.FechaNacimiento.replace(/-/g,'\/');
				this.objCliente.FechaNacimiento = Fecha1;
			})
			.finally(() => {
				this.ConfigLoad.ShowLoader = false;
			});
		},
		Limpiar()
		{
			this.objCliente = {
				IdCliente: 0,
				Nombre: "",
				ApellidoPat: "",
				ApellidoMat: "",
				FechaNacimiento:"",
				Correo: "",
				Telefono: '',
				DescripcionEmpresa: "",
				Calle: "",
				NoInt: "",
				NoExt: "",
				Colonia: "",
				Cp: '',
				Referencias: "",
				IdEstado: 0,
				IdMunicipio: 0,
				Latitud: 0,
				Longitud: 0,
				Imagen: "",
			};

			this.objPrestamo = {
				IdPrestamo: 0,
				IdCliente: 0,
				IdRuta: 0,
				IdCobratario:0,
				Creador: 0,
				IdAutorizo: 1,
				IdValidador: 0,
				IdSucursal: 0,
				FechaAutorizacion: "",
				FechaEntrega: "",
				FechaLiquidacion: "",
				MontoSolicitud: 0,
				MontoAutorizado: 0,
				AdeudoTotal: 0,
				Estatus: "Pendiente",
				Observaciones: "",
				MontoRechazo: 0,
			};
		},
		Regresar() {
            this.$router.push({name:'prospectos',params:{}});
		},
		async ListaMunicipios(id)
		{
			if (typeof id != "undefined") {
				this.objCliente.IdEstado = id;
			}
			await this.$http
				.get("municipios", {
					params: {
						IdEstado: this.objCliente.IdEstado,
					},
				})
				.then((res) => {
					if (typeof id == "undefined") {
						this.ListaMunicipiosArray = []
						this.objCliente.IdMunicipio = 0;
					}
					this.ListaMunicipiosArray = res.data.data;
				});
		},
		async ListaSucursales()
		{
			await this.$http.get(
				"sucursales", {
				params: {
					simple:1
				},
			})
			.then((res) => {
				this.ListaSucursalesArray = res.data.data;
			});
		},
		ListaRutas()
		{
			if(this.objPrestamo.IdSucursal>0)
			{
				this.$http.get(
					"rutas", {
					params: {
						IdSucursal: this.objPrestamo.IdSucursal,
						simple:1
					},
				})
				.then((res) => {
					this.ListaRutasArray = res.data.data;
					this.ListaRutasEmpleados();
				});
			}
			else
			{
				this.ListaRutasArray = [];
				this.ListaRutasEmpleadosArray = [];
				this.objPrestamo.IdRuta = 0;
				this.objPrestamo.Creador = 0;
			}
		},
		ListaRutasEmpleados()
		{
			if(this.objPrestamo.IdRuta>0)
			{
				this.$http.get(
					"rutasxusuariosinner", {
					params: {
						IdRuta: this.objPrestamo.IdRuta
					},
				})
				.then((res) => {
					this.ListaRutasEmpleadosArray = res.data.data;
				});
			}
			else
			{
				this.ListaRutasEmpleadosArray = [];
				this.objPrestamo.Creador = 0;
			}
		},
		ListaMontosPrestamo()
		{
			this.$http.get(
					"prestamosmontos", {
					params: {
						simple: 1
					},
				})
				.then((res) => {
					this.ListaMontosPrestamosArray = res.data.data;
				});
		},
		AgregarItemEvidencia(TipoEvidencia)
		{
			let newObj = { IdClienteEvidencia: 0, IdCliente: 0, TipoEvidencia: '', Evidencia: "", Observaciones: "",ImagenTmp: '' };
			if(TipoEvidencia == 'INE')
			{
				if(this.ListaINEArray.length<2)
				{
					newObj.TipoEvidencia = 'Ine';
					this.ListaINEArray.push(newObj);
				}
			}
			else if(TipoEvidencia == 'DOM')
			{
				if(this.ListaDomicilioArray.length<=2)
				{
					newObj.TipoEvidencia = 'Domicilio';
					this.ListaDomicilioArray.push(newObj);
				}
			}
			else if(TipoEvidencia == 'EMP')
			{
				if(this.ListaEmpresaArray.length<5)
				{
					newObj.TipoEvidencia = 'Empresa';
					this.ListaEmpresaArray.push(newObj);
				}
			}
		},
		EliminarItemEvidencia(item,index)
		{
			let Id = item.IdClienteEvidencia;
			let Tipo = item.TipoEvidencia;

			this.$swal(Configs.configEliminar)
			.then((result) =>
			{
				if (result.value)
				{
					if(Id>0)
					{
						this.$http.delete(
						"clientesevidencias/"+Id)
						.then((res) => {
							this.$swal(Configs.configEliminarConfirm);
							this.ConfigDeleteItem(Tipo,index);
						})
						.catch((err) => {
							this.$toast.error(err.response.data.message);
						});
					}
					else
					{
						this.ConfigDeleteItem(Tipo,index);
					}
				}
			});
		},
		ConfigDeleteItem(Tipo,index)
		{
			if(Tipo == 'Ine'){
				this.ListaINEArray.splice(index,1);
			}
			else if(Tipo == 'Domicilio'){
				this.ListaDomicilioArray.splice(index,1);
			}
			else if(Tipo == 'Empresa'){
				this.ListaEmpresaArray.splice(index,1);
			}
		},

		VerPreviewImagen(item) {
			window.open(this.RutaEvid+item.Evidencia,'Nueva Ventana',"width=600,height=800");
		},
	},
	created() {
		this.bus.$off("Save_"+EmitEjecuta);
		this.bus.$off("EmitReturn");

		this.Limpiar();

		this.ListaEstadosArray = JSON.parse(sessionStorage.getItem("ListaEstadosArray"));

	},
	mounted() {
		this.ListaSucursales();
		this.ListaMontosPrestamo();
		this.oBtnSave.DisableBtn = false;  


		if (this.Id > 0) {
			this.objCliente.IdCliente = this.Id;
			this.Recuperar();
		} else {
			this.ConfigLoad.ShowLoader = false;
		}

		this.bus.$on("Save_"+EmitEjecuta, () => {
			this.Guardar();
		});

		this.bus.$on("EmitReturn", () => {
			this.Regresar();
		});
	},
};
</script>
