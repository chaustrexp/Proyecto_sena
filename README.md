# 🎓 Sistema de Gestión SENA

Sistema web de gestión académica desarrollado con arquitectura MVC para el Servicio Nacional de Aprendizaje (SENA).

![SENA Logo](assets/images/sena-logo.png)

## 📋 Descripción

Sistema completo de gestión académica que permite administrar programas de formación, fichas, instructores, ambientes y asignaciones del SENA. Desarrollado con PHP, MySQL y diseño moderno responsive.

## ✨ Características

- 🔐 **Sistema de autenticación** con roles de usuario
- 📊 **Dashboard interactivo** con estadísticas en tiempo real
- 👥 **Gestión de instructores** y perfiles
- 📚 **Administración de programas** de formación
- 📝 **Control de fichas** y grupos
- 🏢 **Gestión de ambientes** y espacios
- 📅 **Asignaciones** de instructores a fichas
- 🎯 **Competencias** y títulos de programas
- 🏛️ **Centros de formación** y sedes
- 📱 **Diseño responsive** para todos los dispositivos

## 🎨 Diseño

- **Paleta de colores institucional SENA**
  - Verde Principal: `#39A900`
  - Verde Secundario: `#007832`
- **UI moderna y limpia** con efectos glassmorphism
- **Sidebar verde** con navegación intuitiva
- **Tarjetas de estadísticas** con animaciones suaves
- **Tablas modernas** con hover effects
- **Fondo institucional** con imagen de la fachada del SENA

## 🛠️ Tecnologías

- **Backend**: PHP 8.0+
- **Base de datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Iconos**: Lucide Icons
- **Arquitectura**: MVC (Modelo-Vista-Controlador)
- **Servidor**: Apache (XAMPP)

## 📦 Requisitos

- XAMPP 8.0 o superior
- PHP 8.0+
- MySQL 5.7+
- Navegador web moderno (Chrome, Firefox, Edge)

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/chaustrexp/mvc_proyecto_definitivo.git
cd mvc_proyecto_definitivo
```

### 2. Mover a XAMPP

Copiar la carpeta del proyecto a `C:\xampp\htdocs\Gestion-sena`

### 3. Configurar la base de datos

1. Abrir phpMyAdmin: `http://localhost/phpmyadmin`
2. Crear base de datos: `dashboard_sena`
3. Importar: `_database/database.sql`

### 4. Configurar conexión

Editar `dashboard_sena/conexion.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'dashboard_sena');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 5. Iniciar servicios

Ejecutar `_scripts/ABRIR_DASHBOARD.bat` o iniciar Apache y MySQL desde XAMPP.

### 6. Acceder al sistema

Abrir navegador: `http://localhost/Gestion-sena/dashboard_sena/`

## 👤 Credenciales de prueba

```
Email: admin@sena.edu.co
Contraseña: admin123
```

## 📁 Estructura del proyecto

```
Gestion-sena/
├── _database/          # Scripts SQL
├── _docs/              # Documentación
├── _scripts/           # Scripts de ejecución
├── _setup/             # Configuración inicial
├── _utils/             # Utilidades
├── assets/             # Recursos estáticos
│   ├── css/           # Hojas de estilo
│   └── images/        # Imágenes
├── auth/               # Sistema de autenticación
├── dashboard_sena/     # Aplicación principal
│   ├── assets/        # Recursos del dashboard
│   ├── auth/          # Autenticación
│   ├── model/         # Modelos MVC
│   ├── views/         # Vistas MVC
│   │   ├── layout/   # Plantillas
│   │   ├── programa/ # CRUD Programas
│   │   ├── ficha/    # CRUD Fichas
│   │   ├── instructor/ # CRUD Instructores
│   │   └── ...       # Otros módulos
│   ├── conexion.php   # Conexión BD
│   └── index.php      # Dashboard principal
└── index.php           # Redirección
```

## 🔧 Módulos del sistema

### Académico
- **Programas**: Gestión de programas de formación
- **Fichas**: Control de grupos y fichas
- **Competencias**: Administración de competencias
- **Competencia-Programa**: Relación entre competencias y programas
- **Título Programa**: Títulos otorgados

### Recursos
- **Instructores**: Gestión de personal docente
- **Ambientes**: Control de espacios físicos
- **Asignaciones**: Asignación de instructores a fichas
- **Detalle Asignación**: Información detallada de asignaciones

### Infraestructura
- **Centro Formación**: Gestión de centros
- **Sedes**: Administración de sedes
- **Coordinación**: Control de coordinaciones

## 🎯 Funcionalidades principales

### Dashboard
- Estadísticas en tiempo real
- Últimas asignaciones
- Acceso rápido a módulos
- Perfil de usuario

### CRUD Completo
Cada módulo incluye:
- ✅ Crear registros
- 📖 Leer/Listar
- ✏️ Actualizar
- 🗑️ Eliminar
- 👁️ Ver detalles

### Sistema de autenticación
- Login seguro con contraseñas hasheadas
- Sesiones de usuario
- Protección de rutas
- Logout seguro

## 🔒 Seguridad

- Contraseñas encriptadas con `password_hash()`
- Protección contra SQL Injection con PDO
- Validación de sesiones
- Sanitización de datos de entrada

## 📱 Responsive Design

El sistema es completamente responsive y se adapta a:
- 💻 Desktop (1920px+)
- 💻 Laptop (1366px)
- 📱 Tablet (768px)
- 📱 Mobile (320px+)

## 🐛 Solución de problemas

### Error de conexión a BD
```bash
# Verificar que MySQL esté corriendo
# Verificar credenciales en conexion.php
```

### Página en blanco
```bash
# Activar errores en PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Problemas de UTF-8
```bash
# Ejecutar script de reparación
php _utils/REPARAR_UTF8_DEFINITIVO.php
```

## 📝 Scripts útiles

- `_scripts/ABRIR_DASHBOARD.bat` - Inicia el dashboard
- `_scripts/SETUP_DB.bat` - Configura la base de datos
- `_scripts/crear_vistas_restantes.bat` - Crea vistas SQL

## 🤝 Contribuir

1. Fork el proyecto
2. Crear rama feature (`git checkout -b feature/NuevaCaracteristica`)
3. Commit cambios (`git commit -m 'Agregar nueva característica'`)
4. Push a la rama (`git push origin feature/NuevaCaracteristica`)
5. Abrir Pull Request

## 📄 Licencia

Este proyecto es de uso educativo para el SENA.

## 👨‍💻 Autor

**SENA - Servicio Nacional de Aprendizaje**

## 📞 Soporte

Para soporte o consultas, contactar al administrador del sistema.

---

⭐ Si este proyecto te fue útil, dale una estrella en GitHub!

🎓 Desarrollado con ❤️ para el SENA
