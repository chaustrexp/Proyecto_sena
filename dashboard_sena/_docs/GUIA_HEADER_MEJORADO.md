# 🎨 Guía del Header Mejorado

## 📋 Nuevas Características Implementadas

### 1. Logo del SENA
- Logo institucional visible en el header
- Filtro para convertirlo a blanco sobre fondo verde

### 2. Barra de Búsqueda Global
- Búsqueda en tiempo real
- Resultados desplegables
- Busca en fichas, instructores, ambientes, etc.

### 3. Notificaciones
- Icono de campana con badge de contador
- Menú desplegable con notificaciones recientes
- Opción "Marcar todas como leídas"
- Indicador visual de notificaciones no leídas

### 4. Accesos Rápidos
- Botón "+" para acciones rápidas
- Crear nueva asignación
- Crear nuevo instructor
- Crear nueva ficha
- Crear nuevo ambiente

### 5. Ayuda
- Botón de ayuda que abre la documentación
- Acceso rápido a guías del sistema

### 6. Menú de Usuario Mejorado
- Avatar del usuario
- Nombre y rol
- Menú desplegable con:
  - Mi Perfil
  - Configuración
  - Estado del Sistema
  - Cerrar Sesión

### 7. Breadcrumbs (Migas de Pan)
- Navegación jerárquica
- Muestra la ruta actual
- Enlaces clicables para volver atrás

---

## 🎯 Cómo Usar las Nuevas Características

### Agregar Breadcrumbs en una Vista

En tu controlador o vista, define el array `$breadcrumbs`:

```php
<?php
// En el controlador o al inicio de la vista
$breadcrumbs = [
    ['label' => 'Asignaciones', 'url' => '/Gestion-sena/dashboard_sena/asignacion'],
    ['label' => 'Crear Nueva Asignación', 'url' => '']
];

$pageTitle = 'Crear Nueva Asignación';
?>
```

**Ejemplo completo en una vista:**

```php
<?php
require_once __DIR__ . '/../../auth/check_auth.php';

// Definir breadcrumbs
$breadcrumbs = [
    ['label' => 'Instructores', 'url' => '/Gestion-sena/dashboard_sena/instructor'],
    ['label' => 'Ver Instructor', 'url' => '']
];

$pageTitle = 'Detalle del Instructor';

// Incluir header (breadcrumbs se mostrarán automáticamente)
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<!-- Tu contenido aquí -->

<?php include __DIR__ . '/../layout/footer.php'; ?>
```

### Personalizar Información del Usuario

El header usa variables de sesión para mostrar información del usuario:

```php
$_SESSION['usuario_nombre']  // Nombre del usuario
$_SESSION['usuario_rol']     // Rol (Administrador, Coordinador, etc.)
$_SESSION['usuario_email']   // Email del usuario
```

Asegúrate de establecer estas variables al hacer login:

```php
// En auth/login.php después de validar credenciales
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
$_SESSION['usuario_rol'] = $usuario['rol'];
$_SESSION['usuario_email'] = $usuario['email'];
```

---

## 🔧 Personalización

### Cambiar el Logo

Reemplaza el archivo:
```
dashboard_sena/assets/images/sena-logo.png
```

### Cambiar la Foto de Perfil

Reemplaza el archivo:
```
dashboard_sena/assets/images/foto-perfil.jpg
```

O usa una foto dinámica desde la base de datos:

```php
<img src="<?php echo $_SESSION['usuario_foto'] ?? '/Gestion-sena/dashboard_sena/assets/images/foto-perfil.jpg'; ?>" 
     alt="Usuario" class="user-avatar">
```

### Modificar Notificaciones

Las notificaciones actualmente son estáticas. Para hacerlas dinámicas:

1. Crea una tabla `notificaciones` en la base de datos
2. Crea un modelo `NotificacionModel.php`
3. Modifica el header para cargar notificaciones reales:

```php
<?php
// En header.php
require_once __DIR__ . '/../../model/NotificacionModel.php';
$notificacionModel = new NotificacionModel();
$notificaciones = $notificacionModel->getByUsuario($_SESSION['usuario_id']);
$notificacionesNoLeidas = $notificacionModel->countNoLeidas($_SESSION['usuario_id']);
?>

<!-- En el HTML -->
<span class="badge"><?php echo $notificacionesNoLeidas; ?></span>

<!-- En el menú -->
<?php foreach ($notificaciones as $notif): ?>
<div class="notification-item <?php echo $notif['leida'] ? '' : 'unread'; ?>">
    <i data-lucide="<?php echo $notif['icono']; ?>" class="notification-icon"></i>
    <div class="notification-content">
        <p class="notification-title"><?php echo $notif['titulo']; ?></p>
        <p class="notification-time"><?php echo $notif['tiempo']; ?></p>
    </div>
</div>
<?php endforeach; ?>
```

### Implementar Búsqueda Real

Actualmente la búsqueda muestra resultados de ejemplo. Para implementar búsqueda real:

1. Crea un endpoint AJAX: `views/search/global_search.php`

```php
<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../conexion.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$db = Database::getInstance()->getConnection();

// Buscar en fichas
$stmt = $db->prepare("SELECT fich_id as id, fich_numero as nombre, 'Ficha' as tipo 
                      FROM FICHA WHERE fich_numero LIKE ? LIMIT 5");
$stmt->execute(["%$query%"]);
$fichas = $stmt->fetchAll();

// Buscar en instructores
$stmt = $db->prepare("SELECT inst_id as id, CONCAT(inst_nombres, ' ', inst_apellidos) as nombre, 'Instructor' as tipo 
                      FROM INSTRUCTOR WHERE inst_nombres LIKE ? OR inst_apellidos LIKE ? LIMIT 5");
$stmt->execute(["%$query%", "%$query%"]);
$instructores = $stmt->fetchAll();

// Buscar en ambientes
$stmt = $db->prepare("SELECT amb_id as id, amb_nombre as nombre, 'Ambiente' as tipo 
                      FROM AMBIENTE WHERE amb_nombre LIKE ? LIMIT 5");
$stmt->execute(["%$query%"]);
$ambientes = $stmt->fetchAll();

$resultados = array_merge($fichas, $instructores, $ambientes);

echo json_encode($resultados);
?>
```

2. Modifica el JavaScript en `footer.php`:

```javascript
function performSearch(query) {
    fetch(`/Gestion-sena/dashboard_sena/views/search/global_search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                searchResults.innerHTML = data.map(item => {
                    let url = '';
                    if (item.tipo === 'Ficha') url = `/Gestion-sena/dashboard_sena/ficha/show/${item.id}`;
                    if (item.tipo === 'Instructor') url = `/Gestion-sena/dashboard_sena/instructor/show/${item.id}`;
                    if (item.tipo === 'Ambiente') url = `/Gestion-sena/dashboard_sena/ambiente/show/${item.id}`;
                    
                    return `
                        <a href="${url}" class="search-result-item">
                            <strong>${item.tipo}:</strong> ${item.nombre}
                        </a>
                    `;
                }).join('');
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = '<div class="search-result-item">No se encontraron resultados</div>';
                searchResults.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
        });
}
```

---

## 🎨 Estilos Personalizables

### Colores del Header

Puedes cambiar los colores en `theme-enhanced.css`:

```css
/* Cambiar color de fondo del navbar */
.navbar {
    background: linear-gradient(135deg, #39A900 0%, #007832 100%);
}

/* Cambiar color de hover en botones */
.icon-btn:hover {
    background: rgba(255, 255, 255, 0.25);
}

/* Cambiar color de enlaces */
.breadcrumb-item:hover {
    color: #39A900;
}
```

### Tamaño del Logo

```css
.navbar-logo {
    height: 48px; /* Ajusta según necesites */
}
```

### Ancho de la Búsqueda

```css
.navbar-search {
    max-width: 500px; /* Ajusta según necesites */
}
```

---

## 📱 Responsive

El header es completamente responsive:

- **Desktop (>1024px)**: Muestra todos los elementos
- **Tablet (768px-1024px)**: Oculta información del usuario, búsqueda en línea completa
- **Mobile (<768px)**: Logo más pequeño, sin subtítulo, botones compactos

---

## 🔍 Elementos del Header

### Estructura HTML

```
navbar
├── navbar-brand
│   ├── navbar-logo (imagen)
│   └── navbar-title
│       ├── h1 (título)
│       └── navbar-subtitle
├── navbar-search
│   └── search-container
│       ├── icon (lupa)
│       ├── search-input
│       └── search-results
└── navbar-actions
    ├── notificationsBtn
    │   └── notificationsMenu
    ├── quickActionsBtn
    │   └── quickActionsMenu
    ├── helpBtn
    └── userMenuBtn
        └── userMenu
```

---

## ✨ Características Adicionales Sugeridas

### 1. Modo Oscuro
Agrega un botón para cambiar entre modo claro y oscuro:

```html
<div class="navbar-item">
    <button class="icon-btn" title="Modo Oscuro" onclick="toggleDarkMode()">
        <i data-lucide="moon"></i>
    </button>
</div>
```

### 2. Selector de Idioma
Para sistemas multiidioma:

```html
<div class="navbar-item">
    <button class="icon-btn" title="Idioma">
        <i data-lucide="globe"></i>
    </button>
</div>
```

### 3. Pantalla Completa
Botón para activar modo pantalla completa:

```html
<div class="navbar-item">
    <button class="icon-btn" title="Pantalla Completa" onclick="toggleFullscreen()">
        <i data-lucide="maximize"></i>
    </button>
</div>
```

### 4. Atajos de Teclado
Implementa atajos de teclado:

```javascript
// Ctrl + K para abrir búsqueda
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        document.getElementById('globalSearch').focus();
    }
});
```

---

## 🐛 Solución de Problemas

### Los menús no se abren
Verifica que Lucide Icons esté cargado:
```html
<script src="https://unpkg.com/lucide@latest"></script>
```

### Los estilos no se aplican
Limpia la caché del navegador (Ctrl + F5) o verifica que el CSS esté cargado:
```html
<link rel="stylesheet" href="/Gestion-sena/dashboard_sena/assets/css/theme-enhanced.css?v=<?php echo time(); ?>">
```

### El logo no aparece
Verifica que la ruta sea correcta:
```
dashboard_sena/assets/images/sena-logo.png
```

### La búsqueda no funciona
Verifica que el JavaScript esté cargado y que no haya errores en la consola (F12).

---

## 📚 Recursos

- **Lucide Icons:** https://lucide.dev/icons/
- **CSS Flexbox:** https://css-tricks.com/snippets/css/a-guide-to-flexbox/
- **JavaScript Events:** https://developer.mozilla.org/es/docs/Web/Events

---

## 🎉 Resultado Final

Con estas mejoras, tu header ahora tiene:

✅ Logo institucional  
✅ Búsqueda global en tiempo real  
✅ Sistema de notificaciones  
✅ Accesos rápidos a acciones comunes  
✅ Menú de usuario completo  
✅ Breadcrumbs para navegación  
✅ Diseño responsive  
✅ Animaciones suaves  
✅ Colores institucionales SENA  

---

**Última Actualización:** 19 de Febrero de 2026  
**Versión:** 2.0
