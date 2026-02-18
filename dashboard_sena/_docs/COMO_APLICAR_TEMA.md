# Cómo Aplicar el Tema Mejorado SENA

## 📋 Descripción

El archivo `theme-enhanced.css` contiene mejoras visuales encapsuladas que **NO rompen** el diseño actual del dashboard.

## ✅ Ventajas

- ✨ Mejoras visuales sutiles y elegantes
- 🔒 No modifica HTML ni PHP existente
- 🎯 Encapsulado con clase `.sena-enhanced-theme`
- 🚀 Fácil de activar/desactivar
- 📱 Mantiene responsive
- 🎨 No sobrescribe estilos globales

## 🚀 Instalación

### Paso 1: Incluir el CSS

Agrega esta línea en el `<head>` de tu archivo `header.php` (después del CSS principal):

```php
<link rel="stylesheet" href="/Gestion-sena/dashboard_sena/assets/css/theme-enhanced.css">
```

### Paso 2: Aplicar la Clase

Tienes 3 opciones:

#### Opción A: Aplicar a todo el dashboard (Recomendado)

En `header.php`, agrega la clase al `<body>`:

```php
<body class="sena-enhanced-theme">
```

#### Opción B: Solo al contenido principal

En `header.php`, agrega la clase al contenedor principal:

```php
<div class="main-content sena-enhanced-theme">
```

#### Opción C: Solo a secciones específicas

Aplica la clase solo donde quieras las mejoras:

```php
<div class="stats-grid sena-enhanced-theme">
    <!-- tus stats -->
</div>

<div class="table-section sena-enhanced-theme">
    <!-- tu tabla -->
</div>
```

## 🎨 Características Incluidas

### Mejoras Visuales

- ✨ Cards con efecto glass y hover mejorado
- 📊 Tablas con hover suave y transiciones
- 🔘 Botones con sombras y animaciones
- 📝 Formularios con focus mejorado
- 🏷️ Badges con backdrop blur
- 🎯 Iconos con sombras sutiles
- 📱 Scrollbar personalizado

### Animaciones

- `fade-in`: Aparición suave
- `slide-in`: Deslizamiento lateral
- Transiciones suaves en hover

### Modo Oscuro (Opcional)

Para activar modo oscuro, agrega la clase adicional:

```php
<body class="sena-enhanced-theme dark-mode">
```

## 📝 Ejemplo Completo

### En `views/layout/header.php`:

```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard SENA'; ?></title>
    
    <!-- CSS Principal (existente) -->
    <link rel="stylesheet" href="/Gestion-sena/dashboard_sena/assets/css/styles.css">
    
    <!-- NUEVO: Tema Mejorado -->
    <link rel="stylesheet" href="/Gestion-sena/dashboard_sena/assets/css/theme-enhanced.css">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="sena-enhanced-theme">
    <!-- El resto de tu código -->
```

## 🔧 Personalización

Si quieres ajustar colores o efectos, edita las variables en `theme-enhanced.css`:

```css
.sena-enhanced-theme {
    --theme-primary-green: #39A900;  /* Cambia aquí */
    --theme-shadow: 0 4px 6px...;    /* Ajusta sombras */
}
```

## ⚠️ Importante

- **NO modifica** el CSS original (`styles.css`)
- **NO requiere** cambios en HTML o PHP
- **NO rompe** el layout actual
- **Fácil de desactivar**: solo quita la clase o el link al CSS

## 🧪 Prueba

1. Agrega el link al CSS en `header.php`
2. Agrega la clase `sena-enhanced-theme` al `<body>`
3. Recarga la página (Ctrl + F5)
4. Verás mejoras sutiles sin romper nada

## 🔄 Desactivar

Para desactivar temporalmente:

```php
<!-- Comenta el link -->
<!-- <link rel="stylesheet" href="...theme-enhanced.css"> -->
```

O quita la clase:

```php
<body>  <!-- Sin clase -->
```

## 📞 Soporte

Si algo no se ve bien:

1. Verifica que el link al CSS esté correcto
2. Asegúrate de que la clase esté aplicada
3. Limpia caché del navegador (Ctrl + F5)
4. Revisa la consola del navegador (F12)

## ✨ Resultado

El dashboard se verá más moderno y pulido, con:

- Transiciones suaves
- Efectos hover elegantes
- Sombras sutiles
- Mejor feedback visual
- Sin romper nada existente

---

**Creado para**: Dashboard SENA  
**Versión**: 1.0  
**Compatible con**: Diseño actual sin modificaciones
