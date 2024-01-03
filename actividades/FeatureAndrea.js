function PullRequest() {
    return {
        actividad: {
            FechaIncio: '24-03-2022',
            FechaFin: '24-03-2022',
            Descripcion: '',
            Carpeta: [
                {
                    Archivo: 'clientesActivos',
                    comentario: 'Carpeta nueva de vistas para clientes Activos'
                },
            ],
            Modelos: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Controlador: [
                {
                    Archivo: 'ClientesController',
                    comentario: 'Get de clientes - prestamos activos '
                },
            ],
            Route: [
                {
                    Archivo: 'clientesActivos',
                    comentario: ''
                },
            ],
            Vista: [
                {
                    Archivo: 'clientes',
                    comentario: 'Se borró el archivo de la vista principal'
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
        },
        actividad: {
            Descripcion: 'Préstamos - Clientes',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: 'Cliente',
                    comentario: 'Se agregaron las columnas de MotivoRechazo y FechaRechazo',
                    Archivo: 'Prestamo',
                    comentario: 'Se agregaron las columnas de MotivoRechazo y FechaRechazo'
                },
            ],
            Controlador: [
                {
                    Archivo: 'ClientesController',
                    comentario: 'Get de clientes - prestamos activos ',
                    Archivo: 'PrestamosController',
                    comentario: 'Get - Update  de prestamos desde vista de clientes '
                },
            ],
            Route: [
                {
                    Archivo: '',
                    comentario: 'rutas nuevas en prestamos'
                },
            ],
            Vista: [
                {
                    Archivo: 'ClientesActivos',
                    comentario: 'Modal de Prestamo'
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: '',
                    comentario: 'Columnas nuevas en migración de clientes y Prestamos'
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Falta terminar el get de la observacion de rechazo desde préstamos y el re activar del préstamo. '
                },
            ],
        },
        actividad: {
            Descripcion: 'Préstamos - Clientes',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: '',
                    comentario: '',
                    Archivo: 'Prestamo',
                    comentario: ''
                },
            ],
            Controlador: [
                {
                    Archivo: 'ClientesController',
                    comentario: 'Update dependiendo del estatus del prestamo - prospecto -- Cambio en el get de asigandos -- ReAsignar(revisar por Bitácora)',
                },
            ],
            Route: [
                {
                    Archivo: '',
                    comentario: 'rutas nuevas en prestamos'
                },
            ],
            Vista: [
                {
                    Archivo: 'ClientesActivos',
                    comentario: 'Modal de Prestamo'
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: 'Prestamos',
                    comentario: 'Se cambio el campo "MotivoRechazo" a PrestamoMotRechazo, default es null'
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Falta la Bitácora'
                },
            ],
        },
        actividad: {
            Descripcion: 'Préstamos - Clientes',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: '',
                    comentario: '',
                    Archivo: '',
                    comentario: ''
                },
            ],
            Controlador: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Route: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Vista: [
                {
                    Archivo: 'Detail',
                    comentario: 'Cambio de la vista de las fotos'
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: 'Prestamos',
                    comentario: 'Se cambio el campo "MotivoRechazo" a PrestamoMotRechazo, default es null'
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Falta la Bitácora'
                },
            ],
        },
        actividad: {
            Descripcion: 'Préstamos - Clientes',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: '',
                    comentario: '',
                    Archivo: '',
                    comentario: ''
                },
            ],
            Controlador: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Route: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Vista: [
                {
                    Archivo: 'ProspectoPrestamo',
                    comentario: 'Vista donde están todos los métodos de prospecto - prestamo'
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: 'bitacora',
                    comentario: 'Se creo la migración de bitácora de clientes. '
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Falta la Bitácora'
                },
            ],
        },
        actividad: {
            Descripcion: 'Cobranza',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: 'ClientesxCobratario',
                    comentario: '',
                    Archivo: '',
                    comentario: 'nuevo modelo'
                },
            ],
            Controlador: [
                {
                    Archivo: 'ClientesxCobratarioController',
                    comentario: 'nuevo modelo para hacer las apis aparte',
                },
            ],
            Route: [
                {
                    Archivo: 'api',
                    comentario: 'nuevas rutas de para función de "diaSiguiente"'
                },
            ],
            Vista: [
                {
                    Archivo: 'Cobranza',
                    comentario: 'Lista de usuarios con perfiles 2 y 4 con sus respectivos prestamos'
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: 'create_prestamos_table',
                    comentario: 'Se agregaron los campos de MontoDiario, TotalDevolverPrestamo,NumPagos,TotalMultas,TotalGlobal,NumPagosAnual '
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Falta la Bitácora'
                },
            ],
        },
        actividad: {
            Descripcion: 'Prestamos - Prospecto',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: 'ClientexCobratario',
                    comentario: '',
                    Archivo: '',
                    comentario: 'se agregó el TipoAsignacion'
                }, 
                {
                    Archivo: 'Prestamo',
                    comentario: '',
                    Archivo: '',
                    comentario: 'IdEmpleado cambió a Creador'
                }
            ],
            Controlador: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Route: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Vista: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: 'create_prestamos_table',
                    comentario: 'Se agregaron los campos de Origen y IdEmpleado se cambió a Creador'
                },
                {
                    Archivo: 'create_clientesxcobratarios_table',
                    comentario: 'Se agregó el campo de TipoAsignacion'
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Falta la Bitácora'
                },
            ],
        },
        actividad: {
            Descripcion: 'Pagos - API para la App',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: 'PrestamoxCobratario',
                    comentario: '',
                    Archivo: '',
                    comentario: 'Nuevo Modelo'
                }, 
                {
                    Archivo: 'PrestamoPago',
                    comentario: '',
                    Archivo: '',
                    comentario: 'Se agregaron nuevas columnas'
                }
            ],
            Controlador: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Route: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Vista: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: 'create_prestamosxcomentarios_table',
                    comentario: 'Nueva tabla'
                },
                {
                    Archivo: 'create_prestamospagos_table',
                    comentario: 'Se agregaron nuevas columnas'
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Falta la Bitácora'
                },
            ],
        },
        actividad: {
            Descripcion: 'Prestamos - Pagos',
            Carpeta: [
                {
                    Archivo: 'ClientesController',
                    comentario: 'se agregó el PrestamoMotRechazo en el get de clientes rechazados.'
                },
            ],
            Modelos: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Controlador: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Route: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Vista: [
                {
                    Archivo: 'crm\FormActivos.vue',
                    comentario: 'Se cambio la  tablka por el componente Ctablita'
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: '',
                    comentario: 'Nueva '
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: 'Se actualizo el package-lock.json'
                },
            ],
        },
        actividad: {
            Descripcion: 'Limpieza de apis',
            Carpeta: [
                {
                    Archivo: 'ClientesController',
                    comentario: 'Se eliminó la función getRechazadosProspectoPrestamo',
                    comentario2: 'Se eliminó la función RetornarRechazado',
                    comentario3: 'Se eliminó la función RetornarPrestamo',
                },
                {
                    Archivo: 'PrestamosController',
                    comentario: 'Se eliminó la función RejectPrestamo',
                    comentario2: 'Se eliminó la función GetObservacion',
                },
            ],
            Modelos: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Controlador: [
                {
                    Archivo: '',
                    comentario: '',
                },
            ],
            Route: [
                {
                    Archivo: 'api',
                    comentario: 'Se eliminó la api /rejectPrestamo/{id} que estaba en PrestamosController',
                    comentario2: 'Se eliminó la api /prestamosObservacion/{id} que estaba en PrestamosController',
                    comentario4: 'Se eliminó la api /RetornarRechazado/{id} que estaba en ClientesController',
                    comentario5: 'Se eliminó la api /clientesProspectoRechazados que estaba en ClientesController',
                },
            ],
            Vista: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            InstalacionDeDependencias: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            TablasMigraciones: [
                {
                    Archivo: '',
                    comentario: '' 
                },
            ],
            Otros: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
        },
    }
}