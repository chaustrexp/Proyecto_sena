<?php
/**
 * Sistema de Diagnóstico Completo
 * Verifica que todos los componentes del dashboard funcionen correctamente
 */

// Deshabilitar límite de tiempo
set_time_limit(0);

// Iniciar buffer de salida
ob_start();

// Función para mostrar resultado
function mostrarResultado($nombre, $estado, $mensaje = '', $detalles = '') {
    $icono = $estado ? '✅' : '❌';
    $color = $estado ? '#10b981' : '#ef4444';
    $bg = $estado ? '#d1fae5' : '#fee2e2';
    
    echo "<div style='background: $bg; border-left: 4px solid $color; padding: 16px; margin-bottom: 12px; border-radius: 8px;'>";
    echo "<div style='display: flex; align-items: center; gap: 12px; margin-bottom: 8px;'>";
    echo "<span style='font-size: 24px;'>$icono</span>";
    echo "<strong style='font-size: 16px; color: #1f2937;'>$nombre</strong>";
    echo "</div>";
    if ($mensaje) {
        echo "<div style='color: #6b7280; font-size: 14px; margin-left: 36px;'>$mensaje</div>";
    }
    if ($detalles) {
        echo "<div style='background: white; padding: 12px; margin-top: 8px; margin-left: 36px; border-radius: 6px; font-family: monospace; font-size: 12px; color: #374151;'>$detalles</div>";
    }
    echo "</div>";
}

// Función para contar archivos
function contarArchivos($directorio, $extension = '.php') {
    $count = 0;
    if (is_dir($directorio)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directorio, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($files as $file) {
            if ($file->isFile() && strpos($file->getFilename(), $extension) !== false) {
                $count++;
            }
        }
    }
    return $count;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema - Dashboard SENA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 32px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 32px;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #6b7280;
            font-size: 16px;
        }
        
        .section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
        
        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #39A900;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #39A900;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #007832;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(57, 169, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Diagnóstico del Sistema</h1>
            <p>Verificación completa del Dashboard SENA</p>
        </div>

        <?php
        $errores = 0;
        $advertencias = 0;
        $exitos = 0;
        
        // 1. VERIFICAR ESTRUCTURA DE ARCHIVOS
        echo "<div class='section'>";
        echo "<h2 class='section-title'>📁 Estructura de Archivos</h2>";
        
        $directorios = [
            'controller' => 'Controladores',
            'model' => 'Modelos',
            'views' => 'Vistas',
            'auth' => 'Autenticación',
            'config' => 'Configuración',
            'helpers' => 'Helpers',
            'assets' => 'Assets',
            'logs' => 'Logs'
        ];
        
        foreach ($directorios as $dir => $nombre) {
            $existe = is_dir(__DIR__ . "/../$dir");
            mostrarResultado(
                "Directorio: $nombre",
                $existe,
                $existe ? "Directorio encontrado en /$dir" : "Directorio no encontrado",
                $existe ? "Archivos: " . contarArchivos(__DIR__ . "/../$dir") : ""
            );
            $existe ? $exitos++ : $errores++;
        }
        
        echo "</div>";
        
        // 2. VERIFICAR ARCHIVOS CRÍTICOS
        echo "<div class='section'>";
        echo "<h2 class='section-title'>📄 Archivos Críticos</h2>";
        
        $archivos = [
            'index.php' => 'Punto de entrada',
            'conexion.php' => 'Conexión a BD',
            'auth/check_auth.php' => 'Sistema de autenticación',
            'config/error_handler.php' => 'Manejador de errores',
            'helpers/functions.php' => 'Funciones helper',
            'controller/DashboardController.php' => 'Controlador principal',
            'controller/BaseController.php' => 'Controlador base',
            'views/layout/header.php' => 'Header',
            'views/layout/sidebar.php' => 'Sidebar',
            'views/layout/footer.php' => 'Footer',
            'views/dashboard/index.php' => 'Vista dashboard'
        ];
        
        foreach ($archivos as $archivo => $descripcion) {
            $ruta = __DIR__ . "/../$archivo";
            $existe = file_exists($ruta);
            $tamano = $existe ? filesize($ruta) : 0;
            mostrarResultado(
                $descripcion,
                $existe,
                $existe ? "Archivo: $archivo" : "Archivo no encontrado: $archivo",
                $existe ? "Tamaño: " . number_format($tamano) . " bytes" : ""
            );
            $existe ? $exitos++ : $errores++;
        }
        
        echo "</div>";
        
        // 3. VERIFICAR CONEXIÓN A BASE DE DATOS
        echo "<div class='section'>";
        echo "<h2 class='section-title'>💾 Base de Datos</h2>";
        
        try {
            require_once __DIR__ . '/../conexion.php';
            $db = Database::getInstance()->getConnection();
            
            mostrarResultado(
                "Conexión a Base de Datos",
                true,
                "Conexión establecida correctamente",
                "Driver: " . $db->getAttribute(PDO::ATTR_DRIVER_NAME)
            );
            $exitos++;
            
            // Verificar tablas
            $tablas = [
                'PROGRAMA', 'FICHA', 'INSTRUCTOR', 'AMBIENTE', 
                'ASIGNACION', 'COMPETENCIA', 'CENTROFORMACION'
            ];
            
            foreach ($tablas as $tabla) {
                try {
                    $stmt = $db->query("SELECT COUNT(*) as total FROM $tabla");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    mostrarResultado(
                        "Tabla: $tabla",
                        true,
                        "Tabla accesible",
                        "Registros: " . $result['total']
                    );
                    $exitos++;
                } catch (Exception $e) {
                    mostrarResultado(
                        "Tabla: $tabla",
                        false,
                        "Error al acceder a la tabla",
                        $e->getMessage()
                    );
                    $errores++;
                }
            }
            
        } catch (Exception $e) {
            mostrarResultado(
                "Conexión a Base de Datos",
                false,
                "Error de conexión",
                $e->getMessage()
            );
            $errores++;
        }
        
        echo "</div>";
        
        // 4. VERIFICAR CONTROLADORES
        echo "<div class='section'>";
        echo "<h2 class='section-title'>🎮 Controladores</h2>";
        
        $controladores = [
            'DashboardController',
            'AsignacionController',
            'FichaController',
            'InstructorController',
            'AmbienteController',
            'ProgramaController',
            'CompetenciaController'
        ];
        
        foreach ($controladores as $controlador) {
            $archivo = __DIR__ . "/../controller/$controlador.php";
            if (file_exists($archivo)) {
                require_once $archivo;
                $existe = class_exists($controlador);
                mostrarResultado(
                    $controlador,
                    $existe,
                    $existe ? "Clase cargada correctamente" : "Clase no encontrada",
                    $existe ? "Métodos: " . count(get_class_methods($controlador)) : ""
                );
                $existe ? $exitos++ : $errores++;
            } else {
                mostrarResultado(
                    $controlador,
                    false,
                    "Archivo no encontrado"
                );
                $errores++;
            }
        }
        
        echo "</div>";
        
        // 5. VERIFICAR MODELOS
        echo "<div class='section'>";
        echo "<h2 class='section-title'>💼 Modelos</h2>";
        
        $modelos = [
            'ProgramaModel',
            'FichaModel',
            'InstructorModel',
            'AmbienteModel',
            'AsignacionModel',
            'CompetenciaModel'
        ];
        
        foreach ($modelos as $modelo) {
            $archivo = __DIR__ . "/../model/$modelo.php";
            if (file_exists($archivo)) {
                require_once $archivo;
                $existe = class_exists($modelo);
                
                if ($existe) {
                    try {
                        $instance = new $modelo();
                        $metodos = get_class_methods($instance);
                        $tieneGetAll = in_array('getAll', $metodos);
                        $tieneCount = in_array('count', $metodos);
                        
                        mostrarResultado(
                            $modelo,
                            true,
                            "Modelo funcional",
                            "Métodos: " . count($metodos) . " | getAll: " . ($tieneGetAll ? 'Sí' : 'No') . " | count: " . ($tieneCount ? 'Sí' : 'No')
                        );
                        $exitos++;
                    } catch (Exception $e) {
                        mostrarResultado(
                            $modelo,
                            false,
                            "Error al instanciar modelo",
                            $e->getMessage()
                        );
                        $errores++;
                    }
                } else {
                    mostrarResultado(
                        $modelo,
                        false,
                        "Clase no encontrada"
                    );
                    $errores++;
                }
            } else {
                mostrarResultado(
                    $modelo,
                    false,
                    "Archivo no encontrado"
                );
                $errores++;
            }
        }
        
        echo "</div>";
        
        // 6. VERIFICAR FUNCIONES HELPER
        echo "<div class='section'>";
        echo "<h2 class='section-title'>🛠️ Funciones Helper</h2>";
        
        require_once __DIR__ . '/../helpers/functions.php';
        
        $funciones = ['safe', 'safeHtml', 'e', 'registroValido'];
        
        foreach ($funciones as $funcion) {
            $existe = function_exists($funcion);
            mostrarResultado(
                "Función: $funcion()",
                $existe,
                $existe ? "Función disponible" : "Función no encontrada"
            );
            $existe ? $exitos++ : $errores++;
        }
        
        // Probar funciones
        $testArray = ['nombre' => 'Test', 'edad' => 25];
        $testSafe = safe($testArray, 'nombre');
        $testSafeHtml = safeHtml($testArray, 'nombre');
        $testRegistroValido = registroValido($testArray);
        
        mostrarResultado(
            "Prueba de Funciones",
            ($testSafe === 'Test' && $testRegistroValido === true),
            "Funciones operativas",
            "safe() = '$testSafe' | registroValido() = " . ($testRegistroValido ? 'true' : 'false')
        );
        $exitos++;
        
        echo "</div>";
        
        // 7. VERIFICAR SISTEMA DE ERRORES
        echo "<div class='section'>";
        echo "<h2 class='section-title'>⚠️ Sistema de Manejo de Errores</h2>";
        
        $errorHandler = file_exists(__DIR__ . '/../config/error_handler.php');
        mostrarResultado(
            "Error Handler",
            $errorHandler,
            $errorHandler ? "Sistema de errores configurado" : "Error handler no encontrado"
        );
        $errorHandler ? $exitos++ : $errores++;
        
        $logDir = is_dir(__DIR__ . '/../logs');
        $logWritable = $logDir && is_writable(__DIR__ . '/../logs');
        mostrarResultado(
            "Directorio de Logs",
            $logWritable,
            $logWritable ? "Logs configurados y escribibles" : "Problema con directorio de logs",
            $logWritable ? "Ruta: /logs/" : ""
        );
        $logWritable ? $exitos++ : $advertencias++;
        
        echo "</div>";
        
        // 8. RESUMEN FINAL
        $total = $exitos + $errores + $advertencias;
        $porcentaje = $total > 0 ? round(($exitos / $total) * 100, 1) : 0;
        
        echo "<div class='section'>";
        echo "<h2 class='section-title'>📊 Resumen del Diagnóstico</h2>";
        
        echo "<div class='stats-grid'>";
        echo "<div class='stat-card' style='background: linear-gradient(135deg, #10b981 0%, #059669 100%);'>";
        echo "<div class='stat-number'>$exitos</div>";
        echo "<div class='stat-label'>Pruebas Exitosas</div>";
        echo "</div>";
        
        echo "<div class='stat-card' style='background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);'>";
        echo "<div class='stat-number'>$errores</div>";
        echo "<div class='stat-label'>Errores</div>";
        echo "</div>";
        
        echo "<div class='stat-card' style='background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);'>";
        echo "<div class='stat-number'>$advertencias</div>";
        echo "<div class='stat-label'>Advertencias</div>";
        echo "</div>";
        
        echo "<div class='stat-card' style='background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);'>";
        echo "<div class='stat-number'>$porcentaje%</div>";
        echo "<div class='stat-label'>Salud del Sistema</div>";
        echo "</div>";
        echo "</div>";
        
        // Mensaje final
        echo "<div style='margin-top: 24px; padding: 20px; background: ";
        if ($errores == 0) {
            echo "#d1fae5; border-left: 4px solid #10b981;";
            echo "'><strong style='color: #065f46;'>✅ Sistema Funcionando Correctamente</strong>";
            echo "<p style='color: #047857; margin-top: 8px;'>Todos los componentes están operativos. El dashboard está listo para usar.</p>";
        } elseif ($errores < 5) {
            echo "#fef3c7; border-left: 4px solid #f59e0b;";
            echo "'><strong style='color: #92400e;'>⚠️ Sistema Funcional con Advertencias</strong>";
            echo "<p style='color: #b45309; margin-top: 8px;'>El sistema funciona pero hay algunos componentes que requieren atención.</p>";
        } else {
            echo "#fee2e2; border-left: 4px solid #ef4444;";
            echo "'><strong style='color: #991b1b;'>❌ Sistema con Errores Críticos</strong>";
            echo "<p style='color: #dc2626; margin-top: 8px;'>Se detectaron errores que impiden el funcionamiento correcto del sistema.</p>";
        }
        echo "</div>";
        
        echo "<div style='margin-top: 24px; text-align: center;'>";
        echo "<a href='../index.php' class='btn'>Ir al Dashboard</a> ";
        echo "<a href='diagnostico_sistema.php' class='btn' style='background: #6b7280;'>Recargar Diagnóstico</a>";
        echo "</div>";
        
        echo "</div>";
        ?>
    </div>
</body>
</html>
