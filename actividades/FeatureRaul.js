function PullRequest() {
    return {
        actividades:[
            {
                FechaIncio: '08-03-2022',
                FechaFin: '08-03-2022',
                Descripcion: 'Se agrega Seeder de montos de credito, se hace ajuste de menus, eliminado sucursales, estados; se reordena el formulario de sucursales',
                Carpeta: {
                    NA
                },
                Modelos: {
                    NA
                },
                Controlador: [
                    {
                        Archivo:'services/app/Http/Controllers/EmpresasController.php',
                        comentario: 'Se remueve la validacion obligatorio de NoInt'
                    }
                ],
                    Route: {
                    NA
                },
                Vista: [
                    {
                        Archivo: 'admin/src/views/catalogos/montosCredito/List.vue',
                        comentario: 'Se anexa funcion formatNumber porque el plugin general de funciones no esta operando'
                    },
                    {
                        Archivo: 'admin/src/views/modulos/empleados/Form.vue',
                        comentario: 'Se detecta archivo sin aparente uso'
                    },
                    {
                        Archivo: 'admin/src/views/modulos/empleados/SaveEmpledo.vue',
                        comentario: 'Se cambia el combo de estado civil a consumir el archivo de opciones estaticas'
                    },
                ],
                    InstalacionDeDependencias: {
                    NA
                },
                TablasMigraciones: [
                    {
                        Archivo: 'service/database/migration/2022_01_26_180846_create_prestamosmontos_table.php',
                        comentario: 'se agrega valor de decimales quedando con la configuracion 32,2'
                    }
                ],
                    Otros: [
                    {
                        Archivo: 'admin/src/config/ConfigMenu.js',
                        comentario: 'Se elimina menu de estados, sucursales, ya que sucursales esta implicito dentro de empresa y estado se manejará solo desde BD'
                    },
                    {
                        Archivo: 'admin/src/helpers/StaticComboBox.js',
                        comentario: 'Se agrega para tener globalmente los arreglos de las opciones de los combos estaticos del sistema'
                    },
                    {
                        Archivo: 'services/database/seeders/DatabaseSeeder.php',
                        comentario: 'Se agrega el llamado del seeder de montos de prestamo'
                    },
                    {
                        Archivo: 'services/database/seeders/PrestamoMontoSeeder.php',
                        comentario: 'Se crea archivo sembrador de montos de prestamos'
                    },
                ],
            },

            {
                FechaIncio: '14-03-2022',
                FechaFin: '14-03-2022',
                Descripcion: 'se crea api de comando storage-link, se remueve objeto equipo, se modifica Files manager, correccion de form, empresa y sucursales',
                Carpeta: {
                    NA
                },
                Modelos: {
                    NA
                },
                Controlador: [
                    {
                        Archivo:'services/app/Http/Controllers/EquiposController.php',
                        comentario: 'Se remueve el objeto Equipo en respuesta de list'
                    },
                    {
                        Archivo:'services/app/Http/Controllers/UserController.php',
                        comentario: 'Se agrega el Storage_path a la respuesta de listado'
                    },
                    {
                        Archivo:'services/app/Http/Controllers/SystemController.php',
                        comentario: 'Se crea controlador unicamente para la creacion manual de storage-link sin acceso ssh'
                    }
                ],
                Route:[
                    {
                        comentario:"se crea una ruta sin proteccion unicamente para crear el storage-link en servidor sin ssh"
                    }
                ],
                Vista: [
                    {
                        Archivo: 'admin/src/views/catalogos/empresas/List.vue',
                        comentario: 'Se elimina el obejto Sucursales del listado'
                    },
                    {
                        Archivo: 'admin/src/views/modulos/equipos/List.vue',
                        comentario: 'Se elimina uso de objeto Equipo del listado, se corrige nombre de titulo de columna de TipoEquipo a Tipo Equipo'
                    },
                    {
                        Archivo: 'admin/src/views/modulos/sucursales/List.vue',
                        comentario: 'Se elimina el obejto Sucursales del listado, archivo sin utilizar aparentemente.'
                    },
                ],
                InstalacionDeDependencias: [
                ],
                TablasMigraciones: [
                ],
                Otros: [
                    {
                        Archivo: 'services/database/seeders/DatabaseSeeder.php',
                        comentario: 'Se agrega el llamado del seeder de perfiles'
                    },
                    {
                        Archivo: 'services/database/seeders/PerfilSeeder.php',
                        comentario: 'Se crea archivo sembrador de perfiles base, [administrador, validador y gestor]'
                    },
                ],
            },

            {
                FechaIncio: '18-03-2022',
                FechaFin: '21-03-2022',
                Descripcion: 'se genera sistema de bloqueo de acceso apara los usuarios app al panel web en el login, se anexa perfil de supervisor',
                Carpeta: [],
                Modelos: [
                    {
                        Archivo:'services/app/Models/Perfil.php',
                        comentario: 'Se coloca en propiedad hidden que los campos timesatap asi como el de softdeletes sean ocultos a las peticiones al modelo con el motivo de que no son datos de uso para el catalogo.'
                    },
                    {
                        Archivo:'services/app/Models/User.php',
                        comentario: 'se modifica el sistema de validacion del login, para poder verificar de primera instancia si existe el usuario en el sistema ' +
                            'asi como si esta autorizado para ingresar dependiento del lugar desde donde este intentanto ingresa, de modo que un usuario con perfil de app, ' +
                            'solo puede ingresar a la app, (siempre y cuando tambien tenga el permiso UsuarioApp) y no al panel web; Igualmente un usuario de panel, no puede entrar a la app.'
                    },
                ],
                Controlador: [
                    {
                        Archivo:'services/app/Http/Controllers/UserController.php',
                        comentario: 'Se remueve el objeto Equipo en respuesta de list'
                    },
                ],
                Route:[],
                Vista: [],
                InstalacionDeDependencias: [],
                TablasMigraciones: [],
                Otros: [
                    {
                        Archivo: 'services/app/custom/ResponseManager.php',
                        comentario: 'Se separa a una funcion propia que genere el archivo LOG de Errores [createLog], de modo que la funcion esta implicita en el metodo [setErrorServerResponse] ' +
                            'pero tiene la capacidad de ser utilizada individualmente de manera externa solo para generar un log si es necesario'
                    },
                ],
            },

            {
                FechaIncio: '24-03-2022',
                FechaFin: '24-03-2022',
                Descripcion: 'Se agrega correccion de migracion de prestamos, correccion de api de entegadores, y aguste en controlador de clientes',
                Carpeta: [],
                Modelos: [],
                Controlador: [
                    {
                        Archivo:'services/app/Http/Controllers/ClientesController.php',
                        comentario: 'Se agrega en el metodo list, la propiedad de flitrar a los clientes eliminados de los que no, se agrega IdMontoSolicitud' +
                            'al update de la api, ya que no lo tenia'
                    },
                    {
                        Archivo:'services/app/Http/Controllers/PrestamosController.php',
                        comentario: 'Se aplican validaciones a la api de getPrestamosEntregador para que solo usuarios entregadores puedan leer esa informacion'
                    },
                ],
                Route:[],
                Vista: [],
                InstalacionDeDependencias: [],
                TablasMigraciones: [
                    {
                        Archivo:'services/database/migrations/prestamos_table.php',
                        comentario: 'se cambia el tipo de dato de IdMontoSolicitud de string a TinyInteger'
                    },
                ],
                Otros: [
                    {
                        Archivo: 'services/composer.json',
                        comentario: 'Se anexa por PHPstorm el ext-json como complemento de codificacion'
                    },
                ],
            },

            {
                FechaIncio: '24-03-2022',
                FechaFin: '24-03-2022',
                Descripcion: 'Se modifica el cotejamiento de la base de datos a utf8|utf8_general_ci',
                Carpeta: [],
                Modelos: [],
                Controlador: [],
                Route:[],
                Vista: [],
                InstalacionDeDependencias: [],
                TablasMigraciones: [],
                Otros: [
                    {
                        Archivo: 'services/config/database.php',
                        comentario: 'se modifica el charset de utf8mb4 a utf8 y collation de utf8mb4_unicode_ci a utf8_general_ci'
                    },
                ],
            },

            {
                FechaIncio: '11-05-2022',
                FechaFin: '12-05-2022',
                Descripcion: 'Se realizo incorporacion de recalculado de valores apartir del monto real entregado al cliente.',
                Carpeta: [],
                Modelos: [],
                Controlador: [
                    {
                        Archivo: 'services/app/Http/Controllers/PrestamosEntregasController.php',
                        comentario: 'Se integra en la funcion PrestamoEntregaApp(), el helper de calculo de montos de pago del prestamo' +
                            'basandoce en la cantidad de dinero indicada como entregada por el usuario al cliente.'
                    }
                ],
                Route:[],
                Vista: [],
                InstalacionDeDependencias: [],
                TablasMigraciones: [],
                Otros: [
                    {
                        Archivo: 'services/app/custom/CalculaMontosPrestamo',
                        comentario: 'se modifica el funcionamiento de la funcion calulaMontos() de esta libreria, permitiendo que ahora sea directamente' +
                            'el monto que se desea calcular sus montos el que se recibe como parametro y no un objeto de tipo prestamo, como era anteriormente.'
                    },
                ],
            },

            {
                FechaIncio: '12-05-2022',
                FechaFin: '13-05-2022',
                Descripcion: 'Se realizo que al inicio de sesion, el nombre del usuario se le quiten caracteres y todo en mayuscula' +
                    'se corrige combo monto en prospectos',
                Carpeta: [],
                Modelos: [],
                Controlador: [
                    {
                        Archivo: 'services/app/Http/Controllers/UserController.php',
                        comentario: 'Se integra en la funcion login(), al momento de devolver la informacion del usuario que el nombre del mismo usuario se retorne' +
                            'en mayusculas y sin acentos, para verificar si en el Ticket aparece sin errores de los acentos'
                    },
                    {
                        Archivo: 'services/app/Http/Controllers/ClientesController.php',
                        comentario: 'Se en la funcion findProspectosPrestamos(), se agrega en el array de columnas del listado la funcion strtoupper() en conjunto con quitarCaracteres()   ' +
                            'para poner los nombres completos de los clientes en mayusculas y sin acentos, a peticion del area movil para evitar problemas de acentos en la impresion ' +
                            'de los tickes'
                    }
                ],
                Route:[],
                Vista: [
                    {
                        Archivo: 'admin/src/views/modulos/crm/prospectos/Form.vue',
                        comentario: 'Fue cambiado el v-model de combo de montos de IdMontoPrestamo a MontoPrestamo, asi como el value de los option debido a que ahora lo ' +
                            'que se guarda es la cantidad expresada en simbolos (2000.00) y no el Id del registro del monto.'
                    }
                ],
                InstalacionDeDependencias: [],
                TablasMigraciones: [],
                Otros: [
                    {
                        Archivo: 'services/app/custom/Funciones.php',
                        comentario: 'se agrega funcion quitarCaracteres(), funcion que sirve para limpiar de acentos y caracteres raros una cadena de texto '
                    },
                ],
            },
        ]

    }
}
