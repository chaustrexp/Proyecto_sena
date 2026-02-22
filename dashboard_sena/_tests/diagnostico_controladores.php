<?php
/**
 * Diagnóstico Completo de Controladores
 * Verifica qué controladores están conectados y funcionando
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexion.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Controladores</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #39A900 0%, #007832 100%); color: white; padding: 40px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .section { background: white; padding: 30px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section h2 { color: #007832; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #39A900; font-size: 1.8em; }
        .controller-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; margin-top: 20px; }
        .controller-card { background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 12px; padding: 25px; transition: all 0.3s; }
        .controller-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .controller-card.connected { border-color: #28a745; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); }
        .controller-card.partial { border-color: #ffc107; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); }
        .controller-card.disconnected { border-color: #dc3545; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); }
        .controller-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .controller-name { font-size: 1.4em; font-weight: 700; color: #1f2937; }
        .status-badge { padding: 6px 16px; border-radius: 20px; font-size: 0.85em; font-weight: 600; }
        .status-badge.connected { background: #28a745; color: white; }
        .status-badge.partial { background: #ffc107; color: #000; }
        .status-badge.disconnected { background: #dc3545; color: white; }
        .controller-details { margin-top: 15px; }
        .detail-item { padding: 8px 0; display: flex; align-items: center; gap: 10px; font-size: 0.95em; }
        .detail-item .icon { font-size: 1.2em; }
        .detail-item.success { color: #155724; }
        .detail-item.error { color: #721c24; }
        .detail-item.warning { color: #856404; }
        .actions { margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 8px 16px; background: #39A900; color: white; text-decoration: none; border-radius: 6px; font-size: 0.9em; margin-right: 8px; transition: background 0.3s; }
        .btn:hover { background: #007832; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .summary-card { background: white; padding: 25px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .summary-card .number { font-size: 3em; font-weight: 700; margin-bottom: 10px; }
        .summary-card .label { font-size: 1.1em; color: #6b7280; font-weight: 600; }
        .summary-card.connected .number { color: #28a745; }
        .summary-card.partial .number { color: #ffc107; }
        .summary-card.disconnected .number { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎮 Diagnóstico de Controladores</h1>
            <p style="font-size: 1.2em; opacity: 0.95;">Estado de conexión de todos los módulos del sistema</p>
            <p style="font-size: 0.9em; margin-top: 10px; opacity: 0.8;">Fecha: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>

        <?php
        // Definir todos los controladores del sistema
        $controladores = [
            'Dashboard' => [
                'file' => 'controller/DashboardController.php',
                'model' => null,
                'views' => 'views/dashboard/',
                'route' => '/dashboard',
                'icon' => '📊'
            ],
            'Asignaciones' => [
                'file' => 'controller/AsignacionController.php',
                'model' => 'model/AsignacionModel.php',
                'views' => 'views/asignacion/',
                'route' => '/asignacion',
                'icon' => '📅'
            ],
            'Fichas' => [
                'file' => 'controller/FichaController.php',
                'model' => 'model/FichaModel.php',
                'views' => 'views/ficha/',
                'route' => '/ficha',
                'icon' => '📄'
            ],
            'Instructores' => [
                'file' => 'controller/InstructorController.php',
                'model' => 'model/InstructorModel.php',
                'views' => 'views/instructor/',
                'route' => '/instructor',
                'icon' => '👥'
            ],
            'Ambientes' => [
                'file' => 'controller/AmbienteController.php',
                'model' => 'model/AmbienteModel.php',
                'views' => 'views/ambiente/',
                'route' => '/ambiente',
                'icon' => '🏠'
            ],
            'Programas' => [
                'file' => 'controller/ProgramaController.php',
                'model' => 'model/ProgramaModel.php',
                'views' => 'views/programa/',
                'route' => '/programa',
                'icon' => '📚'
            ],
            'Competencias' => [
                'file' => 'controller/CompetenciaController.php',
                'model' => 'model/CompetenciaModel.php',
                'views' => 'views/competencia/',
                'route' => '/competencia',
                'icon' => '🏆'
            ]
        ];

        $conectados = 0;
        $parciales = 0;
        $desconectados = 0;
        $resultados = [];

        // Verificar cada controlador
        foreach ($controladores as $nombre => $config) {
            $resultado = [
                'nombre' => $nombre,
                'icon' => $config['icon'],
                'route' => $config['route'],
                'checks' => []
            ];

            $puntos = 0;
            $total_checks = 0;

            // Check 1: Archivo del controlador
            $total_checks++;
            if (file_exists(__DIR__ . '/../' . $config['file'])) {
                $resultado['checks'][] = ['status' => 'success', 'message' => 'Controlador existe'];
                $puntos++;
            } else {
                $resultado['checks'][] = ['status' => 'error', 'message' => 'Controlador NO existe'];
            }

            // Check 2: Modelo (si aplica)
            if ($config['model']) {
                $total_checks++;
                if (file_exists(__DIR__ . '/../' . $config['model'])) {
                    $resultado['checks'][] = ['status' => 'success', 'message' => 'Modelo existe'];
                    $puntos++;
                } else {
                    $resultado['checks'][] = ['status' => 'error', 'message' => 'Modelo NO existe'];
                }
            }

            // Check 3: Carpeta de vistas
            $total_checks++;
            if (is_dir(__DIR__ . '/../' . $config['views'])) {
                $resultado['checks'][] = ['status' => 'success', 'message' => 'Vistas existen'];
                $puntos++;
            } else {
                $resultado['checks'][] = ['status' => 'error', 'message' => 'Vistas NO existen'];
            }

            // Check 4: Vista index.php
            $total_checks++;
            if (file_exists(__DIR__ . '/../' . $config['views'] . 'index.php')) {
                $resultado['checks'][] = ['status' => 'success', 'message' => 'Vista index.php existe'];
                $puntos++;
            } else {
                $resultado['checks'][] = ['status' => 'error', 'message' => 'Vista index.php NO existe'];
            }

            // Check 5: Routing configurado
            $total_checks++;
            $routing_content = file_get_contents(__DIR__ . '/../routing.php');
            if (strpos($routing_content, "'" . trim($config['route'], '/') . "'") !== false) {
                $resultado['checks'][] = ['status' => 'success', 'message' => 'Configurado en routing'];
                $puntos++;
            } else {
                $resultado['checks'][] = ['status' => 'warning', 'message' => 'NO configurado en routing'];
            }

            // Determinar estado
            if ($puntos == $total_checks) {
                $resultado['status'] = 'connected';
                $conectados++;
            } elseif ($puntos > 0) {
                $resultado['status'] = 'partial';
                $parciales++;
            } else {
                $resultado['status'] = 'disconnected';
                $desconectados++;
            }

            $resultado['puntos'] = $puntos;
            $resultado['total'] = $total_checks;
            $resultados[] = $resultado;
        }

        // Mostrar resumen
        echo '<div class="summary">';
        echo '<div class="summary-card connected">';
        echo "<div class='number'>{$conectados}</div>";
        echo "<div class='label'>Conectados</div>";
        echo '</div>';
        echo '<div class="summary-card partial">';
        echo "<div class='number'>{$parciales}</div>";
        echo "<div class='label'>Parciales</div>";
        echo '</div>';
        echo '<div class="summary-card disconnected">';
        echo "<div class='number'>{$desconectados}</div>";
        echo "<div class='label'>Desconectados</div>";
        echo '</div>';
        echo '</div>';

        // Mostrar controladores
        echo '<div class="section">';
        echo '<h2>Estado de Controladores</h2>';
        echo '<div class="controller-grid">';

        foreach ($resultados as $resultado) {
            $statusClass = $resultado['status'];
            $statusLabel = $statusClass == 'connected' ? '✓ Conectado' : ($statusClass == 'partial' ? '⚠ Parcial' : '✗ Desconectado');
            
            echo "<div class='controller-card {$statusClass}'>";
            echo "<div class='controller-header'>";
            echo "<div class='controller-name'>{$resultado['icon']} {$resultado['nombre']}</div>";
            echo "<span class='status-badge {$statusClass}'>{$statusLabel}</span>";
            echo "</div>";
            
            echo "<div style='font-size: 0.9em; color: #6b7280; margin-bottom: 10px;'>";
            echo "Puntuación: {$resultado['puntos']}/{$resultado['total']}";
            echo "</div>";
            
            echo "<div class='controller-details'>";
            foreach ($resultado['checks'] as $check) {
                $icon = $check['status'] == 'success' ? '✓' : ($check['status'] == 'warning' ? '⚠' : '✗');
                echo "<div class='detail-item {$check['status']}'>";
                echo "<span class='icon'>{$icon}</span>";
                echo "<span>{$check['message']}</span>";
                echo "</div>";
            }
            echo "</div>";
            
            if ($resultado['status'] == 'connected') {
                echo "<div class='actions'>";
                echo "<a href='/Gestion-sena/dashboard_sena{$resultado['route']}' class='btn'>Abrir Módulo</a>";
                echo "<a href='/Gestion-sena/dashboard_sena{$resultado['route']}/create' class='btn btn-secondary'>Crear Nuevo</a>";
                echo "</div>";
            }
            
            echo "</div>";
        }

        echo '</div>';
        echo '</div>';
        ?>

        <div class="section">
            <h2>🔗 Enlaces Rápidos</h2>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="/Gestion-sena/dashboard_sena/" class="btn">Dashboard Principal</a>
                <a href="/Gestion-sena/dashboard_sena/routing.php" class="btn btn-secondary">Ver Routing</a>
                <a href="/Gestion-sena/dashboard_sena/_tests/" class="btn btn-secondary">Otros Tests</a>
            </div>
        </div>
    </div>
</body>
</html>
