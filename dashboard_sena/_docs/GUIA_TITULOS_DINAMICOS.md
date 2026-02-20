# 📖 Guía de Títulos Dinámicos del Header

## 🎯 Cómo Funciona

El sistema de títulos dinámicos detecta automáticamente la sección actual y actualiza el header. Funciona de dos formas:

### 1. Detección Automática (PHP)
El archivo `helpers/page_titles.php` detecta automáticamente:
- El módulo actual (asignacion, ficha, instructor, etc.)
- La acción actual (crear, editar, ver, index)
- Genera el título apropiado

### 2. Título Manual (Opcional)
Puedes definir un título personalizado en cualquier vista:

```php
<?php
$pageTitle = "Mi Título Personalizado";
include __DIR__ . '/../layout/header.php';
?>
```

## 📋 Títulos por Sección

### Dashboard Principal
- **URL:** `/dashboard_sena/index.php`
- **Título:** "Dashboard Principal"

### Asignaciones
- **Listar:** "Gestión de Asignaciones"
- **Crear:** "Crear Asignaciones"
- **Editar:** "Editar Asignaciones"
- **Ver:** "Ver Detalle de Asignaciones"

### Fichas
- **Listar:** "Gestión de Fichas"
- **Crear:** "Crear Fichas"
- **Editar:** "Editar Fichas"
- **Ver:** "Ver Detalle de Fichas"

### Instructores
- **Listar:** "Gestión de Instructores"
- **Crear:** "Crear Instructores"
- **Editar:** "Editar Instructores"
- **Ver:** "Ver Detalle de Instructores"

### Ambientes
- **Listar:** "Gestión de Ambientes"
- **Crear:** "Crear Ambientes"
- **Editar:** "Editar Ambientes"
- **Ver:** "Ver Detalle de Ambientes"

### Programas
- **Listar:** "Gestión de Programas"
- **Crear:** "Crear Programas"
- **Editar:** "Editar Programas"
- **Ver:** "Ver Detalle de Programas"

### Competencias
- **Listar:** "Gestión de Competencias"
- **Crear:** "Crear Competencias"
- **Editar:** "Editar Competencias"
- **Ver:** "Ver Detalle de Competencias"

## 🔧 Funciones Disponibles

### `getPageTitle()`
Retorna el título de la página actual.

```php
$titulo = getPageTitle();
// Retorna: "Gestión de Asignaciones"
```

### `getDocumentTitle()`
Retorna el título para el tag `<title>` del documento.

```php
$tituloDocumento = getDocumentTitle();
// Retorna: "Gestión de Asignaciones - Dashboard SENA"
```

### `getAutoBreadcrumbs()`
Genera breadcrumbs automáticamente según la ruta actual.

```php
$breadcrumbs = getAutoBreadcrumbs();
// Retorna: [
//     ['label' => 'Asignaciones', 'url' => '/...'],
//     ['label' => 'Crear Nuevo', 'url' => '']
// ]
```

## 📝 Ejemplos de Uso

### Ejemplo 1: Usar título automático
```php
<?php
// No definir $pageTitle, se detectará automáticamente
include __DIR__ . '/../layout/header.php';
?>
```

### Ejemplo 2: Título personalizado
```php
<?php
$pageTitle = "Reporte de Asignaciones Mensuales";
include __DIR__ . '/../layout/header.php';
?>
```

### Ejemplo 3: Con breadcrumbs personalizados
```php
<?php
$pageTitle = "Editar Asignación";
$breadcrumbs = [
    ['label' => 'Asignaciones', 'url' => '/Gestion-sena/dashboard_sena/views/asignacion/index.php'],
    ['label' => 'Editar', 'url' => '']
];
include __DIR__ . '/../layout/header.php';
?>
```

## 🎨 Personalización

### Agregar un nuevo módulo

Edita `helpers/page_titles.php` y agrega tu módulo:

```php
$moduleTitles = [
    // ... módulos existentes
    'mi_modulo' => 'Mi Módulo Personalizado'
];
```

### Cambiar el formato de los títulos

Modifica la función `getPageTitle()` en `helpers/page_titles.php`:

```php
case 'crear.php':
    return 'Nuevo ' . $baseTitle; // En lugar de "Crear"
```

## ✅ Ventajas del Sistema

1. **Automático** - No necesitas definir títulos en cada vista
2. **Consistente** - Todos los títulos siguen el mismo formato
3. **Flexible** - Puedes sobrescribir con títulos personalizados
4. **Mantenible** - Cambios centralizados en un solo archivo
5. **SEO-Friendly** - Actualiza también el `<title>` del documento

## 🐛 Solución de Problemas

### El título no cambia
1. Verifica que el archivo `helpers/page_titles.php` exista
2. Asegúrate de que la ruta del módulo esté en el array `$moduleTitles`
3. Revisa que no haya un `$pageTitle` definido manualmente

### Título incorrecto
1. Verifica la estructura de la URL
2. Asegúrate de que el nombre del módulo coincida con la carpeta
3. Revisa el nombre del archivo (crear.php, editar.php, ver.php, index.php)

### Error al cargar el header
1. Verifica la ruta del `require_once` en header.php
2. Asegúrate de que `helpers/page_titles.php` tenga permisos de lectura

---

**Última Actualización:** 19 de Febrero de 2026  
**Versión:** 2.0
