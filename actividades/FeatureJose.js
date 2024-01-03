function PullRequest() {
    return {
        actividad: {
            FechaIncio: '06-02-2022',
            FechaFin: '09-02-2022',
            Descripcion: 'modificacion y agregacion de modelos y controladores  on tables de migraciones de perfiles y permisos, a su vez se agregaron las rutas correspondienets de estos 2 catalogos',
            Carpeta: {
                Catalogos,
                Modulos,
                Security,
                Template,
                Import,
                helpers,
                Config,
                Style
            },
            Modelos: {
                Perfil,
                Permiso
            },
            Controlador: {
                PerfilesController,
                PermisosController
            },
            Route: {
                BackEnd: {
                    Perfiles,
                    Permisos
                },
                FrontEnd: {
                    Login,
                    Template,
                    Clientes,
                    Usuarios,
                    Pefiles,
                    Configuraciones,
                    Dashboard,
                    Prestamos
                }
            },
            Vista: {
                components: {
                    CBtnAccion,
                    CBtnSave,
                    Clist,
                    Cloader,
                    CMapa,
                    Cmodal,
                    Cpagina,
                    Cpdf,
                    CsinRegistro,
                    CTabla,
                    CValidation
                },
                Config: {
                    Gerenal,
                    HttpConfig
                },
                Helpers: {
                    Delete,
                    DeleteConfirmation,
                    VarConfig
                },
                Import: {
                    Index
                },
                views: {
                    Catalogos: {
                        Usuarios,
                        Perfiles,
                        Clientes
                    },
                    Modulos: {
                        Configuracion,
                        Dashboard,
                        Prestamos
                    },
                    Security: {
                        Login
                    },
                    Template: {
                        NavLink,
                        Template
                    }
                }
            },
            InstalacionDeDependencias: {
                fortawesome,
                popperjs,
                treeselect,
                axios,
                bootstrap,
                bootstrapvue,
                corejs,
                jquery,
                moment,
                popper,
                vcalendar,
                vue,
                vueclipboard2,
                vueizitoast,
                vuemoment,
                vuemonthlypicker,
                vuepdfapp,
                vuerouter,
                vueselect,
                vuesweetalert2,
                vuedraggable,
                vuex,
            },
            TablasMigraciones: {
                Perfiles,
                Permisos
            },
            Otros: {
                vue: 'se agregaron css base, mientras el equipo de maquetado nos pasa el original',
            },
        },
        actividad: {
            FechaIncio: '10-02-2022',
            FechaFin: '15-02-2022',
            Descripcion: 'Se trabajo en la parte de el login, se añadio el ccs final que nos otorgo el equipo de diseño, se pusieron los componentes generales globales para ya no estarlo importando en cada archivo vue de nuestros catalogos y modelos, se trabajo en el apartado del main, el js del router, el js del import, y el js de config, se dejo el css base y el archivo css style.',
            Carpeta: {
                Calendarios,
                Paneles,
                Permisos,
            },
            Modelos: {
                NA
            },
            Controlador: {
                NA
            },
            Route: {
                FrontEnd: {
                    Calendarios,
                    Paneles,
                    Permisos,
                }
            },
            Vista: {
                Paneles,
                Permisos
            },
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: {
                NA
            },
            Otros: {
                css: 'Se agrego el css correcto que llevara la estructura original del proyecto.',
                cssStyle: 'Se dejara el archivo Style para poder agregar funciones css que despues se necesite y que no tenga comtemplado el equipo de maquetado.'
            },
        },
        actividad: {
            FechaIncio: '15-02-2022',
            FechaFin: '16-02-2022',
            Descripcion: 'Se reparo el inicio de sesion del login, , se modifico el router.js(Router), General.js, Index.js(Store), Main.j, se agrego la imagen del avatar que llevara el formulario del empleado al momento de subir la foto o imagen.',
            Carpeta: {
                NA
            },
            Modelos: {
                NA
            },
            Controlador: {
                NA
            },
            Route: {
                NA
            },
            Vista: {
                Paneles,
                Permisos
            },
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: {
                NA
            },
            Otros: {
                NA
            },
        },
        actividad: {
            FechaIncio: '18-02-2022',
            FechaFin: '18-02-2022',
            Descripcion: 'Se modifico los nombres de las carpetas que van dentro de la carpeta principal del src, todas las carpetas se iniciaran con minusculas,se modificaron los botones del modal asignando el color correspondiente del maquetado.',
            Carpeta: {
                NA
            },
            Modelos: {
                NA
            },
            Controlador: {
                NA
            },
            Route: {
                NA
            },
            Vista: {
                NA
            },
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: {
                NA
            },
            Otros: {
                NA
            },
        },
        actividad: {
            FechaIncio: '18-02-2022',
            FechaFin: '18-02-2022',
            Descripcion: 'se modifico la ruta principal al que se ira dirigido despues de logearte, se agrego el nuevo css de la ultima version del maquteado, se modifico el login, navlink, varconfig,router y se agrego la rutas del inicio y dasboard',
            Carpeta: {
                Inicio
            },
            Modelos: {
                NA
            },
            Controlador: {
                NA
            },
            Route: {
                NA
            },
            Vista: [{
                Archivo: '@/views/modulos/inicio/List.vue',
                comentario: 'Se agrego el apartado de incio para que se redirija el login directo a ello'
            }],
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: {
                NA
            },
            Otros: {
                vue: 'se agrego el nuevo css para la estructura de empleados'
            },
        },
        actividad: {
            FechaIncio: "25-02-2022",
            FechaFin: "25-02-2022",
            Descripcion: "Se modifico el css del style, se configuro el main general, el index de las rutas se agrego la vista del apartado de recuepera contraseña y se trabajo en el apartado del login",
            Carpeta: {
                NA,
            },
            Modelos: {
                NA,
            },
            Controlador: {
                NA,
            },
            Route: {
                FrontEnd: {
                    Archivo: '@/views/security/RecuperarPassword',
                    comentario: 'Se agrego la nueva ruta para el recupera contraseña'
                },
            },
            Vista: {
                NA,
            },
            InstalacionDeDependencias: {
                NA,
            },
            TablasMigraciones: {
                rutas,
            },
            Otros: {
                NA,
            },
        },
        actividad: {
            FechaIncio: "25-02-2022",
            FechaFin: "25-02-2022",
            Descripcion: "Se arreglo los conflictos del css style",
            Carpeta: {
                NA,
            },
            Modelos: {
                NA,
            },
            Controlador: {
                NA,
            },
            Route: {
                NA
            },
            Vista: {
                NA,
            },
            InstalacionDeDependencias: {
                NA,
            },
            TablasMigraciones: {
                rutas,
            },
            Otros: {
                NA,
            },
        },
        actividad: {
            FechaIncio: "28-02-2022",
            FechaFin: "01-03-2022",
            Descripcion: "Se corrigio el apartado del login poniendole el loader al boton y dejando los css preparados para ello. Se modifico el httpConfig donde se le agrego la ruta del demo(mientras tendra la ruta de zumba) se agrego los formularios de mi perfil y cambiar contraseña, en el navlink se modifico el apartado de configuracion principal, se agrego el nuevo css que paso hoy maquetado, se agrego un nuevo swalt en varconfig, se agrego una nueva imagen en la carpeta style, se cambio la direccion de correo en el demo apartado del seder user, se creo la vista en views emails, (esto para los correo, sera la carpeta principal donde se guardaran las vistas de correos, se modifico el .env agergandole los datos principales para el envio de correo), en app se creó la carpeta de Mail, asu vez se creo el archivo mailable de recupera contraseña",
            Carpeta: {
                BackEnd: {
                    Archivo: '@/resources/views/emails',
                    comentario: 'Se agrego la nueva carpeta para guardar los archivos de correo',

                    Archivo: '@/app/Mail',
                    comentario: 'Se agrego la nueva carpeta para guardar los archivos de correo mailable'
                }
            },
            Modelos: {
                NA,
            },
            Controlador: {
                NA,
            },
            Route: {
                NA
            },
            Vista: {
                CambiarPassword,
                Miperfil
            },
            InstalacionDeDependencias: {
                NA,
            },
            TablasMigraciones: {
                rutas,
            },
            Otros: {
                vue: 'se agrego nuevo css y se adaptaron algunas cosas en la parte del navlink, se quitaron las rutas que se  crearon para mi perfil y cambio de password, se volvieron las vitas  un modal.'
            },
        },
        actividad: {
            FechaIncio: "04-03-2022",
            FechaFin: "07-03-2022",
            Descripcion: "Se trabajo en los apartados de imagenes, agregando 4 imagenes, el avatar para el login, y los avatar's de por si es hombre o mujer depensdiendo del sexo se hara una validación y el avatar le aparecerá, se modifico el archivo style, se quitó las clases que chocaban con el archivo main de css, en el index se quito la tipografia que se puso, para que la letra sea mas apreciado como el maquetado, se agrego un nuevo componente, este asu vez podria servir para un futuro, en este caso no lo use, pero este sirve para que pudas poner el avatar del usuario usuando sus nombres, al cbtsave se le agrego el spner que se le quito en su tiempo, al clist se modifico quitandole el # del href, en el varconfig se agrego un nuevo swalt para el apartado de serrar login y restablecer contraseña, en el index de importación se agrego una nueva vista, del Cavatar, se gregaron nuevas rutas en el front y en el back, , se agrego el archivo vue de cambiar password, se modifico el archivo login, mi perfil y recuperacontraseña, se agergo el archivo de restablecer contraseña, se modifico el navlink, las clases en usercontroller se modificaron y agregaron, a su vez se creo, el mailmanager, mailserviceconfig,mailservconfseeder,restablecerpassword, se creo una migraciín de mailserveconfig, se modifico el passwordresets,se modifico el archivo de vista del correo.",
            Carpeta: {
                Back: {
                    Archivo: 'services/public/File',
                    comentario: 'Se agrego la carpeta principal a la que se le estaran agregando las subcarpetas, ejemplo: users, clientes, evidencias'
                }
            },
            Modelos: {
                NA,
            },
            Controlador: {
                UsuarioCpontroller: 'se modifico',
            },
            Route: {
                Front: {
                    RecuperarPassword: 'recoveryaccount',
                },
                Back: {
                    Archivo: 'usresetacount',
                    comentario: 'es para el restablecimiento de contraseña',
                    Archivo: 'usrvalidreset',
                    comentario: 'validamos antes de hacer el restablecimeinto',
                    Archivo: 'usraceptchangepass',
                    comentario: 'se usa para cambiar la contraseña',
                }
            },
            Vista: {
                FrontEnd: {
                    Login,
                    Miperfil,
                    CambiarPassword,
                    RecuperarPassword,
                    RestablecerPassword,
                    NavLink
                },
                BackEnd: {
                    RecuperarPassword
                }
            },
            InstalacionDeDependencias: {
                NA,
            },
            TablasMigraciones: {
                mailserviceconfig,
                password_resets
            },
            Otros: {
                seeder: {
                    Userseeder: 'se modifico el correo del superadmin.',
                    MailservConfSSeeder
                }
            },
        },
        actividad: {
            FechaIncio: "24-03-2022",
            FechaFin: "25-03-2022",
            Descripcion: "Se configuro la parte de diseño en el apartado asignar list y detail.",
            Carpeta: {
                NA,
            },
            Modelos: {
                NA,
            },
            Controlador: {
                NA,
            },
            Route: {
                BackEnd: {
                    NA,
                },
            },
            Vista: {
                NA,
            },
            InstalacionDeDependencias: {
                NA,
            },
            TablasMigraciones: {
                NA,
            },
            Otros: {
                css: 'se modifico el archivo style del css.'
            },
        },
    }
}