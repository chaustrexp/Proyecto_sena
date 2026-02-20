# 🎯 Header Funcional Completo - Documentación

## Resumen

Se ha implementado un header completamente funcional con búsqueda global, notificaciones, menú de acciones rápidas y centro de ayuda.

## Funcionalidades Implementadas

### 1. 🔍 Búsqueda Global

**Características:**
- Búsqueda en tiempo real con debounce (300ms)
- Busca en: Instructores, Fichas, Programas, Ambientes y Asignaciones
- Resultados con iconos y subtítulos descriptivos
- Máximo 15 resultados
- Dropdown con resultados clickeables

**Cómo funciona:**
1. Usuario escribe en el campo de búsqueda
2. Después de 300ms sin escribir, se ejecuta la búsqueda
3. Se consulta la API `/api/search.php`
4. Se muestran resultados agrupados por tipo
5. Click en resultado navega a la página de detalle

**API Endpoint:**
```
GET /api/search.php?q=termino
```

**Respuesta:**
```json
[
  {
    "title": "Juan Pérez",
    "subtitle": "Instructor - Programación",
    "url": "/views/instructor/ver.php?id=1",
    "icon": "user",
    "type": "instructor"
  }
]
```

### 2. 🔔 Notificaciones

**Características:**
- Contador de notificaciones no leídas (badge rojo)
- Dropdown con lista de notificaciones
- Marca individual o todas como leídas
- Notificaciones con timestamp relativo
- Indicador visual de no leídas

**Cómo funciona:**
1. Click en icono de campana abre dropdown
2. Se cargan notificaciones desde `/api/notifications.php`
3. Notificaciones no leídas tienen fondo azul claro
4. Click en notificación la marca como leída
5. "Marcar todas como leídas" actualiza todas

**API Endpoints:**
```
GET /api/notifications.php
POST /api/notifications.php
  - action: "mark_read", id: 123
  - action: "mark_all_read"
```

**Tabla de Base de Datos (Opcional):**
Si deseas notificaciones reales, ejecuta:
```sql
-- Ver archivo: _database/tabla_notificaciones.sql
```

Si no existe la tabla, el sistema usa notificaciones de ejemplo.

### 3. ➕ Menú de Acciones Rápidas

**Características:**
- Botón "+" con dropdown
- Enlaces directos a formularios de creación
- 5 acciones principales:
  - Nueva Asignación
  - Nuevo Instructor
  - Nueva Ficha
  - Nuevo Programa
  - Nuevo Ambiente

**Cómo funciona:**
1. Click en botón "+" abre dropdown
2. Dropdown muestra opciones con iconos
3. Click en opción navega al formulario de creación

### 4. ❓ Centro de Ayuda

**Características:**
- Modal con documentación
- Secciones organizadas:
  - Documentación
  - Preguntas Frecuentes
  - Información de Soporte
- Enlaces a archivos de documentación
- Cierre con botón X o tecla ESC

**Cómo funciona:**
1. Click en icono "?" abre modal
2. Modal muestra contenido de ayuda
3. Enlaces abren documentación en nueva pestaña
4. Click fuera del modal o ESC lo cierra

## Archivos Creados/Modificados

### Archivos Modificados

1. **`views/layout/header.php`**
   - Agregados elementos funcionales
   - IDs para JavaScript
   - Dropdowns y modal de ayuda

2. **`views/layout/footer.php`**
   - Carga de `header-functions.js`

3. **`assets/css/styles.css`**
   - Estilos para dropdowns
   - Estilos para modal
   - Estilos para resultados de búsqueda
   - Estilos responsive

### Archivos Nuevos

4. **`assets/js/header-functions.js`**
   - Lógica de búsqueda
   - Lógica de notificaciones
   - Lógica de dropdowns
   - Lógica de modal de ayuda

5. **`api/search.php`**
   - Endpoint de búsqueda global
   - Consultas a múltiples tablas
   - Formato de resultados

6. **`api/notifications.php`**
   - Endpoint de notificaciones
   - GET: obtener notificaciones
   - POST: marcar como leídas

7. **`_database/tabla_notificaciones.sql`**
   - Estructura de tabla (opcional)
   - Triggers automáticos
   - Procedimientos almacenados

## Instalación y Configuración

### Paso 1: Limpiar Caché
```bash
# En el navegador:
Ctrl + Shift + Delete
# Seleccionar "Imágenes y archivos en caché"
# Borrar datos
```

### Paso 2: Recargar Página
```bash
Ctrl + F5  # Forzar recarga
```

### Paso 3: Verificar Archivos
Asegúrate de que existan:
- ✅ `assets/js/header-functions.js`
- ✅ `api/search.php`
- ✅ `api/notifications.php`

### Paso 4: Tabla de Notificaciones (Opcional)
Si deseas notificaciones reales:
```sql
-- Ejecutar en phpMyAdmin o MySQL:
source _database/tabla_notificaciones.sql;
```

Si no ejecutas este script, el sistema usará notificaciones de ejemplo.

## Uso

### Búsqueda
1. Escribe en el campo de búsqueda
2. Espera 300ms (debounce automático)
3. Aparecen resultados
4. Click en resultado para navegar

### Notificaciones
1. Click en icono de campana
2. Ver lista de notificaciones
3. Click en notificación para marcar como leída
4. "Marcar todas como leídas" para limpiar

### Agregar
1. Click en botón "+"
2. Seleccionar tipo de elemento
3. Navega al formulario de creación

### Ayuda
1. Click en icono "?"
2. Leer documentación
3. Click en enlaces para más info
4. ESC o X para cerrar

## Personalización

### Cambiar Resultados de Búsqueda

Edita `api/search.php`:
```php
// Agregar más tablas a la búsqueda
$stmt = $conn->prepare("
    SELECT ... FROM mi_tabla
    WHERE campo LIKE ?
");
```

### Cambiar Opciones del Menú Agregar

Edita `views/layout/header.php`:
```html
<a href="/ruta/crear.php" class="add-dropdown-item">
    <i data-lucide="icon-name"></i>
    Nuevo Elemento
</a>
```

### Cambiar Contenido de Ayuda

Edita `views/layout/header.php` en la sección del modal:
```html
<div class="help-section">
    <h3><i data-lucide="icon"></i> Título</h3>
    <ul>
        <li>Contenido...</li>
    </ul>
</div>
```

### Agregar Notificaciones Programáticamente

```php
// En cualquier archivo PHP después de una acción:
require_once __DIR__ . '/conexion.php';

$stmt = $conn->prepare("
    INSERT INTO notificaciones (IdUsuario, Titulo, Mensaje, Tipo)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param('isss', $userId, $titulo, $mensaje, $tipo);
$stmt->execute();
```

## Responsive

### Desktop (>768px)
- Todos los elementos visibles
- Búsqueda centrada con max-width 500px
- Dropdowns completos

### Tablet (768px)
- Búsqueda oculta
- Botones más compactos (32x32px)
- Texto "Cerrar Sesión" oculto

### Mobile (<480px)
- Solo elementos esenciales
- Botón de ayuda oculto
- Iconos más pequeños

## Troubleshooting

### La búsqueda no funciona
1. Verifica que `api/search.php` existe
2. Abre DevTools > Network
3. Busca la petición a `search.php`
4. Verifica errores en la respuesta

### Las notificaciones no cargan
1. Verifica que `api/notifications.php` existe
2. Si no existe la tabla, usará notificaciones de ejemplo
3. Revisa la consola del navegador (F12)

### Los dropdowns no se abren
1. Verifica que `header-functions.js` se carga
2. Abre DevTools > Console
3. Busca errores de JavaScript
4. Verifica que Lucide Icons se carga

### El modal de ayuda no aparece
1. Verifica que el modal existe en el HTML
2. Revisa estilos CSS del modal
3. Verifica que el JavaScript se ejecuta

## Testing

### Test de Búsqueda
```javascript
// En la consola del navegador:
fetch('/Gestion-sena/dashboard_sena/api/search.php?q=test')
  .then(r => r.json())
  .then(d => console.log(d));
```

### Test de Notificaciones
```javascript
// En la consola del navegador:
fetch('/Gestion-sena/dashboard_sena/api/notifications.php')
  .then(r => r.json())
  .then(d => console.log(d));
```

### Test de Dropdowns
1. Click en cada botón
2. Verificar que se abre el dropdown
3. Click fuera para cerrar
4. Verificar que se cierra

## Próximas Mejoras

### Búsqueda Avanzada
- Filtros por tipo
- Búsqueda con operadores (AND, OR)
- Historial de búsquedas
- Búsquedas guardadas

### Notificaciones
- Notificaciones en tiempo real (WebSockets)
- Sonido al recibir notificación
- Categorías de notificaciones
- Configuración de preferencias

### Acciones Rápidas
- Accesos personalizados por rol
- Acciones recientes
- Favoritos

### Centro de Ayuda
- Chat de soporte
- Videos tutoriales
- Tours guiados
- Búsqueda en documentación

## Soporte

Si tienes problemas:

1. Revisa la consola del navegador (F12)
2. Verifica que todos los archivos existen
3. Limpia el caché del navegador
4. Revisa los logs de PHP
5. Consulta esta documentación

---

**Última actualización:** 2026-02-19
**Estado:** ✅ Completamente funcional
**Versión:** 1.0.0
