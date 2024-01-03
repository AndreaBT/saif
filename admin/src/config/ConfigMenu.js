const MenuInicio = {
    Menu: 'Inicio',
    Icono: 'fas fa-home-lg-alt',
    Permiso: true,
    Router: 'inicio',
    Parametros: {},
    TipoBoton: 0,
    SubMenus: []
};

const MenuReportes = {
    Menu: 'Reportes',
    Icono: 'fas fa-books',
    Permiso: true,
    Router: 'inicio',
    Parametros: {},
    TipoBoton: 0,
    SubMenus: []
};

const MenuMiEmpresa = {
    Menu: 'Empresa',
    Icono: 'fas fa-building',
    Permiso: true,
    Router: '',
    Parametros: {},
    TipoBoton: 0,
    SubMenus: [{
            NombreSubMenu: 'Mi Empresa',
            Icono: "",
            Permiso: true,
            Router: 'empresa',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: 'Empleados',
            Icono: "",
            Permiso: true,
            Router: 'empleados',
            Parametros: {},
            Apartados: []
        }
    ]
};

const MenuCrm = {
    Menu: "CRM",
    Icono: "fas fa-chart-network",
    Permiso: true,
    Router: "",
    Parametros: {},
    TipoBoton: 0,
    SubMenus: [{
            NombreSubMenu: "Prospectos",
            Permiso: true,
            Router: "prospectos",
            Parametros: {},
            Apartados: [],
        },
        {
            NombreSubMenu: "Clientes",
            Permiso: true,
            Router: "clientesActivos",
            Parametros: {},
            Apartados: [],
        },
        {
            NombreSubMenu: "Prestamos",
            Permiso: true,
            Router: "menuprestamos",
            Parametros: {},
            Apartados: [],
        },
        {
            NombreSubMenu: "Reasignación",
            Permiso: true,
            Router: "reasignacion",
            Parametros: {},
            Apartados: [],
        },

    ],
};

const MenuCobranza = {
    Menu: 'Cobranza',
    Icono: 'fas fa-usd-square',
    Permiso: true,
    Router: '',
    Parametros: {},
    TipoBoton: 0,
    SubMenus: [{
            NombreSubMenu: 'Corte del Día',
            Icono: "",
            Permiso: true,
            Router: 'corteDia',
            Parametros: {},
            Apartados: []
        },
    ]
};

const MenuConfig = {
    Menu: 'Configuración',
    Icono: 'fas fa-cog',
    Permiso: true,
    Router: '',
    Parametros: {},
    TipoBoton: 0,
    SubMenus: [{
            NombreSubMenu: 'Montos de Crédito',
            Icono: "",
            Permiso: true,
            Router: 'montoscredito',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: 'Tazas de Interes',
            Icono: "",
            Permiso: true,
            Router: 'taza',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: 'Días Inhabiles',
            Icono: "",
            Permiso: true,
            Router: '',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: 'Equipos',
            Icono: "",
            Permiso: true,
            Router: 'equipos',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: 'Perfiles',
            Icono: "",
            Permiso: true,
            Router: 'perfiles',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: "Puestos",
            Icono: "",
            Permiso: true,
            Router: "puestos",
            Parametros: {},
            Apartados: [],
        },
        {
            NombreSubMenu: 'Menus',
            Icono: "",
            Permiso: true,
            Router: 'paneles',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: "Rutas",
            Icono: "",
            Permiso: true,
            Router: "rutas",
            Parametros: {},
            Apartados: [],
        },
        {
            NombreSubMenu: 'Permisos',
            Icono: "",
            Permiso: true,
            Router: 'permisos',
            Parametros: {},
            Apartados: []
        },
        {
            NombreSubMenu: "Correo Electrónico",
            Icono: "",
            Permiso: true,
            Router: "correo",
            Parametros: {},
            Apartados: [],
        },
    ]
};

const ConfigMenus = [
    MenuInicio,
    MenuMiEmpresa,
    MenuCrm,
    MenuCobranza,
    MenuReportes,
    MenuConfig
];

export default {
    ConfigMenus,
}
