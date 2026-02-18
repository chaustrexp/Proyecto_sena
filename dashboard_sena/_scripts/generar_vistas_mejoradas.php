<?php
/**
 * Script para generar vistas mejoradas automáticamente
 * Ejecutar desde: http://localhost:8000/generar_vistas_mejoradas.php
 */

$secciones = [
    'instructor' => [
        'titulo' => 'Instructores',
        'subtitulo' => 'Gestiona los instructores del SENA',
        'icono' => 'users',
        'color' => '#8b5cf6',
        'bg' => '#F5F3FF',
        'campos' => ['nombre', 'documento', 'email', 'telefono'],
        'emoji' => '👨‍🏫'
    ],
    'ambiente' => [
        'titulo' => 'Ambientes',
        'subtitulo' => 'Gestiona los ambientes de formación',
        'icono' => 'home',
        'color' => '#f59e0b',
        'bg' => '#FEF3C7',
        'campos' => ['nombre', 'codigo', 'capacidad', 'tipo'],
        'emoji' => '🏫'
    ],
    'asignacion' => [
        'titulo' => 'Asignaciones',
        'subtitulo' => 'Gestiona las asignaciones de instructores',
        'icono' => 'calendar',
        'color' => '#ec4899',
        'bg' => '#FCE7F3',
        'campos' => ['ficha_numero', 'instructor_nombre', 'ambiente_nombre', 'fecha_inicio'],
        'emoji' => '📅'
    ],
    'competencia' => [
        'titulo' => 'Competencias',
        'subtitulo' => 'Gestiona las competencias de formación',
        'icono' => 'target',
        'color' => '#10b981',
        'bg' => '#D1FAE5',
        'campos' => ['codigo', 'nombre', 'descripcion'],
        'emoji' => '🎯'
    ],
    'centro_formacion' => [
        'titulo' => 'Centros de Formación',
        'subtitulo' => 'Gestiona los centros de formación',
        'icono' => 'building-2',
        'color' => '#6366f1',
        'bg' => '#E0E7FF',
        'campos' => ['nombre', 'codigo', 'direccion', 'telefono'],
        'emoji' => '🏢'
    ],
    'sede' => [
        'titulo' => 'Sedes',
        'subtitulo' => 'Gestiona las sedes del SENA',
        'icono' => 'map-pin',
        'color' => '#ef4444',
        'bg' => '#FEE2E2',
        'campos' => ['nombre', 'direccion', 'ciudad'],
        'emoji' => '📍'
    ],
    'coordinacion' => [
        'titulo' => 'Coordinaciones',
        'subtitulo' => 'Gestiona las coordinaciones académicas',
        'icono' => 'users',
        'color' => '#14b8a6',
        'bg' => '#CCFBF1',
        'campos' => ['nombre', 'responsable'],
        'emoji' => '👥'
    ],
    'titulo_programa' => [
        'titulo' => 'Títulos de Programa',
        'subtitulo' => 'Gestiona los títulos académicos',
        'icono' => 'graduation-cap',
        'color' => '#a855f7',
        'bg' => '#F3E8FF',
        'campos' => ['nombre', 'nivel'],
        'emoji' => '🎓'
    ]
];

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Generador de Vistas</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f5f7fa; }
        .seccion { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #39A900; }
        .success { color: #39A900; }
        .error { color: #ef4444; }
    </style>
</head>
<body>
    <h1>🚀 Generador de Vistas Mejoradas</h1>
    <p>Generando vistas con diseño limpio y moderno...</p>
";

foreach ($secciones as $carpeta => $config) {
    echo "<div class='seccion'>";
    echo "<h3>{$config['emoji']} {$config['titulo']}</h3>";
    
    $rutaIndex = __DIR__ . "/views/{$carpeta}/index.php";
    
    if (!file_exists(dirname($rutaIndex))) {
        echo "<p class='error'>❌ La carpeta no existe</p>";
        echo "</div>";
        continue;
    }
    
    // Generar contenido del index.php
    $contenido = generarIndex($carpeta, $config);
    
    // Guardar archivo
    if (file_put_contents($rutaIndex, $contenido)) {
        echo "<p class='success'>✓ index.php generado correctamente</p>";
    } else {
        echo "<p class='error'>❌ Error al generar index.php</p>";
    }
    
    echo "</div>";
}

echo "<div style='background: #E8F5E8; padding: 20px; border-radius: 8px; margin-top: 20px;'>";
echo "<h2>✅ Proceso Completado</h2>";
echo "<p>Todas las vistas han sido generadas con el nuevo diseño.</p>";
echo "<p><a href='index.php' style='color: #39A900; font-weight: bold;'>← Volver al Dashboard</a></p>";
echo "</div>";

echo "</body></html>";

function generarIndex($carpeta, $config) {
    $modelName = ucfirst(str_replace('_', '', ucwords($carpeta, '_'))) . 'Model';
    $singular = rtrim($carpeta, 's');
    
    return "<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/{$modelName}.php';

\$model = new {$modelName}();

if (isset(\$_GET['eliminar'])) {
    \$model->delete(\$_GET['eliminar']);
    header('Location: index.php?msg=eliminado');
    exit;
}

\$registros = \$model->getAll();
\$pageTitle = \"{$config['titulo']}\";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class=\"main-content\">
    <!-- Header -->
    <div style=\"padding: 32px 32px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb;\">
        <div>
            <h1 style=\"font-size: 28px; font-weight: 700; color: #1f2937; margin: 0 0 4px;\">{$config['titulo']}</h1>
            <p style=\"font-size: 14px; color: #6b7280; margin: 0;\">{$config['subtitulo']}</p>
        </div>
        <a href=\"crear.php\" class=\"btn btn-primary\" style=\"display: inline-flex; align-items: center; gap: 8px;\">
            <i data-lucide=\"plus\" style=\"width: 18px; height: 18px;\"></i>
            Nuevo
        </a>
    </div>

    <!-- Alert -->
    <?php if (isset(\$_GET['msg'])): ?>
        <div class=\"alert alert-success\" style=\"margin: 24px 32px;\">
            <?php 
            if (\$_GET['msg'] == 'creado') echo '✓ Registro creado exitosamente';
            if (\$_GET['msg'] == 'actualizado') echo '✓ Registro actualizado exitosamente';
            if (\$_GET['msg'] == 'eliminado') echo '✓ Registro eliminado exitosamente';
            ?>
        </div>
    <?php endif; ?>

    <!-- Stats -->
    <div style=\"display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 24px 32px;\">
        <div style=\"background: white; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb;\">
            <div style=\"font-size: 13px; color: #6b7280; margin-bottom: 8px;\">Total Registros</div>
            <div style=\"font-size: 32px; font-weight: 700; color: {$config['color']};\"><?php echo count(\$registros); ?></div>
        </div>
        <div style=\"background: white; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb;\">
            <div style=\"font-size: 13px; color: #6b7280; margin-bottom: 8px;\">Activos</div>
            <div style=\"font-size: 32px; font-weight: 700; color: #39A900;\"><?php echo count(\$registros); ?></div>
        </div>
    </div>

    <!-- Table -->
    <div style=\"padding: 0 32px 32px;\">
        <div style=\"background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;\">
            <table style=\"width: 100%; border-collapse: collapse;\">
                <thead>
                    <tr style=\"background: #f9fafb; border-bottom: 1px solid #e5e7eb;\">
                        " . generarHeaders($config['campos']) . "
                        <th style=\"padding: 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;\">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty(\$registros)): ?>
                    <tr>
                        <td colspan=\"" . (count($config['campos']) + 1) . "\" style=\"text-align: center; padding: 60px 20px; color: #6b7280;\">
                            <div style=\"font-size: 48px; margin-bottom: 16px;\">{$config['emoji']}</div>
                            <p style=\"margin: 0 0 16px; font-size: 16px;\">No hay registros</p>
                            <a href=\"crear.php\" class=\"btn btn-primary btn-sm\">Crear Primero</a>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach (\$registros as \$registro): ?>
                        <tr style=\"border-bottom: 1px solid #f3f4f6;\">
                            " . generarCeldas($config['campos']) . "
                            <td style=\"padding: 16px;\">
                                <div class=\"btn-group\" style=\"justify-content: flex-end;\">
                                    <a href=\"ver.php?id=<?php echo \$registro['id']; ?>\" class=\"btn btn-secondary btn-sm\">Ver</a>
                                    <a href=\"editar.php?id=<?php echo \$registro['id']; ?>\" class=\"btn btn-primary btn-sm\">Editar</a>
                                    <button onclick=\"confirmarEliminacion(<?php echo \$registro['id']; ?>, '{$singular}')\" class=\"btn btn-danger btn-sm\">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    document.querySelectorAll('tbody tr').forEach(row => {
        if (row.cells.length > 1) {
            row.addEventListener('mouseenter', function() {
                this.style.background = '#f9fafb';
            });
            row.addEventListener('mouseleave', function() {
                this.style.background = 'white';
            });
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
";
}

function generarHeaders($campos) {
    $html = '';
    foreach ($campos as $campo) {
        $label = ucwords(str_replace('_', ' ', $campo));
        $html .= "<th style=\"padding: 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;\">{$label}</th>\n                        ";
    }
    return $html;
}

function generarCeldas($campos) {
    $html = '';
    foreach ($campos as $i => $campo) {
        if ($i == 0) {
            $html .= "<td style=\"padding: 16px;\">
                                <strong style=\"color: #3b82f6; font-size: 14px;\"><?php echo \$registro['{$campo}']; ?></strong>
                            </td>\n                            ";
        } else {
            $html .= "<td style=\"padding: 16px; color: #6b7280;\">
                                <?php echo \$registro['{$campo}']; ?>
                            </td>\n                            ";
        }
    }
    return $html;
}
?>
