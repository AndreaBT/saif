const validImage10m = {
    NameFile: 'Elegir archivo (max 10 MB)',
    Peso: 6,
    MsgPeso: 'Solo se puede subir archivos menores a 10 MB',
    Formatos: /(\.jpg|\.jpeg|\.png|\.gif)$/i,
    MsgFormato: 'Extenciones permitidas .jpeg/.jpg/.png/.gif'
}

const validImage6m = {
    NameFile: 'Elegir archivo (max 6 MB)',
    Peso: 6,
    MsgPeso: 'Solo se puede subir archivos menores a 6 MB',
    Formatos: /(\.jpg|\.jpeg|\.png)$/i,
    MsgFormato: 'Extenciones permitidas .jpeg/.jpg/.png'
}

const validImage5m = {
    NameFile: 'Elegir archivo (max 5 MB)',
    Peso: 5,
    MsgPeso: 'Solo se puede subir archivos menores a 5 MB',
    Formatos: /(\.jpg|\.jpeg|\.png)$/i,
    MsgFormato: 'Extenciones permitidas .jpeg/.jpg/.png'
}

const validImage2m = {
    NameFile: 'Elegir archivo (max 2 MB)',
    Peso: 2,
    MsgPeso: 'Solo se puede subir archivos menores a 2 MB',
    Formatos: /(\.jpg|\.jpeg|\.png)$/i,
    MsgFormato: 'Extenciones permitidas .jpeg/.jpg/.png'
}

const validFile2m = {
    NameFile: 'Elegir archivo (max 2 MB)',
    Peso: 2,
    MsgPeso: 'Solo se puede subir archivos menores a 2 MB',
    Formatos: /(\.pdf|\.doc|\.docx|\.ppt|\.json)$/i,
    MsgFormato: 'Extenciones permitidas .pdf/.doc/.docx/.ppt/.json'
}

const validFile3m = {
    NameFile: 'Elegir archivo (max 3 MB)',
    Peso: 3,
    MsgPeso: 'Solo se puede subir archivos menores a 3 MB',
    Formatos: /(\.pdf|\.doc|\.docx|\.ppt|\.json)$/i,
    MsgFormato: 'Extenciones permitidas .pdf/.doc/.docx/.ppt/.json'
}

const validFile5m = {
    NameFile: 'Elegir archivo (max 5 MB)',
    Peso: 5,
    MsgPeso: 'Solo se puede subir archivos menores a 5 MB',
    Formatos: /(\.pdf|\.doc|\.docx|\.ppt|\.json)$/i,
    MsgFormato: 'Extenciones permitidas .pdf/.doc/.docx/.ppt/.json'
}

const configTolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    // ['blockquote', 'code-block'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'color': [] }, { 'background': [] }],
    // [{ 'font': [] }],
    [{ 'align': [] }],
    ['clean'],
    ['link'] //, 'image', 'video'
];

const configEliminar = {
    title: '<h2 style="font-size:25px; font-weight: 700;">¿Desea eliminar este registro?</h2>', //<h2 style="font-size:25px; font-weight: 700;">¿Desea eliminar esta información?</h2>
    text: 'Esta acción no se podra revertir ',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showCloseButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#0096ED',
    cancelButtonColor: '#b92e27',
}

const configEliminarItem = {
    title: '<h2 style="font-size:25px; font-weight: 700;">Atención</h2>',
    text: '¿Confirma que desea eliminar la fila?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showCloseButton: true,
    confirmButtonColor: '#0096ED',
    cancelButtonColor: '#b92e27',
}

const configEliminarConfirm = {
    title: '<h2 style="font-size:25px; font-weight: 700;">El registro ha sido eliminado.</h2>',
    icon: 'success',
    showConfirmButton: true,
    confirmButtonText: 'Continuar',
    confirmButtonColor: '#0096ED',
    timer: 3000,
    timerProgressBar: true,
}

const configCerrarSession = {
    title: '¿Cerrar Sesión?',
    text: 'Esta a punto de cerrar su sesión, ¿desea cerrarla?',
    icon: 'warning',
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showCloseButton: true,
    showCancelButton: true,
    confirmButtonColor: '#0096ED',
    cancelButtonColor: '#b92e27',
}

const configRestablecerPassword = {
    title: '¡Exito!',
    text: '¡Su contraseña ha sido reestablecida, ahora podra ingresar de nueva cuenta.!',
    icon: 'success',
    showCancelButton: false,
    showCloseButton: false,
    showLoaderOnConfirm: true,
}

const configAutorizarPrestamo = {
    title: '<h2 style="font-size:25px; font-weight: 700;">¿Desea autorizar el prestamo?</h2>',
    text: 'Esta acción no se podra revertir ',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showCloseButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#0096ED',
    cancelButtonColor: '#b92e27',
}

const configGenerarDia = {
    title: '<h2 style="font-size:25px; font-weight: 700;">¿Desea generar dia siguiente?</h2>',
    text: '¡Solo podra generar una vez al dia esta información!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showCloseButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#0096ED',
    cancelButtonColor: '#b92e27',
}

const configReactivarPrestamo = {
    title: '<h2 style="font-size:25px; font-weight: 700;">¿Desea reactivar este prestamo?</h2>',
    text: '¿Está seguro de Reactivar a este Prestamo?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showCloseButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#0096ED',
    cancelButtonColor: '#b92e27',
}

const configReactivarProspecto = {
    title: '<h2 style="font-size:25px; font-weight: 700;">¿Desea reactivar este prospecto?</h2>',
    text: '¿Está seguro de Reactivar a este Prospecto?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Continuar',
    cancelButtonText: 'Cancelar',
    showCloseButton: true,
    showLoaderOnConfirm: true,
    confirmButtonColor: '#0096ED',
    cancelButtonColor: '#b92e27',
}

export default {
    validImage10m,
    validImage6m,
    validImage5m,
    validImage2m,
    validFile2m,
    validFile3m,
    validFile5m,
    configTolbarOptions,
    configEliminar,
    configEliminarItem,
    configEliminarConfirm,
    configCerrarSession,
    configRestablecerPassword,
    configAutorizarPrestamo,
    configGenerarDia,
    configReactivarPrestamo,
    configReactivarProspecto
}
