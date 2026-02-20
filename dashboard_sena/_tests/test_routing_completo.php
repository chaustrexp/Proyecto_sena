<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Routing Completo - Dashboard SENA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            padding: 40px 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #39A900;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }
        .test-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .test-section h2 {
            color: #1f2937;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }
        .url-test {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 12px;
            align-items: center;
        }
        .url-test:hover {
            background: #f3f4f6;
        }
        .url {
            font-family: 'Courier New', monospace;
            color: #3b82f6;
            font-size: 14px;
        }
        .description {
            color: #6b7280;
            font-size: 13px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn-test {
            background: #39A900;
            color: white;
        }
        .btn-test:hover {
            background: #2d8700;
        }
        .btn-view {
            background: #3b82f6;
            color: white;
        }
        .btn-view:hover {
            background: #2563eb;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-ok {
            background: #d1fae5;
            color: #065f46;
        }
        .status-error {
            background: #fee2e2;
            color: #991b1b;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-box h3 {
            color: #1e40af;
            margin-bottom: 8px;
        }
        .info-box p {
            color: #1e3a8a;
            line-height: 1.6;
        }
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .warning-box h3 {
            color: #92400e;
            margin-bottom: 8px;
        }
        .warning-box p {
            color: #78350f;
            line-height: 1.6;
        }
        .success-box {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .success-box h3 {
            color: #065f46;
            margin-bottom: 8px;
        }
        .code {
            background: #1f2937;
            color: #10b981;
            padding: 16px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 12px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test de Routing Completo</h1>
        <p class="subtitle">Verificación del sistema de URLs amigables del Dashboard SENA</p>

        <!-- Estado del Sistema -->
        <div class="test-section">
            <h2>📊 Estado del Sistema</h2>
            
            <?php
            // Verificar mod_rewrite
            $modRewriteEnabled = false;
            if (function_exists('apache_get_modules')) {
                $modRewriteEnabled = in_array('mod_rewrite', apache_get_modules());
            }
            
            // Verificar .htaccess
            $htaccessExists = file_exists(__DIR__ . '/../.htaccess');
            
            // Verificar routing.php
            $routingExists = file_exists(__DIR__ . '/../routing.php');
            
            // Verificar controladores
            $controllersExist = file_exists(__DIR__ . '/../controller/DashboardController.php');
            ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                <div style="padding: 16px; background: <?php echo $modRewriteEnabled ? '#d1fae5' : '#fee2e2'; ?>; border-radius: 8px;">
                    <strong>mod_rewrite:</strong>
                    <span class="status <?php echo $modRewriteEnabled ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $modRewriteEnabled ? '✓ Habilitado' : '✗ Deshabilitado'; ?>
                    </span>
                </div>
                
                <div style="padding: 16px; background: <?php echo $htaccessExists ? '#d1fae5' : '#fee2e2'; ?>; border-radius: 8px;">
                    <strong>.htaccess:</strong>
                    <span class="status <?php echo $htaccessExists ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $htaccessExists ? '✓ Existe' : '✗ No existe'; ?>
                    </span>
                </div>
                
                <div style="padding: 16px; background: <?php echo $routingExists ? '#d1fae5' : '#fee2e2'; ?>; border-radius: 8px;">
                    <strong>routing.php:</strong>
                    <span class="status <?php echo $routingExists ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $routingExists ? '✓ Existe' : '✗ No existe'; ?>
                    </span>
                </div>
                
                <div style="padding: 16px; background: <?php echo $controllersExist ? '#d1fae5' : '#fee2e2'; ?>; border-radius: 8px;">
                    <strong>Controladores:</strong>
                    <span class="status <?php echo $controllersExist ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $controllersExist ? '✓ Existen' : '✗ No existen'; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- URLs del Dashboard -->
        <div class="test-section">
            <h2>🏠 Dashboard Principal</h2>
            
            <div class="url-test">
                <div>
                    <div class="url">/Gestion-sena/dashboard_sena/</div>
                    <div class="description">Dashboard principal (index.php)</div>
                </div>
                <a href="/Gestion-sena/dashboard_sena/" class="btn btn-view" target="_blank">Abrir</a>
            </div>
            
            <div class="url-test">
                <div>
                    <div class="url">/Gestion-sena/dashboard_sena/dashboard</div>
                    <div class="description">Dashboard con routing</div>
                </div>
                <a href="/Gestion-sena/dashboard_sena/dashboard" class="btn btn-view" target="_blank">Abrir</a>
            </div>
        </div>

        <!-- URLs de Módulos -->
        <div class="test-section">
            <h2>📦 Módulos del Sistema</h2>
            
            <?php
            $modules = [
                'asignacion' => ['name' => 'Asignaciones', 'icon' => '📅'],
                'instructor' => ['name' => 'Instructores', 'icon' => '👨‍🏫'],
                'ficha' => ['name' => 'Fichas', 'icon' => '📄'],
                'programa' => ['name' => 'Programas', 'icon' => '📚'],
                'ambiente' => ['name' => 'Ambientes', 'icon' => '🏢'],
                'competencia' => ['name' => 'Competencias', 'icon' => '🎯']
            ];
            
            foreach ($modules as $module => $info) {
                echo '<div style="margin-bottom: 24px;">';
                echo '<h3 style="color: #1f2937; margin-bottom: 12px;">' . $info['icon'] . ' ' . $info['name'] . '</h3>';
                
                $actions = [
                    'index' => 'Listar todos',
                    'create' => 'Crear nuevo',
                    'show/1' => 'Ver detalle (ID: 1)',
                    'edit/1' => 'Editar (ID: 1)'
                ];
                
                foreach ($actions as $action => $description) {
                    $url = "/Gestion-sena/dashboard_sena/$module/$action";
                    echo '<div class="url-test">';
                    echo '<div>';
                    echo '<div class="url">' . htmlspecialchars($url) . '</div>';
                    echo '<div class="description">' . $description . '</div>';
                    echo '</div>';
                    echo '<a href="' . $url . '" class="btn btn-view" target="_blank">Abrir</a>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
        </div>

        <!-- URLs de API -->
        <div class="test-section">
            <h2>🔌 Endpoints de API</h2>
            
            <div class="url-test">
                <div>
                    <div class="url">/Gestion-sena/dashboard_sena/api/search.php?q=test</div>
                    <div class="description">Búsqueda global</div>
                </div>
                <a href="/Gestion-sena/dashboard_sena/api/search.php?q=test" class="btn btn-test" target="_blank">Probar</a>
            </div>
            
            <div class="url-test">
                <div>
                    <div class="url">/Gestion-sena/dashboard_sena/api/notifications.php</div>
                    <div class="description">Notificaciones</div>
                </div>
                <a href="/Gestion-sena/dashboard_sena/api/notifications.php" class="btn btn-test" target="_blank">Probar</a>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="test-section">
            <h2>📖 Instrucciones de Uso</h2>
            
            <div class="info-box">
                <h3>✅ URLs Funcionando Correctamente</h3>
                <p>Si todas las URLs abren correctamente sin errores 404, el sistema de routing está funcionando perfectamente.</p>
            </div>
            
            <div class="warning-box">
                <h3>⚠️ Si ves errores 404</h3>
                <p>Verifica que:</p>
                <ul style="margin-left: 20px; margin-top: 8px;">
                    <li>mod_rewrite esté habilitado en Apache</li>
                    <li>El archivo .htaccess exista en la raíz del proyecto</li>
                    <li>AllowOverride esté configurado en Apache</li>
                </ul>
            </div>
            
            <div class="success-box">
                <h3>🎯 Estructura de URLs</h3>
                <p>El sistema soporta las siguientes estructuras:</p>
                <div class="code">
/dashboard_sena/                    → Dashboard principal
/dashboard_sena/modulo              → Listar módulo
/dashboard_sena/modulo/create       → Crear nuevo
/dashboard_sena/modulo/show/ID      → Ver detalle
/dashboard_sena/modulo/edit/ID      → Editar registro</div>
            </div>
        </div>

        <!-- Información Técnica -->
        <div class="test-section">
            <h2>🔧 Información Técnica</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px; font-weight: 600;">REQUEST_URI</td>
                    <td style="padding: 12px; font-family: monospace; color: #3b82f6;">
                        <?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px; font-weight: 600;">SCRIPT_NAME</td>
                    <td style="padding: 12px; font-family: monospace; color: #3b82f6;">
                        <?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px; font-weight: 600;">DOCUMENT_ROOT</td>
                    <td style="padding: 12px; font-family: monospace; color: #3b82f6;">
                        <?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px; font-weight: 600;">PHP Version</td>
                    <td style="padding: 12px; font-family: monospace; color: #3b82f6;">
                        <?php echo phpversion(); ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
