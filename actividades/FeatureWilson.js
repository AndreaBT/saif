function PullRequest() {
    return {
        actividad: {
            FechaIncio: '00-00-0000',
            FechaFin: '00-00-0000',
            Descripcion: '',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
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
                    Archivo: '',
                    comentario: ''
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
            FechaIncio: '12-02-2022',
            FechaFin: '16-02-2022',
            Descripcion: 'Se crearon las configuraciones que los componente van a requerir, se estabilizo la estructura de modal, boton save, boton accion, clist, loader, sin registro y paginador, cada componente fue adaptado para recibir la nueva estructura y para que se puedan abrir multiples modales con un solo componentes y que estos no choquen entre si, se reconfiguraron algunas lineas del login, store, main y route en la vista',
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
            Vista: [
                {
                    Archivo: 'src/components/CBtnAccion.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },{
                    Archivo: 'src/components/CBtnSave.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },{
                    Archivo: 'src/components/CList.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },{
                    Archivo: 'src/components/CLoader.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },{
                    Archivo: 'src/components/CModal.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },{
                    Archivo: 'src/components/CPagina.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },{
                    Archivo: 'src/components/CSinRegistros.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },
                {
                    Archivo: 'src/components/Cvalidation.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                },{
                    Archivo: 'src/components/Login.vue',
                    comentario: 'Cambio de estructura general, cambio de nombres, cambio de emits'
                }
            ],
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: {
                NA
            },
            Otros: [
                {
                    Archivo: 'src/store/index.js',
                    comentario: 'Cambio palabra Token'
                },
                {
                    Archivo: 'src/router/index.js',
                    comentario: 'cambio de espacios y posicion de vue y vuex'
                },
                {
                    Archivo: 'views/main.js',
                    comentario: 'Cambio de la posicion de la instancia de axios'
                }
            ],
        },
        actividad: {
            FechaIncio: '16-02-2022',
            FechaFin: '18-02-2022',
            Descripcion: 'Se cambio la estructura de los metodos y los emits en los componentes, se reparo alertas al abrir modal, se reparo la vista de perfiles para recibir los cambios del crud, se ajustaron las rutas del router de vista y se preparo el configurativo para los mensajes de alert',
            Carpeta: {
                NA
            },
            Modelos: {
                NA
            },
            Controlador: [
                {
                    Archivo: 'app/Http/Controllers/PerfilesControlers.php',
                    comentario: 'Cambio algunas palabras para devolver correctamente las respuestas al guardar o actualizar, asi como el validator'
                },
            ],
            Route: {
                NA
            },
            Vista:[
                {
                    Archivo: 'src/components/CBtnAccion.vue',
                    comentario: 'Se cambiaron metodos y emits a ingles'
                },
                {
                    Archivo: 'src/components/CBtnSave.vue',
                    comentario: 'Se cambiaron metodos y emits a ingles'
                },
                {
                    Archivo: 'src/views/Catalogos/Perfiles/List.vue',
                    comentario: 'se ajusto los metodos a los emits y se preparo para recibir el crud correctamente'
                },
                {
                    Archivo: 'src/views/Catalogos/Perfiles/Form.vue',
                    comentario: 'se ajusto los metodos a los emits'
                },
                
            ],
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: {
                NA
            },
            Otros: [
                {
                    Archivo: 'src/helpers/varConfig.js',
                    comentario: 'se añadieron los 2 configurativos de mensajes de swal para el eliminar'
                },
                {
                    Archivo: 'src/helpers/',
                    comentario: 'se eliminaron los 2 configurativos que existian para el swal del delete'
                },
                {
                    Archivo: 'src/router/index.js',
                    comentario: 'se cambio la estructura de los imports para dividirlos de acuerdo a la seccion del menu'
                },
                {
                    Archivo: 'src/router/RutasCalendario.js',
                    comentario: 'Nuevo, division de los routers de la vista'
                },
                {
                    Archivo: 'src/router/RutasClientes.js',
                    comentario: 'Nuevo, division de los routers de la vista'
                },
                {
                    Archivo: 'src/router/RutasInicio.js',
                    comentario: 'Nuevo, division de los routers de la vista'
                },
                {
                    Archivo: 'src/router/RutasMenuDerecho.js',
                    comentario: 'Nuevo, division de los routers de la vista'
                },
                {
                    Archivo: 'src/router/RutasPrestamos.js',
                    comentario: 'Nuevo, division de los routers de la vista'
                },
                {
                    Archivo: 'src/router/RutasUsuarios.js',
                    comentario: 'Nuevo, division de los routers de la vista'
                },
            ],
        },
        actividad: {
            FechaIncio: '18-02-2022',
            FechaFin: '22-02-2022',
            Descripcion: 'Se cambio el acceso de las rutas y se establecio la separacion del menu mediante las opciones que tiene cada menu, con su respectiva configuracion, se modifico los breadcumbs del clist, se crearon modelos, controladores y tablas para panel y panelxpermiso',
            Carpeta: {
                NA
            },
            Modelos: [
                {
                    Archivo: 'app/Models/PanelMenu.php',
                    comentario: 'Modelo Nuevo'
                },
                {
                    Archivo: 'app/Models/PanelxPermiso.php',
                    comentario: 'Modelo Nuevo'
                },
            ],
            Controlador: [
                {
                    Archivo: 'app/Http/Controllers/PerfilesControlers.php',
                    comentario: 'Se modifico los valores para poder recibir bien el paginador'
                },
                {
                    Archivo: 'app/Http/Controllers/PanelMenuControlers.php',
                    comentario: 'CRUD Nuevo'
                },
            ],
            Route: {
                NA
            },
            Vista: [
                {
                    Archivo: 'src/components/CList.vue',
                    comentario: 'Se adapto para recibir el breadcrumbs dinamico y se eliminaron elementos demas'
                },
                {
                    Archivo: 'src/components/CModal.vue',
                    comentario: 'Se cambiaron de lugar los emit de accion por que marcaban  error de emits'
                },
                {
                    Archivo: 'src/components/CNavLinksMenu.vue',
                    comentario: 'Componente nuevo creado para el menu'
                },
                {
                    Archivo: 'src/views/perfiles/List.vue',
                    comentario: 'Se reparo el paginador'
                },
                {
                    Archivo: 'src/views/template/NavLinks.vue',
                    comentario: 'Se cambiaron el menu para hacerlo dinamico'
                },
            ],
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: [
                {
                    Archivo: 'PanelesMenus',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'Panelesxpermisos',
                    comentario: 'Nuevo'
                },
            ],
            Otros: [
                {
                    Archivo: 'src/config/ConfigMenu.js',
                    comentario: 'se creo para almancenar las rutas dinamicas del menu'
                },
                {
                    Archivo: 'src/import/index.js',
                    comentario: 'Se modifico para recibir las rutas del menu en archivos separados'
                },
                {
                    Archivo: 'src/router/index.js',
                    comentario: 'se adapto para el cambio de rutas '
                },
                {
                    Archivo: 'src/router/RutasCobranza.js',
                    comentario: 'se cambio para contener las rutas de esa seccion'
                },
                {
                    Archivo: 'src/router/RutasConfiguracion.js',
                    comentario: 'se cambio para contener las rutas de esa seccion'
                },
                {
                    Archivo: 'src/router/RutasCrm.js',
                    comentario: 'se cambio para contener las rutas de esa seccion'
                },
                {
                    Archivo: 'src/router/RutasReportes.js',
                    comentario: 'se cambio para contener las rutas de esa seccion'
                },
            ],
        },
        actividad: {
            FechaIncio: '22-02-2022',
            FechaFin: '25-02-2022',
            Descripcion: 'se estan anexando las partes visuales del listado y form de paneles, es decir los menus',
            Carpeta: {
                NA
            },
            Modelos: [
            ],
            Controlador: [
                {
                    Archivo: 'app/Http/Controllers/PanelMenuControlers.php',
                    comentario: 'se adaptaron los metodos para el controlador'
                },
                
            ],
            Route: [
                {
                    Archivo: 'app/routes/api.php',
                    comentario: 'CRUD Nuevo de paneles menu'
                },
            ],
            Vista: [
                {
                    Archivo: 'src/views/paneles/List.vue',
                    comentario: 'vista nueva'
                },
                {
                    Archivo: 'src/views/paneles/Form.vue',
                    comentario: 'vista nueva'
                },
            ],
            InstalacionDeDependencias: {
                NA
            },
            TablasMigraciones: [
            ],
            Otros: [
                {
                    Archivo: 'src/config/ConfigMenu.js',
                    comentario: 'se anexo la ruta para los paneles'
                },
                {
                    Archivo: 'src/config/RutasMenuDerecho.js',
                    comentario: 'se reemplazo la ruta de perfiles y paneles'
                },
                {
                    Archivo: 'src/router/RutasConfiguracion.js',
                    comentario: 'se anexo las rutas para paneles y perfiles'
                },
               
            ],
        },
        actividad: {
            FechaIncio: '02-03-2022',
            FechaFin: '04-03-2022',
            Descripcion: 'Se repararon vistas del menu, config, asi como crearon los accesos para los catalogos equipo, monto credito, estados y municipios',
            Carpeta: [
            ],
            Modelos: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Controlador: [
                {
                    Archivo: 'app/http/Controllers/EstadosController.php',
                    comentario: 'se repararon respuestas y proceso de guardado'
                },{
                    Archivo: 'app/http/Controllers/MunicipiosController.php',
                    comentario: 'se repararon respuestas y proceso de guardado'
                },{
                    Archivo: 'app/http/Controllers/EquiposController.php',
                    comentario: 'se repararon respuestas y proceso de guardado'
                },{
                    Archivo: 'app/http/Controllers/MontosCreditosController.php',
                    comentario: 'Nuevo'
                },{
                    Archivo: 'app/http/Controllers/PerfilesController.php',
                    comentario: 'se repararon algunas opciones de los metodos'
                },
            ],
            Route: [
                {
                    comentario: ''
                },
            ],
            Vista: [
                {
                    Archivo: 'src/views/catalogos/estados',
                    comentario: 'Nuevo'
                },{
                    Archivo: 'src/views/catalogos/municipios',
                    comentario: 'Nuevo'
                },{
                    Archivo: 'src/views/catalogos/montoCredito',
                    comentario: 'Nuevo'
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
                    Archivo: 'src/config/ConfigMenu.js',
                    comentario: 'se anexaron los menus de estados y se cambiaron de lugar puestos y equipos'
                },
                {
                    Archivo: 'src/config/General.js',
                    comentario: 'se adapto con comentarios y se anexaron configuracion para preview de imagenes'
                },
                {
                    Archivo: 'src/helpers/VarConfig.js',
                    comentario: 'se anexo validacion para subir imagenes de 2 megas'
                },
                {
                    Archivo: 'src/router/RutasConfiguracion.js',
                    comentario: 'se anexaron rutas de equipos, puestos, municipios, montos credito'
                },
                {
                    Archivo: 'src/router/RutasEmpleado.js',
                    comentario: 'se quitaron las rutas de equipos y puestos y se enviaron a configuracion'
                },
            ],
        },
        actividad: {
            FechaIncio: '11-03-2022',
            FechaFin: '14-03-2022',
            Descripcion: 'reparacion completa del formulario de cliente-prospecto, reparacion de las apis de los combos asi como de el proceso de guardado',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: 'app/models/ClienteEvidencia.php',
                    comentario: 'se modifico para agregarle 2 parametros mas, TipoEvidencia y PosicionEvidencia'
                },
            ],
            Controlador: [
                {
                    Archivo: 'app/Http/Controllers/ClientesController.php',
                    comentario: ''
                },
                {
                    Archivo: 'app/Http/Controllers/ClientesEvidenciasController.php',
                    comentario: ''
                },
                {
                    Archivo: 'app/Http/Controllers/PrestamosMontosController.php',
                    comentario: ''
                },
                {
                    Archivo: 'app/Http/Controllers/RutassController.php',
                    comentario: ''
                },
                {
                    Archivo: 'app/Http/Controllers/RutasxEmpleadosController.php',
                    comentario: ''
                },
                {
                    Archivo: 'app/Http/Controllers/SucursalesController.php',
                    comentario: ''
                },
            ],
            Route: [
                {
                    Archivo: 'routes/api.php',
                    comentario: 'clientesevidenciasUpdate se creo como alternativa'
                },
            ],
            Vista: [
                {
                    Archivo: 'src/views/catalogos/clientes/Detail.vue',
                    comentario: ''
                },
                {
                    Archivo: 'src/views/catalogos/montrosCredito/List.vue',
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
                    Archivo: 'database/migrations/prestamos',
                    comentario: 'se le coloco campos nulables a varios y valor por default'
                },
                {
                    Archivo: 'database/migrations/clientesevidencias',
                    comentario: 'se agregaron los campos de TipoEvidencia y PosicionEvidencia'
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
            FechaIncio: '14-03-2022',
            FechaFin: '14-03-2022',
            Descripcion: 'se reparo el accion de guardar de la vista del cliente',
            Carpeta: [
                {
                    Archivo: 'imgClientesEvidencias',
                    comentario: 'nuevo'
                },
            ],
            Modelos: [
                {
                    Archivo: 'ClientesEvidencias',
                    comentario: 'se elimino el campo PosicionEvidencia'
                },
            ],
            Controlador: [
                {
                    Archivo: 'ClientesController',
                    comentario: 'se modifico para aceptar imagenes'
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
                    Archivo: 'catalogos/clientes/List',
                    comentario: 'se mofico data'
                },
                {
                    Archivo: 'catalogos/clientes/Detail.vue',
                    comentario: 'se adapto el resto del formulario para las evidencias'
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
                    Archivo: 'ClientesEvidencias',
                    comentario: 'se elimino el campo PosicionEvidencia'
                },
            ],
            Otros: [
                {
                    Archivo: 'FilesManager',
                    comentario: 'se modifico la funcion UploadMultiThumbnailImages para adaptarla a recibir imagenes en iteraciones para las evidencias de clientes'
                },
            ],
        },
        actividad: {
            FechaIncio: '15-03-2022',
            FechaFin: '17-03-2022',
            Descripcion: 'asignacion del personal para una ruta',
            Carpeta: [
                {
                    Archivo: 'src/views/catalogos/rutas',
                    comentario: 'Nuevo'
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
                    Archivo: 'controllers/RutasxEmpleadosController',
                    comentario: 'se modificacion metodos add y finallinner'
                },
            ],
            Route: [
                {
                    Archivo: 'rutasxusuariosUsers',
                    comentario: 'nuevo para traer todos los usuarios a la asignacion de usuarios x ruta'
                },
            ],
            Vista: [
                {
                    Archivo: 'src/views/catalogos/rutas',
                    comentario: 'Nuevo'
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
            FechaIncio: '18-03-2022',
            FechaFin: '19-03-2022',
            Descripcion: 'se termino de reparar las vistas de paneles de menus y se reparo el asignar de las rutas',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
                },
            ],
            Modelos: [
                {
                    Archivo: 'app/Models/PanelMenu.php',
                    comentario: 'se anexaron campos IdMenu, IdSubMenu, IdApartado'
                },
                {
                    Archivo: 'app/Models/Panelxpermiso.php',
                    comentario: 'se quito softdelete'
                },
                {
                    Archivo: 'app/Models/RutaxEmpleado.php',
                    comentario: 'se quito softdelete'
                },
            ],
            Controlador: [
                {
                    Archivo: 'app/Http/Controllers/PanelesxPermisosController.php',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'app/Http/Controllers/PanelMenuController.php',
                    comentario: 'se modifico el crud'
                },
                {
                    Archivo: 'app/Http/Controllers/PermisosController.php',
                    comentario: 'se modifico el crud'
                },
                {
                    Archivo: 'app/Http/Controllers/RutasxEmpleadosController.php',
                    comentario: 'se corrigio detalles del eliminar'
                },
            ],
            Route: [
                {
                    Archivo: 'routes/api.php',
                    comentario: 'se agregaron las rutas de paneles x permisos'
                },
            ],
            Vista: [
                {
                    Archivo: 'src/views/catalogos/clientes/Detail.vue',
                    comentario: 'se estaba ajustando los input file (aun pendiente por terminar)'
                },
                {
                    Archivo: 'src/views/catalogos/clientes/List.vue',
                    comentario: 'se reparo variables'
                },
                {
                    Archivo: 'src/views/catalogos/montosCredito/List.vue',
                    comentario: 'se reparo $ del prototype'
                },
                {
                    Archivo: 'src/views/catalogos/paneles/List.vue',
                    comentario: 'se adapto para terminar de leer datos dinamicos, se agrego boton de asignacion y filtro por tipomenu'
                },
                {
                    Archivo: 'src/views/catalogos/paneles/Form.vue',
                    comentario: 'se reparo para recibir un arreglo unico de todos los menus,s,a,sa'
                },
                {
                    Archivo: 'src/views/catalogos/paneles/FormAsignacion.vue',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'src/views/catalogos/Permisos/List/Form.vue',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'src/views/catalogos/rutas/FormAsignacion.vue',
                    comentario: 'se reparo el recuperar'
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
                    Archivo: 'RutasxEmpleados',
                    comentario: 'se elimino el softdelete'
                },
                {
                    Archivo: 'PanelesxPermisos',
                    comentario: 'se elimino el softdelete'
                },
            ],
            Otros: [
                {
                    Archivo: 'src/router/RutasConfiguracion.js',
                    comentario: 'se anexo las rutas para permisos'
                },
                {
                    Archivo: 'src/config/configMenu.js',
                    comentario: 'se anexo el item para permisos'
                },
            ],
        },
        actividad: {
            FechaIncio: '21-03-2022',
            FechaFin: '24-03-2022',
            Descripcion: 'se creo permisos para los perfiles asi como la asignacion de los menus y permisos al perfil seleccionado',
            Carpeta: [
                {
                    Archivo: 'src/views/catalogos/menusxPerfil',
                    comentario: 'Nuevo'
                },
            ],
            Modelos: [
                {
                    Archivo: 'app/Models/PerfilxMenu.php',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'app/Models/PerfilxPermiso.php',
                    comentario: 'Nuevo'
                },
            ],
            Controlador: [
                {
                    Archivo: 'app/Http/controllers/PerfilesxMenusController.php',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'app/Http/controllers/PanelMenuController.php',
                    comentario: 'se actualizo parametros al eliminar y devolver los asignados'
                },
            ],
            Route: [
                {
                    Archivo: 'perfilesxmenus',
                    comentario: 'rutas para la asignacion de menus al perfil'
                },
                {
                    Archivo: 'panelesxpermisos',
                    comentario: 'rutas para la asignacion de permisos al perfil'
                },
            ],
            Vista: [
                {
                    Archivo: 'src/views/catalogos/empresas/Form.vue',
                    comentario: 'se agrego validacion para campos cp y telefono'
                },
                {
                    Archivo: 'src/views/catalogos/paneles/Form-List.vue',
                    comentario: 'se termino de crear las rutas dependientes'
                },
                {
                    Archivo: 'src/views/catalogos/perfiles/List.vue',
                    comentario: 'se modifico para agregar el boton para asignar los permisos y menus al perfil'
                },
                {
                    Archivo: 'src/views/catalogos/sucursales/Form.vue',
                    comentario: 'se agrego validacion para campos cp y telefono'
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
                    Archivo: 'perfilesxmenus',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'perfilesxpermisos',
                    comentario: 'Nuevo'
                },
            ],
            Otros: [
                {
                    Archivo: 'src/router/RutasConfiguracion.js',
                    comentario: 'se anexo la ruta para la vista al submenu'
                },
                {
                    Archivo: 'src/config/ConfigMenu.js',
                    comentario: 'se anexaron las rutas y parametros nuevos como son tipoboton en menu e icono en submenu'
                },
                {
                    Archivo: 'src/components/CNavLinksMenu.vue',
                    comentario: 'se modifico para anexar el tipo de boton para que vaya a un submenu de cards'
                },
                {
                    Archivo: 'src/views/template/SubMenus.vue',
                    comentario: 'Nuevo'
                },
            ],
        },
        actividad: {
            FechaIncio: '24-03-2022',
            FechaFin: '25-03-2022',
            Descripcion: 'se actualizaron las vistas de las cargas de imagenes en el prospecto, asi como se reparo detalles en la ubicacion de los usuarios a los que se asignan a una ruta, para que tome en cuenta los 3 perfiles',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
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
                    Archivo: 'ClientesController.php',
                    comentario: 'se anexo parametros IdMontoSolicitud al validador y se corregio en el como lo recibe'
                },
                {
                    Archivo: 'UserController.php',
                    comentario: 'se esta empezando a modificar para realizar la reparacion del inser y update en el empleado'
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
                    Archivo: 'src/views/modulos/empleados/SaveEmpleado.vue',
                    comentario: 'se corrigio html, posicion de los metodos y se esta empezando a reparar el save'
                },
                {
                    Archivo: 'src/views/catalogos/menusxPerfil/AsignarList.vue',
                    comentario: 'se reparo la condicion de ocultar el contenido cuando no se ha seleccionado nada en el combo de menu'
                },
                {
                    Archivo: 'src/views/catalogos/clientes/Detail.vue',
                    comentario: 'se reparo las vista para que reciba correctamente la visualizacion de las imagenes'
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
                    Archivo: 'src/config/General.js',
                    comentario: 'se anexo un metodo nuevo para el preview de las imagenes en array'
                },
            ],
        },
        actividad: {
            FechaIncio: '14-04-2022',
            FechaFin: '15-04-2022',
            Descripcion: 'se comenzo con el proceso de dar de baja al empleado y por consiguiente al usuario, asignando los prestamos a un cobratario distinto',
            Carpeta: [
                {
                    Archivo: '',
                    comentario: ''
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
                    Archivo: 'Http/Controllers/PrestamosController.php',
                    comentario: ''
                },
                {
                    Archivo: 'Http/Controllers/UserController.php',
                    comentario: ''
                },
            ],
            Route: [
                {
                    Archivo: 'getPrestamosAsignados',
                    comentario: 'ruta para traer los usuarios cobratarios de la sucursal'
                },
                {
                    Archivo: 'ReasignarPrestamosUsuario',
                    comentario: 'post para guardar, cambiar ids y dar de baja al empleado-usuario'
                },
            ],
            Vista: [
                {
                    Archivo: 'src/views/modulos/empresa/empleados/FormBaja.vue',
                    comentario: 'Nuevo'
                },
                {
                    Archivo: 'src/views/modulos/empresa/empleados/List.vue',
                    comentario: 'se creo el boton para inciar proceso de baja'
                },
                {
                    Archivo: 'src/views/modulos/empresa/empleados/SaveEmpleado.vue',
                    comentario: 'se modifico la obtencion de 18 años atras'
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
                    Archivo: 'ninguno',
                    comentario: ''
                },
            ],
        },
    }
}