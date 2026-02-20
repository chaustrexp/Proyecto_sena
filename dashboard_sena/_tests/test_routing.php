<?php
/**
 * Script de Prueba del Sistema de Routing
 * Verifica que todas las rutas estén configuradas correctamente
 */

// Deshabilitar autenticación para pruebas
define('SKIP_AUTH', true);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Routing - Dashboard SENA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #39A900 0%, #007832 100%);
            color: white;
            padding: 32px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 32px;
        }
        
        .section {
            margin-bottom: 32px;
        }
        
        .section h2 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 3px solid #39A900;
        }
        
        .test-group {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
        }
        
        .test-group h3 {
            font-size: 18px;
            color: #374151;
            margin-bottom: 12px;
        }
        
        .test-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: white;
            border-radius: 6px;
            margin-bottom: 8px;
            border-left: 4px solid #e5e7eb;
            transition: all 0.2s;
        }
        
        .test-item:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .test-item.success {
            border-left-color: #10b981;
        }
        
        .test-item.error {
            border-left-color: #ef4444;
        }
        
        .test-item.warning {
            border-left-color: #f59e0b;
        }
        
        .status-icon {
            font-size: 24px;
            margin-right: 12px;
            min-width: 32px;
            text-align: center;
        }
        
        .test-info {
            flex: 1;
        }
        
        .test-url {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }
        
        .test-link {
            background: #39A900;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .test-link:hover {
            background: #007832;
            transform: scale(1.05);
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-radius: 12px;
            text-align: center;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .stat-card.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .stat-card.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .stat-value {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        
        .info-box h4 {
            color: #1e40af;
            margin-bottom: 8px;
            font-size: 16px;
        }
        
        .info-box p {
            color: #1e3a8a;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .code-block {
            background: #1f2937;
            color: #10b981;
            padding: 16px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test de Sistema de Routing</h1>
            <p>Verificación de rutas y controladores del Dashboard SENA</p>
        </div>
        
        <div class="content">
            <?php
            // Configuración
            $baseUrl = '/Gestion-sena/dashboard_sena';
            
            // Definir rutas a probar
            $routes = [
                'Dashboard' => [
                    ['name' => 'Página Principal', 'url' => '/dashboard', 'method' => 'GET'],
                    ['name' => 'Raíz (debe redirigir a dashboard)', 'url' => '/', 'method' => 'GET'],
                ],
                'Asignaciones' => [
                    ['name' => 'Listar Asignaciones', 'url' => '/asignacion', 'method' => 'GET'],
                    ['name' => 'Crear Asignación (formulario)', 'url' => '/asignacion/create', 'method' => 'GET'],
                    ['name' => 'Ver Asignación #1', 'url' => '/asignacion/show/1', 'method' => 'GET'],
                    ['name' => 'Editar Asignación #1', 'url' => '/asignacion/edit/1', 'method' => 'GET'],
                ],
                'Fichas' => [
                    ['name' => 'Listar Fichas', 'url' => '/ficha', 'method' => 'GET'],
                    ['name' => 'Crear Ficha (formulario)', 'url' => '/ficha/create', 'method' => 'GET'],
                    ['name' => 'Ver Ficha #123', 'url' => '/ficha/show/123', 'method' => 'GET'],
                ],
                'Instructores' => [
                    ['name' => 'Listar Instructores', 'url' => '/instructor', 'method' => 'GET'],
                    ['name' => 'Crear Instructor (formulario)', 'url' => '/instructor/create', 'method' => 'GET'],
                    ['name' => 'Ver Instructor #5', 'url' => '/instructor/show/5', 'method' => 'GET'],
                ],
                'Ambientes' => [
                    ['name' => 'Listar Ambientes', 'url' => '/ambiente', 'method' => 'GET'],
                    ['name' => 'Crear Ambiente (formulario)', 'url' => '/ambiente/create', 'method' => 'GET'],
                ],
                'Programas' => [
                    ['name' => 'Listar Programas', 'url' => '/programa', 'method' => 'GET'],
                    ['name' => 'Crear Programa (formulario)', 'url' => '/programa/create', 'method' => 'GET'],
                ],
                'Competencias' => [
                    ['name' => 'Listar Competencias', 'url' => '/competencia', 'method' => 'GET'],
                    ['name' => 'Crear Competencia (formulario)', 'url' => '/competencia/create', 'method' => 'GET'],
                ],
            ];
            
            // Verificar archivos del sistema
            $systemFiles = [
                'routing.php' => __DIR__ . '/../routing.php',
                '.htaccess' => __DIR__ . '/../.htaccess',
                'BaseController.php' => __DIR__ . '/../controller/BaseController.php',
                'DashboardController.php' => __DIR__ . '/../controller/DashboardController.php',
                'AsignacionController.php' => __DIR__ . '/../controller/AsignacionController.php',
                'FichaController.php' => __DIR__ . '/../controller/FichaController.php',
                'InstructorController.php' => __DIR__ . '/../controller/InstructorController.php',
                'AmbienteController.php' => __DIR__ . '/../controller/AmbienteController.php',
                'ProgramaController.php' => __DIR__ . '/../controller/ProgramaController.php',
                'CompetenciaController.php' => __DIR__ . '/../controller/CompetenciaController.php',
            ];
            
            $filesOk = 0;
            $filesError = 0;
            
            foreach ($systemFiles as $name => $path) {
                if (file_exists($path)) {
                    $filesOk++;
                } else {
                    $filesError++;
                }
            }
            
            $totalRoutes = 0;
            foreach ($routes as $group) {
                $totalRoutes += count($group);
            }
            ?>
            
            <!-- Estadísticas -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalRoutes; ?></div>
                    <div class="stat-label">Rutas Definidas</div>
                </div>
                <div class="stat-card <?php echo $filesError === 0 ? 'success' : 'error'; ?>">
                    <div class="stat-value"><?php echo $filesOk; ?>/<?php echo count($systemFiles); ?></div>
                    <div class="stat-label">Archivos del Sistema</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-value"><?php echo count($routes); ?></div>
                    <div class="stat-label">Módulos</div>
                </div>
            </div>
            
            <!-- Información -->
            <div class="info-box">
                <h4>ℹ️ Cómo usar este test</h4>
                <p>
                    Haz clic en los botones "Probar" para verificar cada ruta. Si el routing está configurado correctamente,
                    deberías ver la página correspondiente. Si ves un error 404 o 500, revisa la configuración de Apache
                    y el archivo .htaccess.
                </p>
            </div>
            
            <!-- Verificación de archivos del sistema -->
            <div class="section">
                <h2>📁 Archivos del Sistema</h2>
                <div class="test-group">
                    <?php foreach ($systemFiles as $name => $path): ?>
                        <div class="test-item <?php echo file_exists($path) ? 'success' : 'error'; ?>">
                            <div class="status-icon">
                                <?php echo file_exists($path) ? '✅' : '❌'; ?>
                            </div>
                            <div class="test-info">
                                <strong><?php echo $name; ?></strong>
                                <div class="test-url"><?php echo $path; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Rutas por módulo -->
            <?php foreach ($routes as $module => $moduleRoutes): ?>
                <div class="section">
                    <h2>🔗 <?php echo $module; ?></h2>
                    <div class="test-group">
                        <?php foreach ($moduleRoutes as $route): ?>
                            <div class="test-item">
                                <div class="status-icon">🌐</div>
                                <div class="test-info">
                                    <strong><?php echo $route['name']; ?></strong>
                                    <div class="test-url"><?php echo $baseUrl . $route['url']; ?></div>
                                </div>
                                <a href="<?php echo $baseUrl . $route['url']; ?>" class="test-link" target="_blank">
                                    Probar →
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Información técnica -->
            <div class="section">
                <h2>🔧 Información Técnica</h2>
                <div class="test-group">
                    <h3>Configuración del Servidor</h3>
                    <div class="code-block">
                        Servidor: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido'; ?><br>
                        PHP: <?php echo PHP_VERSION; ?><br>
                        Document Root: <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?><br>
                        Script: <?php echo $_SERVER['SCRIPT_NAME'] ?? 'N/A'; ?><br>
                        Base URL: <?php echo $baseUrl; ?>
                    </div>
                </div>
                
                <div class="test-group">
                    <h3>Verificar mod_rewrite</h3>
                    <p style="margin-bottom: 12px; color: #6b7280;">
                        Para que el routing funcione, Apache debe tener habilitado mod_rewrite.
                    </p>
                    <div class="code-block">
                        # En Linux/Mac<br>
                        apache2ctl -M | grep rewrite<br>
                        <br>
                        # En Windows (XAMPP)<br>
                        # Abrir httpd.conf y verificar:<br>
                        LoadModule rewrite_module modules/mod_rewrite.so
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
