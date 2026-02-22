# 🚀 Guía de Optimización de Rendimiento

## Problema: Dashboard Lento

### Soluciones Inmediatas

#### 1. Habilitar OPcache en PHP

**Ubicación:** `C:\xampp\php\php.ini`

**Agregar/Descomentar estas líneas:**

```ini
[opcache]
zend_extension=opcache
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

**Reiniciar Apache después de cambiar**

#### 2. Agregar Índices a la Base de Datos

```sql
-- Índices para mejorar velocidad de consultas
USE progsena;

-- Tabla ASIGNACION
ALTER TABLE ASIGNACION ADD INDEX idx_fecha_inicio (asig_fecha_inicio);
ALTER TABLE ASIGNACION ADD INDEX idx_fecha_fin (asig_fecha_fin);
ALTER TABLE ASIGNACION ADD INDEX idx_instructor (asig_instru_id);
ALTER TABLE ASIGNACION ADD INDEX idx_ficha (asig_fich_id);

-- Tabla FICHA
ALTER TABLE FICHA ADD INDEX idx_numero (fich_numero);
ALTER TABLE FICHA ADD INDEX idx_programa (fich_prog_id);

-- Tabla INSTRUCTOR
ALTER TABLE INSTRUCTOR ADD INDEX idx_nombre (instru_nombre);

-- Tabla AMBIENTE
ALTER TABLE AMBIENTE ADD INDEX idx_nombre (amb_nombre);

-- Tabla PROGRAMA
ALTER TABLE PROGRAMA ADD INDEX idx_titulo (prog_titu_id);
```

#### 3. Optimizar Consultas del Dashboard

**Archivo:** `model/AsignacionModel.php`

Usar LIMIT en consultas que no necesitan todos los registros:

```php
// En lugar de:
$stmt = $this->db->query("SELECT * FROM ASIGNACION");

// Usar:
$stmt = $this->db->query("SELECT * FROM ASIGNACION LIMIT 100");
```

#### 4. Implementar Caché Simple

**Crear:** `helpers/cache.php`

```php
<?php
class SimpleCache {
    private static $cache = [];
    private static $ttl = 300; // 5 minutos
    
    public static function get($key) {
        if (isset(self::$cache[$key])) {
            $data = self::$cache[$key];
            if (time() < $data['expires']) {
                return $data['value'];
            }
            unset(self::$cache[$key]);
        }
        return null;
    }
    
    public static function set($key, $value, $ttl = null) {
        $ttl = $ttl ?? self::$ttl;
        self::$cache[$key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
    }
}
?>
```

**Usar en DashboardController:**

```php
// Cachear estadísticas
$cacheKey = 'dashboard_stats';
$stats = SimpleCache::get($cacheKey);

if ($stats === null) {
    $stats = [
        'programas' => $this->programaModel->count(),
        'fichas' => $this->fichaModel->count(),
        // ... otras estadísticas
    ];
    SimpleCache::set($cacheKey, $stats, 300); // 5 minutos
}
```

### Verificación de Rendimiento

#### Tiempos Esperados

| Componente | Óptimo | Aceptable | Lento |
|------------|--------|-----------|-------|
| Conexión BD | < 50ms | < 200ms | > 200ms |
| Consulta simple | < 10ms | < 50ms | > 50ms |
| Carga página completa | < 500ms | < 2s | > 2s |

#### Herramientas de Diagnóstico

1. **Diagnóstico de Rendimiento:**
   ```
   http://localhost/Gestion-sena/dashboard_sena/_tests/diagnostico_rendimiento.php
   ```

2. **Consola del Navegador (F12):**
   - Network tab: Ver tiempos de carga
   - Console: Ver errores JavaScript

3. **MySQL Slow Query Log:**
   ```ini
   # En my.ini
   slow_query_log = 1
   slow_query_log_file = "C:/xampp/mysql/data/slow.log"
   long_query_time = 1
   ```

### Optimizaciones Avanzadas

#### 1. Lazy Loading de Imágenes

```html
<img src="imagen.jpg" loading="lazy" alt="Descripción">
```

#### 2. Minimizar CSS/JS

Usar herramientas como:
- UglifyJS para JavaScript
- CSSNano para CSS

#### 3. Comprimir Respuestas

**En .htaccess:**

```apache
# Habilitar compresión GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

#### 4. Caché del Navegador

**En .htaccess:**

```apache
# Caché de recursos estáticos
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### Monitoreo Continuo

#### Script de Monitoreo

```php
// _tests/monitor_rendimiento.php
<?php
$start = microtime(true);

// Simular carga del dashboard
require_once '../controller/DashboardController.php';
$controller = new DashboardController();

$time = (microtime(true) - $start) * 1000;

// Guardar en log
file_put_contents(
    '../logs/performance.log',
    date('Y-m-d H:i:s') . " - Dashboard: {$time}ms\n",
    FILE_APPEND
);

echo "Tiempo: {$time}ms";
?>
```

### Checklist de Optimización

- [ ] OPcache habilitado
- [ ] Índices agregados a tablas principales
- [ ] Consultas optimizadas con LIMIT
- [ ] Caché implementado para estadísticas
- [ ] Compresión GZIP habilitada
- [ ] Caché del navegador configurado
- [ ] Imágenes con lazy loading
- [ ] CSS/JS minimizados
- [ ] Slow query log habilitado
- [ ] Monitoreo de rendimiento activo

### Resultados Esperados

Después de aplicar estas optimizaciones:

- ✅ Carga inicial: < 1 segundo
- ✅ Navegación entre páginas: < 500ms
- ✅ Consultas a BD: < 50ms promedio
- ✅ Uso de memoria: < 64MB por petición

---

**Nota:** Aplica las optimizaciones de forma incremental y mide el impacto de cada una.
