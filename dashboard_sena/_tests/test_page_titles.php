<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sistema de Títulos Dinámicos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #39A900;
        }
        h2 {
            color: #007832;
            border-bottom: 2px solid #e8f5e9;
            padding-bottom: 10px;
        }
        .success {
            color: #10b981;
            font-weight: bold;
        }
        .error {
            color: #ef4444;
            font-weight: bold;
        }
        .info {
            background: #e8f5e9;
            padding: 15px;
            border-left: 4px solid #39A900;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #e8f5e9;
            font-weight: bold;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <h1>🧪 Test Sistema de Títulos Dinámicos</h1>
    
    <?php
    // Cargar el helper de títulos
    $helperPath = __DIR__ . '/../helpers/page_titles.php';
    
    echo '<div class="test-section">';
    echo '<h2>1. Verificación de Archivos</h2>';
    
    if (file_exists($helperPath)) {
        echo '<p class="success">✓ Archivo page_titles.php encontrado</p>';
        echo '<p class="info">Ruta: ' . $helperPath . '</p>';
        require_once $helperPath;
    } else {
        echo '<p class="error">✗ Archivo page_titles.php NO encontrado</p>';
        echo '<p class="info">Ruta esperada: ' . $helperPath . '</p>';
        exit;
    }
    echo '</div>';
    
    // Test de funciones
    echo '<div class="test-section">';
    echo '<h2>2. Test de Funciones</h2>';
    
    if (function_exists('getPageTitle')) {
        echo '<p class="success">✓ Función getPageTitle() existe</p>';
    } else {
        echo '<p class="error">✗ Función getPageTitle() NO existe</p>';
    }
    
    if (function_exists('getDocumentTitle')) {
        echo '<p class="success">✓ Función getDocumentTitle() existe</p>';
    } else {
        echo '<p class="error">✗ Función getDocumentTitle() NO existe</p>';
    }
    
    if (function_exists('getAutoBreadcrumbs')) {
        echo '<p class="success">✓ Función getAutoBreadcrumbs() existe</p>';
    } else {
        echo '<p class="error">✗ Función getAutoBreadcrumbs() NO existe</p>';
    }
    echo '</div>';
    
    // Simular diferentes rutas
    echo '<div class="test-section">';
    echo '<h2>3. Simulación de Títulos por Ruta</h2>';
    
    $testCases = [
        ['path' => '/Gestion-sena/dashboard_sena/index.php', 'file' => 'index.php', 'expected' => 'Dashboard Principal'],
        ['path' => '/Gestion-sena/dashboard_sena/views/asignacion/index.php', 'file' => 'index.php', 'expected' => 'Gestión de Asignaciones'],
        ['path' => '/Gestion-sena/dashboard_sena/views/asignacion/crear.php', 'file' => 'crear.php', 'expected' => 'Crear Asignaciones'],
        ['path' => '/Gestion-sena/dashboard_sena/views/instructor/index.php', 'file' => 'index.php', 'expected' => 'Gestión de Instructores'],
        ['path' => '/Gestion-sena/dashboard_sena/views/ficha/editar.php', 'file' => 'editar.php', 'expected' => 'Editar Fichas'],
        ['path' => '/Gestion-sena/dashboard_sena/views/ambiente/ver.php', 'file' => 'ver.php', 'expected' => 'Ver Detalle de Ambientes'],
    ];
    
    echo '<table>';
    echo '<thead><tr><th>Ruta</th><th>Archivo</th><th>Título Esperado</th><th>Título Obtenido</th><th>Estado</th></tr></thead>';
    echo '<tbody>';
    
    foreach ($testCases as $test) {
        // Simular la ruta
        $_SERVER['REQUEST_URI'] = $test['path'];
        $_SERVER['PHP_SELF'] = $test['file'];
        
        $title = getPageTitle();
        $match = ($title === $test['expected']);
        
        echo '<tr>';
        echo '<td><code>' . htmlspecialchars($test['path']) . '</code></td>';
        echo '<td><code>' . htmlspecialchars($test['file']) . '</code></td>';
        echo '<td>' . htmlspecialchars($test['expected']) . '</td>';
        echo '<td><strong>' . htmlspecialchars($title) . '</strong></td>';
        echo '<td>' . ($match ? '<span class="success">✓ OK</span>' : '<span class="error">✗ FAIL</span>') . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    echo '</div>';
    
    // Test de breadcrumbs
    echo '<div class="test-section">';
    echo '<h2>4. Test de Breadcrumbs</h2>';
    
    $_SERVER['REQUEST_URI'] = '/Gestion-sena/dashboard_sena/views/asignacion/crear.php';
    $_SERVER['PHP_SELF'] = 'crear.php';
    
    $breadcrumbs = getAutoBreadcrumbs();
    
    if (!empty($breadcrumbs)) {
        echo '<p class="success">✓ Breadcrumbs generados correctamente</p>';
        echo '<ul>';
        foreach ($breadcrumbs as $crumb) {
            echo '<li>' . htmlspecialchars($crumb['label']);
            if ($crumb['url']) {
                echo ' → <code>' . htmlspecialchars($crumb['url']) . '</code>';
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="error">✗ No se generaron breadcrumbs</p>';
    }
    echo '</div>';
    
    // Información del sistema
    echo '<div class="test-section">';
    echo '<h2>5. Información del Sistema</h2>';
    echo '<table>';
    echo '<tr><th>Variable</th><th>Valor</th></tr>';
    echo '<tr><td>PHP Version</td><td>' . phpversion() . '</td></tr>';
    echo '<tr><td>REQUEST_URI actual</td><td><code>' . htmlspecialchars($_SERVER['REQUEST_URI']) . '</code></td></tr>';
    echo '<tr><td>PHP_SELF actual</td><td><code>' . htmlspecialchars($_SERVER['PHP_SELF']) . '</code></td></tr>';
    echo '<tr><td>Título actual</td><td><strong>' . htmlspecialchars(getPageTitle()) . '</strong></td></tr>';
    echo '<tr><td>Título documento</td><td><strong>' . htmlspecialchars(getDocumentTitle()) . '</strong></td></tr>';
    echo '</table>';
    echo '</div>';
    ?>
    
    <div class="test-section">
        <h2>6. Instrucciones</h2>
        <div class="info">
            <p><strong>Para probar en el dashboard real:</strong></p>
            <ol>
                <li>Abre el dashboard principal: <code>/Gestion-sena/dashboard_sena/index.php</code></li>
                <li>Navega a diferentes secciones usando el menú lateral</li>
                <li>Verifica que el título del header cambie según la sección</li>
                <li>Si no ves el título, abre las DevTools del navegador (F12) y verifica:
                    <ul>
                        <li>Que el elemento <code>&lt;h1&gt;</code> existe en el DOM</li>
                        <li>Que no hay estilos CSS ocultándolo (display: none, visibility: hidden, opacity: 0)</li>
                        <li>Que no hay errores en la consola</li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>
</body>
</html>
