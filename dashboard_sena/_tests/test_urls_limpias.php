<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de URLs Limpias</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #39A900;
            border-bottom: 3px solid #39A900;
            padding-bottom: 10px;
        }
        h2 {
            color: #333;
            margin-top: 30px;
        }
        .module-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .url-list {
            list-style: none;
            padding: 0;
        }
        .url-item {
            padding: 10px;
            margin: 5px 0;
            background: #f9f9f9;
            border-left: 4px solid #39A900;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .url-link {
            color: #0066cc;
            text-decoration: none;
            font-family: monospace;
        }
        .url-link:hover {
            text-decoration: underline;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-get {
            background: #e3f2fd;
            color: #1976d2;
        }
        .badge-post {
            background: #fff3e0;
            color: #f57c00;
        }
        .summary {
            background: #e8f5e9;
            border: 2px solid #4caf50;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .summary h3 {
            color: #2e7d32;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <h1>🧪 Test de URLs Limpias - Sistema SENA</h1>
    
    <div class="summary">
        <h3>✅ Sistema de URLs Limpias Activo</h3>
        <p>Todas las URLs ahora muestran explícitamente el módulo y la acción:</p>
        <code>/dashboard_sena/{modulo}/{accion}/{id}</code>
    </div>

    <?php
    $modulos = [
        'dashboard' => 'Dashboard Principal',
        'asignacion' => 'Asignaciones',
        'ficha' => 'Fichas',
        'instructor' => 'Instructores',
        'ambiente' => 'Ambientes',
        'programa' => 'Programas',
        'competencia' => 'Competencias',
        'competencia_programa' => 'Competencia-Programa',
        'titulo_programa' => 'Título Programa',
        'instru_competencia' => 'Instructor-Competencia',
        'detalle_asignacion' => 'Detalle Asignación',
        'centro_formacion' => 'Centro de Formación',
        'coordinacion' => 'Coordinación',
        'sede' => 'Sedes'
    ];

    $basePath = '/Gestion-sena/dashboard_sena';

    foreach ($modulos as $modulo => $nombre) {
        echo "<div class='module-section'>";
        echo "<h2>📁 {$nombre}</h2>";
        echo "<ul class='url-list'>";
        
        // Index
        echo "<li class='url-item'>";
        echo "<a href='{$basePath}/{$modulo}/index' class='url-link' target='_blank'>{$basePath}/{$modulo}/index</a>";
        echo "<span class='badge badge-get'>GET</span>";
        echo "</li>";
        
        // Crear (GET)
        echo "<li class='url-item'>";
        echo "<a href='{$basePath}/{$modulo}/crear' class='url-link' target='_blank'>{$basePath}/{$modulo}/crear</a>";
        echo "<span class='badge badge-get'>GET</span>";
        echo "</li>";
        
        // Ver (ejemplo con ID 1)
        if ($modulo !== 'dashboard') {
            echo "<li class='url-item'>";
            echo "<span class='url-link'>{$basePath}/{$modulo}/ver/1</span>";
            echo "<span class='badge badge-get'>GET</span>";
            echo "</li>";
            
            // Editar (ejemplo con ID 1)
            echo "<li class='url-item'>";
            echo "<span class='url-link'>{$basePath}/{$modulo}/editar/1</span>";
            echo "<span class='badge badge-get'>GET</span>";
            echo "</li>";
            
            // Eliminar (ejemplo con ID 1)
            echo "<li class='url-item'>";
            echo "<span class='url-link'>{$basePath}/{$modulo}/eliminar/1</span>";
            echo "<span class='badge badge-get'>GET</span>";
            echo "</li>";
        }
        
        echo "</ul>";
        echo "</div>";
    }
    ?>

    <div class="summary">
        <h3>🔄 Redirecciones Automáticas</h3>
        <p>Si accedes a una URL sin acción, el sistema redirige automáticamente:</p>
        <ul>
            <li><code>/dashboard_sena/ambiente</code> → <code>/dashboard_sena/ambiente/index</code></li>
            <li><code>/dashboard_sena/instructor</code> → <code>/dashboard_sena/instructor/index</code></li>
            <li><code>/dashboard_sena/dashboard</code> → <code>/dashboard_sena/dashboard/index</code></li>
        </ul>
    </div>

    <div class="summary">
        <h3>📊 Estadísticas</h3>
        <ul>
            <li><strong>Total de módulos:</strong> <?php echo count($modulos); ?></li>
            <li><strong>URLs por módulo:</strong> 5 (index, crear, ver, editar, eliminar)</li>
            <li><strong>Total de URLs:</strong> <?php echo count($modulos) * 5; ?></li>
            <li><strong>Formato:</strong> <code>/dashboard_sena/{modulo}/{accion}/{id}</code></li>
        </ul>
    </div>

    <div style="text-align: center; margin-top: 40px; padding: 20px; background: white; border-radius: 8px;">
        <p style="color: #666; margin: 0;">
            <strong>Sistema de Gestión SENA</strong><br>
            URLs Limpias v2.1.0 - Febrero 2024
        </p>
    </div>
</body>
</html>
