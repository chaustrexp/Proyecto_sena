<?php
/**
 * Diagnóstico de Rendimiento del Dashboard SENA
 * Identifica cuellos de botella y problemas de velocidad
 */

// Iniciar medición de tiempo
$tiempoInicio = microtime(true);

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Diagnóstico de Rendimiento - Dashboard SENA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f3f4f6; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #39A900 0%, #007832 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .metric { display: flex; justify-content: space-between; padding: 15px; border-bottom: 1px solid #e5e7eb; }
        .metric:last-child { border-bottom: none; }
        .metric-label { font-weight: 600; color: #374151; }
        .metric-value { font-family: 'Courier New', monospace; }
        .status-ok { color: #10b981; font-weight: bold; }
        .status-warning { color: #f59e0b; font-weight: bold; }
        .status-error { color: #ef4444; font-weight: bold; }
        .section-title { font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 15px; }
        .recommendation { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin-top: 15px; border-radius: 4px; }
        .progress-bar { width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; margin-top: 5px; }
        .progress-fill { height: 100%; transition: width 0.3s; }
        .code { background: #f3f4f6; padding: 10px; border-radius: 4px; font-family: 'Courier New', monospace; font-size: 12px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🔍 Diagnóstico de Rendimiento</h1>
            <p style='margin-top: 10px; opacity: 0.9;'>Dashboard SENA - Análisis de Velocidad y Optimización</p>
        </div>";

// 1. TEST DE CONEXIÓN A BASE DE DATOS
echo "<div class='card'>
        <div class='section-title'>1. Conexión a Base de Datos</div>";

$dbStart = microtime(true);
try {
    require_once __DIR__ . '/../conexion.php';
    $db = Database::getInstance()->getConnection();
    $dbTime = (microtime(true) - $dbStart) * 1000;
    
    $statusClass = $dbTime < 50 ? 'status-ok' : ($dbTime < 200 ? 'status-warning' : 'status-error');
    echo "<div class='metric'>
            <span class='metric-label'>Tiempo de conexión</span>
            <span class='metric-value {$statusClass}'>" . number_format($dbTime, 2) . " ms</span>
          </div>";
    
    // Test de ping
    $pingStart = microtime(true);
    $db->query("SELECT 1");
    $pingTime = (microtime(true) - $pingStart) * 1000;
    
    $pingClass = $pingTime < 10 ? 'status-ok' : ($pingTime < 50 ? 'status-warning' : 'status-error');
    echo "<div class='metric'>
            <span class='metric-label'>Ping a MySQL</span>
            <span class='metric-value {$pingClass}'>" . number_format($pingTime, 2) . " ms</span>
          </div>";
    
    echo "<div class='metric'>
            <span class='metric-label'>Estado</span>
            <span class='metric-value status-ok'>✓ Conectado</span>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='metric'>
            <span class='metric-label'>Error</span>
            <span class='metric-value status-error'>" . htmlspecialchars($e->getMessage()) . "</span>
          </div>";
}

echo "</div>";

// 2. TEST DE CONSULTAS A MODELOS
echo "<div class='card'>
        <div class='section-title'>2. Rendimiento de Consultas</div>";

$modelos = [
    'ProgramaModel' => 'Programas',
    'FichaModel' => 'Fichas',
    'InstructorModel' => 'Instructores',
    'AmbienteModel' => 'Ambientes',
    'AsignacionModel' => 'Asignaciones'
];

$tiemposConsultas = [];

foreach ($modelos as $clase => $nombre) {
    try {
        require_once __DIR__ . "/../model/{$clase}.php";
        $modelo = new $clase();
        
        $queryStart = microtime(true);
        $count = $modelo->count();
        $queryTime = (microtime(true) - $queryStart) * 1000;
        
        $tiemposConsultas[] = $queryTime;
        
        $queryClass = $queryTime < 50 ? 'status-ok' : ($queryTime < 200 ? 'status-warning' : 'status-error');
        echo "<div class='metric'>
                <span class='metric-label'>{$nombre} (count)</span>
                <span class='metric-value {$queryClass}'>" . number_format($queryTime, 2) . " ms ({$count} registros)</span>
              </div>";
        
    } catch (Exception $e) {
        echo "<div class='metric'>
                <span class='metric-label'>{$nombre}</span>
                <span class='metric-value status-error'>Error: " . htmlspecialchars($e->getMessage()) . "</span>
              </div>";
    }
}

$promedioConsultas = count($tiemposConsultas) > 0 ? array_sum($tiemposConsultas) / count($tiemposConsultas) : 0;
$promedioClass = $promedioConsultas < 50 ? 'status-ok' : ($promedioConsultas < 200 ? 'status-warning' : 'status-error');

echo "<div class='metric' style='background: #f9fafb; font-weight: bold;'>
        <span class='metric-label'>Promedio de consultas</span>
        <span class='metric-value {$promedioClass}'>" . number_format($promedioConsultas, 2) . " ms</span>
      </div>";

echo "</div>";

// 3. TEST DE CARGA DE VISTAS
echo "<div class='card'>
        <div class='section-title'>3. Carga de Archivos PHP</div>";

$archivos = [
    'Header' => 'views/layout/header.php',
    'Sidebar' => 'views/layout/sidebar.php',
    'Dashboard Index' => 'views/dashboard/index.php',
    'Stats Cards' => 'views/dashboard/stats_cards.php',
    'Calendar' => 'views/dashboard/calendar.php',
    'Footer' => 'views/layout/footer.php'
];

foreach ($archivos as $nombre => $ruta) {
    $rutaCompleta = __DIR__ . "/../{$ruta}";
    if (file_exists($rutaCompleta)) {
        $fileStart = microtime(true);
        $contenido = file_get_contents($rutaCompleta);
        $fileTime = (microtime(true) - $fileStart) * 1000;
        $tamano = strlen($contenido);
        
        $fileClass = $fileTime < 5 ? 'status-ok' : ($fileTime < 20 ? 'status-warning' : 'status-error');
        echo "<div class='metric'>
                <span class='metric-label'>{$nombre}</span>
                <span class='metric-value {$fileClass}'>" . number_format($fileTime, 2) . " ms (" . number_format($tamano/1024, 1) . " KB)</span>
              </div>";
    } else {
        echo "<div class='metric'>
                <span class='metric-label'>{$nombre}</span>
                <span class='metric-value status-error'>No encontrado</span>
              </div>";
    }
}

echo "</div>";

// 4. CONFIGURACIÓN DE PHP
echo "<div class='card'>
        <div class='section-title'>4. Configuración de PHP</div>";

$phpConfigs = [
    'Versión PHP' => phpversion(),
    'Memory Limit' => ini_get('memory_limit'),
    'Max Execution Time' => ini_get('max_execution_time') . 's',
    'Upload Max Filesize' => ini_get('upload_max_filesize'),
    'Post Max Size' => ini_get('post_max_size'),
    'OPcache Enabled' => function_exists('opcache_get_status') && opcache_get_status() ? 'Sí' : 'No'
];

foreach ($phpConfigs as $config => $valor) {
    $configClass = 'metric-value';
    if ($config === 'OPcache Enabled' && $valor === 'No') {
        $configClass .= ' status-warning';
    }
    echo "<div class='metric'>
            <span class='metric-label'>{$config}</span>
            <span class='{$configClass}'>{$valor}</span>
          </div>";
}

echo "</div>";

// 5. RECOMENDACIONES
echo "<div class='card'>
        <div class='section-title'>5. Recomendaciones de Optimización</div>";

$recomendaciones = [];

if ($promedioConsultas > 100) {
    $recomendaciones[] = "⚠️ Las consultas a la base de datos son lentas (>" . number_format($promedioConsultas, 0) . "ms). Considera agregar índices a las tablas.";
}

if (!function_exists('opcache_get_status') || !opcache_get_status()) {
    $recomendaciones[] = "⚠️ OPcache no está habilitado. Activarlo puede mejorar el rendimiento hasta un 50%.";
}

if ($dbTime > 100) {
    $recomendaciones[] = "⚠️ La conexión a MySQL es lenta. Verifica que MySQL esté optimizado y no tenga consultas bloqueadas.";
}

if (count($recomendaciones) === 0) {
    echo "<div style='color: #10b981; padding: 15px; background: #d1fae5; border-radius: 8px;'>
            ✓ El sistema está funcionando con buen rendimiento. No se detectaron problemas críticos.
          </div>";
} else {
    foreach ($recomendaciones as $rec) {
        echo "<div class='recommendation'>{$rec}</div>";
    }
}

// Recomendaciones generales
echo "<div style='margin-top: 20px; padding: 15px; background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px;'>
        <strong>💡 Optimizaciones Sugeridas:</strong>
        <ul style='margin-top: 10px; margin-left: 20px;'>
            <li>Habilitar OPcache en php.ini</li>
            <li>Agregar índices a columnas frecuentemente consultadas</li>
            <li>Implementar caché de consultas (Redis/Memcached)</li>
            <li>Minimizar archivos CSS/JS</li>
            <li>Usar lazy loading para imágenes</li>
        </ul>
      </div>";

echo "</div>";

// 6. TIEMPO TOTAL
$tiempoTotal = (microtime(true) - $tiempoInicio) * 1000;
$totalClass = $tiempoTotal < 500 ? 'status-ok' : ($tiempoTotal < 2000 ? 'status-warning' : 'status-error');

echo "<div class='card' style='background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);'>
        <div class='section-title'>⏱️ Tiempo Total de Diagnóstico</div>
        <div style='text-align: center; padding: 20px;'>
            <div style='font-size: 48px; font-weight: bold;' class='{$totalClass}'>" . number_format($tiempoTotal, 0) . " ms</div>
            <div style='color: #6b7280; margin-top: 10px;'>Tiempo de ejecución del diagnóstico completo</div>
        </div>
      </div>";

echo "    </div>
</body>
</html>";
?>
