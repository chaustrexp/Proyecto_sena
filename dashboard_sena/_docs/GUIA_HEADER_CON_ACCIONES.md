# 📋 Guía: Header con Acciones y Búsqueda

## Resumen de Cambios

Se han agregado nuevas funcionalidades al header del dashboard manteniendo el diseño y colores originales SENA.

## Nuevos Elementos Agregados

### 1. 🔍 Barra de Búsqueda
**Ubicación:** Centro del header, entre el título y las acciones

**Características:**
- Campo de búsqueda con icono de lupa
- Placeholder: "Buscar..."
- Fondo blanco con borde verde claro (#c8e6c9)
- Focus con borde verde SENA (#39A900) y sombra
- Ancho máximo: 400px
- Responsive: se oculta en dispositivos móviles (<768px)

**HTML:**
```html
<div class="navbar-search">
    <i data-lucide="search"></i>
    <input type="text" placeholder="Buscar..." class="search-input">
</div>
```

**CSS:**
```css
.navbar-search {
    display: flex;
    align-items: center;
    gap: 10px;
    background: white;
    border: 1px solid #c8e6c9;
    border-radius: 8px;
    padding: 6px 14px;
    flex: 1;
    max-width: 400px;
}
```

### 2. ➕ Botón Agregar
**Ubicación:** Primer botón en el grupo de acciones

**Características:**
- Icono de "plus" (más)
- Botón cuadrado 36x36px
- Fondo blanco con borde verde
- Hover: fondo azul claro y borde verde SENA
- Tooltip: "Agregar nuevo"

**Uso sugerido:**
- Crear nueva asignación
- Agregar instructor
- Crear ficha
- Agregar programa

### 3. 🔔 Notificaciones
**Ubicación:** Segundo botón en el grupo de acciones

**Características:**
- Icono de campana (bell)
- Badge rojo con contador de notificaciones
- Badge posicionado en esquina superior derecha
- Número de notificaciones: personalizable
- Tooltip: "Notificaciones"

**HTML:**
```html
<button class="navbar-btn navbar-notifications" title="Notificaciones">
    <i data-lucide="bell"></i>
    <span class="notification-badge">3</span>
</button>
```

**Personalizar contador:**
```html
<!-- Sin notificaciones: ocultar badge -->
<span class="notification-badge" style="display: none;">0</span>

<!-- Con notificaciones: mostrar número -->
<span class="notification-badge">5</span>
```

### 4. ❓ Botón de Ayuda
**Ubicación:** Tercer botón en el grupo de acciones

**Características:**
- Icono de signo de interrogación (help-circle)
- Mismo estilo que otros botones
- Tooltip: "Ayuda"
- Responsive: se oculta en móviles pequeños (<480px)

**Uso sugerido:**
- Abrir documentación
- Mostrar guía de usuario
- FAQ del sistema
- Tutoriales

## Estructura del Header

```
┌─────────────────────────────────────────────────────────────────┐
│ [Título] [────── Búsqueda ──────] [+] [🔔³] [?] [Cerrar Sesión] │
└─────────────────────────────────────────────────────────────────┘
```

### Distribución con Flexbox

```css
.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
```

**Elementos:**
1. `.navbar-title` - flex: 0 1 auto (no crece, puede encogerse)
2. `.navbar-search` - flex: 1 (crece para llenar espacio)
3. `.navbar-actions` - flex-shrink: 0 (no se encoge)
4. `.navbar-user` - flex-shrink: 0, margin-left: auto (pegado a la derecha)

## Estilos CSS

### Colores SENA Utilizados

```css
/* Verde principal SENA */
--verde-principal: #39A900;

/* Verde claro para bordes */
--borde-verde: #c8e6c9;

/* Fondo del header */
--fondo-header: #e8f5e9;

/* Rojo para notificaciones */
--rojo-notificacion: #ef4444;
```

### Botones de Acción

```css
.navbar-btn {
    width: 36px;
    height: 36px;
    background: white;
    border: 1px solid #c8e6c9;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.navbar-btn:hover {
    background: #f0f9ff;
    border-color: #39A900;
}

.navbar-btn i {
    width: 20px;
    height: 20px;
    color: #39A900;
}
```

### Badge de Notificaciones

```css
.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
}
```

## Responsive Design

### Desktop (>768px)
- Todos los elementos visibles
- Búsqueda con ancho completo (max 400px)
- Botones 36x36px
- Texto "Cerrar Sesión" visible

### Tablet (768px)
```css
@media (max-width: 768px) {
    .navbar-search {
        display: none; /* Ocultar búsqueda */
    }
    
    .navbar-actions {
        gap: 6px; /* Reducir espacio entre botones */
    }
    
    .navbar-btn {
        width: 32px;
        height: 32px;
    }
    
    .btn-logout span {
        display: none; /* Solo icono de logout */
    }
}
```

### Mobile (<480px)
```css
@media (max-width: 480px) {
    .navbar-actions .navbar-btn:last-child {
        display: none; /* Ocultar botón de ayuda */
    }
}
```

## Implementación de Funcionalidades

### 1. Búsqueda en Tiempo Real

**JavaScript sugerido:**
```javascript
const searchInput = document.querySelector('.search-input');

searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    
    // Realizar búsqueda AJAX
    fetch(`/api/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            // Mostrar resultados
            displaySearchResults(data);
        });
});
```

### 2. Dropdown de Notificaciones

**HTML sugerido:**
```html
<button class="navbar-btn navbar-notifications" id="notificationsBtn">
    <i data-lucide="bell"></i>
    <span class="notification-badge">3</span>
</button>

<div class="notifications-dropdown" id="notificationsDropdown">
    <div class="notification-item">
        <strong>Nueva asignación</strong>
        <p>Se ha creado una nueva asignación para la ficha 2024-01</p>
        <span class="notification-time">Hace 5 minutos</span>
    </div>
    <!-- Más notificaciones -->
</div>
```

**JavaScript:**
```javascript
const notificationsBtn = document.getElementById('notificationsBtn');
const notificationsDropdown = document.getElementById('notificationsDropdown');

notificationsBtn.addEventListener('click', function() {
    notificationsDropdown.classList.toggle('active');
});
```

### 3. Modal de Ayuda

**JavaScript sugerido:**
```javascript
const helpBtn = document.querySelector('.navbar-btn[title="Ayuda"]');

helpBtn.addEventListener('click', function() {
    // Abrir modal de ayuda
    openHelpModal();
});

function openHelpModal() {
    // Crear y mostrar modal con documentación
    const modal = document.createElement('div');
    modal.className = 'help-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <h2>Centro de Ayuda</h2>
            <ul>
                <li><a href="/docs/manual-usuario.pdf">Manual de Usuario</a></li>
                <li><a href="/docs/faq.html">Preguntas Frecuentes</a></li>
                <li><a href="/docs/tutoriales.html">Tutoriales</a></li>
            </ul>
        </div>
    `;
    document.body.appendChild(modal);
}
```

### 4. Botón Agregar con Menú

**HTML sugerido:**
```html
<button class="navbar-btn" id="addBtn" title="Agregar nuevo">
    <i data-lucide="plus"></i>
</button>

<div class="add-dropdown" id="addDropdown">
    <a href="/asignacion/crear.php" class="dropdown-item">
        <i data-lucide="calendar"></i>
        Nueva Asignación
    </a>
    <a href="/instructor/crear.php" class="dropdown-item">
        <i data-lucide="user-plus"></i>
        Nuevo Instructor
    </a>
    <a href="/ficha/crear.php" class="dropdown-item">
        <i data-lucide="file-plus"></i>
        Nueva Ficha
    </a>
</div>
```

## Archivos Modificados

### 1. `views/layout/header.php`
- Agregada barra de búsqueda
- Agregado grupo de acciones con 3 botones
- Mantenida estructura original del header

### 2. `assets/css/styles.css`
- Agregados estilos para `.navbar-search`
- Agregados estilos para `.navbar-actions`
- Agregados estilos para `.navbar-btn`
- Agregados estilos para `.notification-badge`
- Actualizados media queries responsive

## Testing

### Preview HTML
**Archivo:** `_html_demos/PREVIEW_HEADER_CON_ACCIONES.html`

Abre este archivo en el navegador para ver el header con todas las funcionalidades.

### Verificación en Dashboard Real

1. Limpia el caché del navegador (Ctrl + Shift + Delete)
2. Recarga con Ctrl + F5
3. Verifica que aparezcan:
   - ✓ Barra de búsqueda en el centro
   - ✓ Botón "+" (agregar)
   - ✓ Botón de notificaciones con badge "3"
   - ✓ Botón de ayuda "?"
   - ✓ Botón "Cerrar Sesión" a la derecha

### Pruebas Responsive

1. Abre DevTools (F12)
2. Activa el modo responsive (Ctrl + Shift + M)
3. Prueba diferentes tamaños:
   - 1920px (Desktop): todos los elementos visibles
   - 768px (Tablet): búsqueda oculta
   - 480px (Mobile): ayuda oculta, solo iconos

## Personalización

### Cambiar Número de Notificaciones

En `header.php`:
```php
<?php
// Obtener número de notificaciones desde la base de datos
$notificationCount = getUnreadNotifications($_SESSION['usuario_id']);
?>

<span class="notification-badge" <?php echo $notificationCount == 0 ? 'style="display:none;"' : ''; ?>>
    <?php echo $notificationCount; ?>
</span>
```

### Cambiar Placeholder de Búsqueda

```html
<input type="text" placeholder="Buscar instructores, fichas..." class="search-input">
```

### Agregar Más Botones

```html
<div class="navbar-actions">
    <!-- Botones existentes -->
    
    <!-- Nuevo botón -->
    <button class="navbar-btn" title="Configuración">
        <i data-lucide="settings"></i>
    </button>
</div>
```

## Próximos Pasos

1. **Implementar búsqueda funcional**
   - Crear endpoint de búsqueda en PHP
   - Agregar JavaScript para búsqueda en tiempo real
   - Mostrar resultados en dropdown

2. **Crear sistema de notificaciones**
   - Tabla de notificaciones en la base de datos
   - Endpoint para obtener notificaciones
   - Dropdown con lista de notificaciones
   - Marcar como leídas

3. **Agregar modal de ayuda**
   - Crear contenido de ayuda
   - Implementar modal con documentación
   - Enlaces a manuales y tutoriales

4. **Menú del botón agregar**
   - Dropdown con opciones de creación
   - Enlaces a formularios de creación
   - Permisos según rol de usuario

## Soporte

Si tienes problemas con el header:

1. Verifica que los archivos CSS se carguen correctamente
2. Limpia el caché del navegador
3. Revisa la consola del navegador (F12) para errores
4. Abre el preview HTML para verificar que los estilos funcionen

---

**Última actualización:** 2026-02-19
**Estado:** ✅ Implementado y listo para uso
