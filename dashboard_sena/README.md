# Dashboard SENA - Sistema de Gestión Académica

Sistema completo de gestión académica para el SENA con arquitectura MVC, sistema de autenticación, manejo de errores global y calendario interactivo.

## 📁 Estructura del Proyecto

```
dashboard_sena/
│
├── 📂 assets/                      # Recursos estáticos
│   ├── css/                        # Hojas de estilo
│   ├── images/                     # Imágenes e iconos
│   └── icons/                      # Iconos del sistema
│
├── 📂 auth/                        # Sistema de autenticación
│   ├── check_auth.php              # Verificación de sesión
│   ├── login.php                   # Página de login
│   ├── logout.php                  # Cerrar sesión
│   └── README_LOGIN.md             # Documentación de autenticación
│
├── 📂 config/                      # Configuración del sistema
│   └── error_handler.php           # Manejador global de errores
│
├── 📂 controller/                  # Controladores MVC
│   ├── BaseController.php          # Controlador base
│   ├── AsignacionController.php    # Gestión de asignaciones
│   ├── FichaController.php         # Gestión de fichas
│   ├── InstructorController.php    # Gestión de instructores
│   ├── AmbienteController.php      # Gestión de ambientes
│   ├── ProgramaController.php      # Gestión de programas
│   ├── CompetenciaController.php   # Gestión de competencias
│   ├── DashboardController.php     # Dashboard principal
│   └── README_CONTROLADORES.md     # Documentación de controladores
│
├── 📂 helpers/                     # Funciones auxiliares
│   └── functions.php               # Funciones helper globales
│
├── 📂 logs/                        # Logs del sistema
│   ├── php_errors.log              # Registro de errores PHP
│   ├── .htaccess                   # Protección de logs
│   └── .gitignore                  # Ignorar logs en git
│
├── 📂 model/                       # Modelos de datos
│   ├── AsignacionModel.php         # Modelo de asignaciones
│   ├── FichaModel.php              # Modelo de fichas
│   ├── InstructorModel.php         # Modelo de instructores
│   ├── AmbienteModel.php           # Modelo de ambientes
│   ├── ProgramaModel.php           # Modelo de programas
│   ├── CompetenciaModel.php        # Modelo de competencias
│   └── ...                         # Otros modelos
│
├── 📂 views/                       # Vistas del sistema
│   ├── layout/                     # Plantillas base
│   │   ├── header.php              # Encabezado
│   │   ├── sidebar.php             # Menú lateral
│   │   └── footer.php              # Pie de página
│   ├── errors/                     # Páginas de error
│   │   └── 500.php                 # Error del servidor
│   ├── asignacion/                 # Vistas de asignaciones
│   ├── ficha/                      # Vistas de fichas
│   ├── instructor/                 # Vistas de instructores
│   └── ...                         # Otras vistas
│
├── 📂 _docs/                       # Documentación
│   ├── ESTRUCTURA_PROYECTO.md      # Estructura del proyecto
│   ├── SISTEMA_MANEJO_ERRORES.md   # Sistema de errores
│   ├── CORRECCION_FORMULARIOS.md   # Guía de corrección
│   └── COMO_APLICAR_TEMA.md        # Guía de estilos
│
├── 📂 _sql/                        # Scripts SQL
│   ├── database.sql                # Estructura de BD
│   ├── CONVERTIR_UTF8_COMPLETO.sql # Conversión UTF-8
│   ├── REPARAR_UTF8_COMPLETO.sql   # Reparación UTF-8
│   ├── corregir_utf8.sql           # Corrección UTF-8
│   └── limpiar_datos.sql           # Limpieza de datos
│
├── 📂 _scripts/                    # Scripts de utilidad
│   ├── agregar_tabla_admin.php     # Crear tabla admin
│   ├── crear_admin.php             # Crear usuario admin
│   ├── crear_coordinador_prueba.php # Crear coordinador
│   ├── diagnostico_tablas.php      # Diagnóstico de BD
│   ├── generar_vistas.php          # Generar vistas
│   ├── insertar_datos_prueba.php   # Datos de prueba
│   ├── migrar_bd.php               # Migración de BD
│   ├── reparar_caracteres.php      # Reparar caracteres
│   ├── ver_estructura.php          # Ver estructura BD
│   ├── verificar_roles.php         # Verificar roles
│   └── verificar_y_crear_bd.php    # Verificar/crear BD
│
├── 📂 _tests/                      # Pruebas del sistema
│   ├── test_conexion.php           # Probar conexión BD
│   ├── test_controladores.php      # Probar controladores
│   └── test_insertar_datos.php     # Probar inserción
│
├── 📂 _html_demos/                 # Demos HTML
│   ├── ACCESO_DASHBOARD.html       # Demo dashboard
│   ├── ACCESO_LOGIN.html           # Demo login
│   ├── DIAGNOSTICO.html            # Demo diagnóstico
│   ├── EJECUTAR_REPARACION.html    # Demo reparación
│   ├── INSTRUCCIONES_INICIO.html   # Instrucciones
│   └── PREVIEW_FAVICON.html        # Preview favicon
│
├── 📂 _database/                   # Backups de BD
├── 📂 _tools/                      # Herramientas
│
├── 📄 conexion.php                 # Conexión a base de datos
├── 📄 index.php                    # Dashboard principal
└── 📄 README.md                    # Este archivo
```

## 🚀 Características Principales

### ✅ Sistema de Autenticación
- Login seguro con sesiones PHP
- Verificación de roles (Administrador, Coordinador)
- Protección de páginas con `check_auth.php`
- Logout seguro

### ✅ Arquitectura MVC
- **Modelos**: Acceso a datos y lógica de negocio
- **Vistas**: Presentación e interfaz de usuario
- **Controladores**: Lógica de aplicación y flujo

### ✅ Sistema de Manejo de Errores Global
- Oculta warnings y notices en producción
- Registra errores en `logs/php_errors.log`
- Funciones helper para acceso seguro a arrays:
  - `safe($array, $key, $default)`
  - `safeHtml($array, $key, $default)`
  - `registroValido($registro)`
  - `e($value)`

### ✅ Calendario Interactivo
- Vista mensual de asignaciones
- Navegación por meses
- Click en días para ver asignaciones
- Modales con detalles de asignaciones
- Creación de asignaciones desde calendario

### ✅ Gestión Completa
- **Asignaciones**: Instructores, fichas, ambientes
- **Fichas**: Grupos de formación
- **Instructores**: Personal docente
- **Ambientes**: Salones y espacios
- **Programas**: Programas de formación
- **Competencias**: Competencias técnicas
- **Coordinaciones**: Áreas administrativas

### ✅ Diseño Moderno
- Colores institucionales SENA (#39A900, #007832)
- Interfaz responsive
- Iconos Lucide
- Animaciones suaves
- Diseño limpio y profesional

## 🔧 Instalación

### Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache/Nginx
- Extensión PDO de PHP

### Pasos

1. **Clonar el repositorio**
```bash
git clone [url-del-repo]
cd Gestion-sena/dashboard_sena
```

2. **Configurar base de datos**
```bash
# Importar estructura
mysql -u root -p < _sql/database.sql

# Configurar conexión en conexion.php
```

3. **Configurar permisos**
```bash
chmod 755 logs/
chmod 644 logs/.htaccess
```

4. **Crear usuario administrador**
```bash
php _scripts/crear_admin.php
```

5. **Acceder al sistema**
```
http://localhost/Gestion-sena/dashboard_sena/
```

## 📖 Uso

### Acceso al Sistema
1. Ir a `http://localhost/Gestion-sena/dashboard_sena/`
2. Iniciar sesión con credenciales de administrador
3. Navegar por los módulos del sistema

### Crear Asignación
1. Ir a "Asignaciones"
2. Click en "Nueva Asignación"
3. Llenar formulario
4. Guardar

### Ver Calendario
1. Dashboard principal muestra calendario
2. Click en días para ver asignaciones
3. Click en asignaciones para ver detalles

## 🛠️ Desarrollo

### Agregar Nuevo Módulo

1. **Crear Modelo** en `model/`
```php
class MiModel {
    public function getAll() { ... }
    public function getById($id) { ... }
    public function create($data) { ... }
    public function update($id, $data) { ... }
    public function delete($id) { ... }
}
```

2. **Crear Controlador** en `controller/`
```php
class MiController extends BaseController {
    public function index() { ... }
    public function crear() { ... }
    public function ver() { ... }
    public function editar() { ... }
    public function eliminar() { ... }
}
```

3. **Crear Vistas** en `views/mi_modulo/`
- `index.php` - Listado
- `crear.php` - Formulario creación
- `ver.php` - Detalle
- `editar.php` - Formulario edición

### Usar Funciones Helper

```php
// Acceso seguro a arrays
$nombre = safe($registro, 'nombre', 'Sin nombre');

// Acceso seguro + escape HTML
echo safeHtml($registro, 'nombre');

// Validar registro
if (registroValido($registro)) {
    // Procesar
}

// Escape HTML simple
echo e($valor);
```

## 📚 Documentación

- [Sistema de Manejo de Errores](_docs/SISTEMA_MANEJO_ERRORES.md)
- [Controladores](_docs/controller/README_CONTROLADORES.md)
- [Corrección de Formularios](_docs/CORRECCION_FORMULARIOS.md)
- [Estructura del Proyecto](_docs/ESTRUCTURA_PROYECTO.md)
- [Autenticación](auth/README_LOGIN.md)

## 🧪 Pruebas

```bash
# Probar conexión a BD
php _tests/test_conexion.php

# Probar controladores
http://localhost/Gestion-sena/dashboard_sena/_tests/test_controladores.php

# Probar inserción de datos
php _tests/test_insertar_datos.php
```

## 🔒 Seguridad

- ✅ Validación de sesiones
- ✅ Escape de HTML (prevención XSS)
- ✅ Prepared statements (prevención SQL injection)
- ✅ Validación de datos de entrada
- ✅ Logs protegidos con .htaccess
- ✅ Manejo seguro de errores

## 📝 Notas

- Los archivos con prefijo `_` son carpetas de utilidad
- Los logs se guardan en `logs/php_errors.log`
- La base de datos debe usar charset `utf8mb4_unicode_ci`
- Los colores institucionales son #39A900 y #007832

## 👥 Roles del Sistema

- **Administrador**: Acceso completo
- **Coordinador**: Gestión de su área

## 🐛 Solución de Problemas

### Error "Undefined array key"
- Verificar que se use `safe()` o `safeHtml()`
- Ver [CORRECCION_FORMULARIOS.md](_docs/CORRECCION_FORMULARIOS.md)

### Error de conexión a BD
- Verificar credenciales en `conexion.php`
- Ejecutar `_tests/test_conexion.php`

### Warnings visibles
- Verificar que `error_handler.php` esté incluido
- Revisar `logs/php_errors.log`

## 📞 Soporte

Para reportar problemas o sugerencias, contactar al equipo de desarrollo.

---

**Versión**: 2.0  
**Última actualización**: Febrero 2026  
**Desarrollado para**: SENA - Servicio Nacional de Aprendizaje
