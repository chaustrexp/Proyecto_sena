# Sistema Global de Manejo de Errores

## Descripción
Sistema implementado para evitar warnings, notices y errores "Undefined array key" en todo el proyecto PHP.

## Componentes Implementados

### 1. Configuración Global de Errores
**Archivo:** `dashboard_sena/config/error_handler.php`

Características:
- `error_reporting(E_ALL)` - Reporta todos los errores
- `display_errors = 0` - No muestra errores en pantalla
- `log_errors = 1` - Registra errores en archivo log
- Manejadores personalizados de errores, excepciones y errores fatales
- Registro automático en `logs/php_errors.log`

### 2. Funciones Helper Globales
**Archivo:** `dashboard_sena/helpers/functions.php`

Funciones disponibles:

#### `safe($array, $key, $default = 'No disponible')`
Acceso seguro a arrays sin warnings.
```php
// Antes (genera warning si no existe):
echo $registro['nombre'];

// Ahora (seguro):
echo safe($registro, 'nombre');
echo safe($registro, 'nombre', 'Sin nombre'); // Con valor por defecto personalizado
```

#### `e($value)`
Escape seguro de HTML para prevenir XSS.
```php
echo e($valor); // Escapa HTML y maneja valores null
```

#### `safeHtml($array, $key, $default = 'No disponible')`
Combina acceso seguro + escape HTML.
```php
// Acceso seguro + protección XSS en una sola función
echo safeHtml($registro, 'nombre');
```

#### `registroValido($registro)`
Verifica si un registro existe y tiene datos.
```php
if (registroValido($registro)) {
    // Mostrar datos
} else {
    // Mostrar mensaje de "no encontrado"
}
```

### 3. Integración Global
**Archivo:** `dashboard_sena/auth/check_auth.php`

El sistema se carga automáticamente en TODAS las páginas protegidas mediante:
```php
require_once __DIR__ . '/../config/error_handler.php';
require_once __DIR__ . '/../helpers/functions.php';
```

### 4. Página de Error 500
**Archivo:** `dashboard_sena/views/errors/500.php`

Página amigable que se muestra cuando ocurre un error fatal.
- Diseño con colores institucionales SENA
- Mensaje claro para el usuario
- Botón para volver al inicio

### 5. Directorio de Logs
**Ubicación:** `dashboard_sena/logs/`

- Almacena todos los errores PHP en `php_errors.log`
- Protegido con `.htaccess` (no accesible desde navegador)
- Excluido de git con `.gitignore`

## Vistas Actualizadas

Todas las vistas `ver.php` han sido actualizadas con el sistema:

✅ `views/ficha/ver.php`
✅ `views/ambiente/ver.php`
✅ `views/asignacion/ver.php`
✅ `views/detalle_asignacion/ver.php`
✅ `views/competencia_programa/ver.php`
✅ `views/sede/ver.php`
✅ `views/coordinacion/ver.php`
✅ `views/centro_formacion/ver.php`
✅ `views/instructor/ver.php`
✅ `views/programa/ver.php`
✅ `views/competencia/ver.php`
✅ `views/titulo_programa/ver.php`
✅ `views/instru_competencia/ver.php`

## Patrón de Uso en Vistas

### Estructura Estándar
```php
<?php
require_once __DIR__ . '/../../auth/check_auth.php'; // Carga automática del sistema
require_once __DIR__ . '/../../model/MiModel.php';

$model = new MiModel();
$id = safe($_GET, 'id', 0); // Acceso seguro a $_GET
$registro = $model->getById($id);

$pageTitle = "Ver Registro";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="detail-card">
        <?php if (registroValido($registro)): ?>
            <h2>Detalle del Registro</h2>
            <div class="detail-row">
                <div class="detail-label">Campo:</div>
                <div><?php echo safeHtml($registro, 'campo'); ?></div>
            </div>
            <!-- Más campos... -->
        <?php else: ?>
            <h2>Registro no encontrado</h2>
            <p>No se encontraron datos.</p>
            <a href="index.php" class="btn btn-secondary">Volver</a>
        <?php endif; ?>
    </div>
</div>
```

## Beneficios

1. ✅ **Sin warnings visibles** - Los usuarios nunca ven errores técnicos
2. ✅ **Registro de errores** - Todos los errores se guardan en log para debugging
3. ✅ **Código más limpio** - Menos código repetitivo de validación
4. ✅ **Seguridad XSS** - Escape automático de HTML
5. ✅ **Tolerante a errores** - El sistema sigue funcionando aunque falten datos
6. ✅ **Mensajes amigables** - "No disponible" en lugar de warnings
7. ✅ **Fácil mantenimiento** - Funciones centralizadas y reutilizables

## Próximos Pasos (Opcional)

Para aplicar el sistema a TODAS las páginas del proyecto:

1. **Formularios de creación/edición** - Usar `safe()` en valores de formularios
2. **Tablas de listado** - Usar `safeHtml()` en celdas de tablas
3. **Archivos sin check_auth** - Agregar manualmente los requires
4. **Validaciones de formulario** - Usar `safe()` para acceder a $_POST

## Monitoreo de Errores

Para revisar errores registrados:
```bash
# Ver últimas líneas del log
tail -f dashboard_sena/logs/php_errors.log

# Ver todo el log
cat dashboard_sena/logs/php_errors.log
```

## Modo Desarrollo

Si necesitas ver errores en pantalla durante desarrollo, edita `config/error_handler.php`:
```php
ini_set('display_errors', 1); // Cambiar de 0 a 1
```

**IMPORTANTE:** Siempre volver a `0` en producción.
