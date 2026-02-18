<?php
/**
 * Script para generar automáticamente las vistas CRUD restantes
 * Ejecutar: php generar_vistas.php
 */

$modulos = [
    'ficha' => [
        'campos' => ['numero', 'programa_id', 'fecha_inicio', 'fecha_fin', 'estado'],
        'relaciones' => ['programa_id' => 'programa'],
        'display' => ['numero', 'programa_nombre', 'fecha_inicio', 'fecha_fin', 'estado']
    ],
    'instructor' => [
        'campos' => ['nombre', 'documento', 'email', 'telefono', 'centro_formacion_id'],
        'relaciones' => ['centro_formacion_id' => 'centro_formacion'],
        'display' => ['nombre', 'documento', 'email', 'telefono', 'centro_nombre']
    ],
    'asignacion' => [
        'campos' => ['ficha_id', 'instructor_id', 'ambiente_id', 'competencia_id', 'fecha_inicio', 'fecha_fin'],
        'relaciones' => ['ficha_id' => 'ficha', 'instructor_id' => 'instructor', 'ambiente_id' => 'ambiente', 'competencia_id' => 'competencia'],
        'display' => ['ficha_numero', 'instructor_nombre', 'ambiente_nombre', 'competencia_nombre', 'fecha_inicio', 'fecha_fin']
    ],
    'competencia' => [
        'campos' => ['codigo', 'nombre', 'descripcion'],
        'relaciones' => [],
        'display' => ['codigo', 'nombre', 'descripcion']
    ],
    'competencia_programa' => [
        'campos' => ['competencia_id', 'programa_id', 'horas'],
        'relaciones' => ['competencia_id' => 'competencia', 'programa_id' => 'programa'],
        'display' => ['competencia_nombre', 'programa_nombre', 'horas']
    ],
    'detalle_asignacion' => [
        'campos' => ['asignacion_id', 'fecha', 'hora_inicio', 'hora_fin', 'observaciones'],
        'relaciones' => ['asignacion_id' => 'asignacion'],
        'display' => ['asignacion_id', 'fecha', 'hora_inicio', 'hora_fin']
    ],
    'sede' => [
        'campos' => ['nombre', 'direccion', 'ciudad'],
        'relaciones' => [],
        'display' => ['nombre', 'direccion', 'ciudad']
    ],
    'coordinacion' => [
        'campos' => ['nombre', 'centro_formacion_id', 'responsable'],
        'relaciones' => ['centro_formacion_id' => 'centro_formacion'],
        'display' => ['nombre', 'centro_nombre', 'responsable']
    ],
    'centro_formacion' => [
        'campos' => ['nombre', 'codigo', 'direccion', 'telefono'],
        'relaciones' => [],
        'display' => ['nombre', 'codigo', 'direccion', 'telefono']
    ],
    'titulo_programa' => [
        'campos' => ['nombre', 'nivel'],
        'relaciones' => [],
        'display' => ['nombre', 'nivel']
    ]
];

function generarIndex($modulo, $config) {
    $moduloTitle = ucwords(str_replace('_', ' ', $modulo));
    $modelClass = str_replace('_', '', ucwords($modulo, '_')) . 'Model';
    
    $displayFields = '';
    foreach ($config['display'] as $field) {
        $displayFields .= "                    <th>" . ucwords(str_replace('_', ' ', $field)) . "</th>\n";
    }
    
    $displayData = '';
    foreach ($config['display'] as $field) {
        $displayData .= "                    <td><?php echo \$registro['$field']; ?></td>\n";
    }
    
    return "<?php
require_once __DIR__ . '/../../model/{$modelClass}.php';

\$model = new {$modelClass}();

if (isset(\$_GET['eliminar'])) {
    \$model->delete(\$_GET['eliminar']);
    header('Location: index.php?msg=eliminado');
    exit;
}

\$registros = \$model->getAll();
\$pageTitle = \"Gestión de {$moduloTitle}\";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class=\"main-content\">
    <?php if (isset(\$_GET['msg'])): ?>
        <div class=\"alert alert-success\">
            <?php 
            if (\$_GET['msg'] == 'creado') echo 'Registro creado exitosamente';
            if (\$_GET['msg'] == 'actualizado') echo 'Registro actualizado exitosamente';
            if (\$_GET['msg'] == 'eliminado') echo 'Registro eliminado exitosamente';
            ?>
        </div>
    <?php endif; ?>

    <div class=\"table-container\">
        <div class=\"table-header\">
            <h2>Listado de {$moduloTitle}</h2>
            <a href=\"crear.php\" class=\"btn btn-primary\">+ Nuevo</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
{$displayFields}                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (\$registros as \$registro): ?>
                <tr>
                    <td><?php echo \$registro['id']; ?></td>
{$displayData}                    <td>
                        <div class=\"btn-group\">
                            <a href=\"ver.php?id=<?php echo \$registro['id']; ?>\" class=\"btn btn-secondary btn-sm\">Ver</a>
                            <a href=\"editar.php?id=<?php echo \$registro['id']; ?>\" class=\"btn btn-primary btn-sm\">Editar</a>
                            <button onclick=\"confirmarEliminacion(<?php echo \$registro['id']; ?>, '{$modulo}')\" class=\"btn btn-danger btn-sm\">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>";
}

function generarCrear($modulo, $config) {
    $moduloTitle = ucwords(str_replace('_', ' ', $modulo));
    $modelClass = str_replace('_', '', ucwords($modulo, '_')) . 'Model';
    
    $formFields = '';
    foreach ($config['campos'] as $campo) {
        if (isset($config['relaciones'][$campo])) {
            $relacion = $config['relaciones'][$campo];
            $relacionModel = str_replace('_', '', ucwords($relacion, '_')) . 'Model';
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <select name=\"{$campo}\" class=\"form-control\">
                    <option value=\"\">Seleccione...</option>
                    <?php foreach (\${$relacion}s as \$item): ?>
                        <option value=\"<?php echo \$item['id']; ?>\"><?php echo \$item['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>\n";
        } elseif (strpos($campo, 'fecha') !== false) {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <input type=\"date\" name=\"{$campo}\" class=\"form-control\">
            </div>\n";
        } elseif (strpos($campo, 'hora') !== false) {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <input type=\"time\" name=\"{$campo}\" class=\"form-control\">
            </div>\n";
        } elseif ($campo == 'descripcion' || $campo == 'observaciones') {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <textarea name=\"{$campo}\" class=\"form-control\" rows=\"3\"></textarea>
            </div>\n";
        } else {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . " *</label>
                <input type=\"text\" name=\"{$campo}\" class=\"form-control\" required>
            </div>\n";
        }
    }
    
    $relacionesLoad = '';
    foreach ($config['relaciones'] as $campo => $relacion) {
        $relacionModel = str_replace('_', '', ucwords($relacion, '_')) . 'Model';
        $relacionesLoad .= "require_once __DIR__ . '/../../model/{$relacionModel}.php';\n";
    }
    
    $relacionesInit = '';
    foreach ($config['relaciones'] as $campo => $relacion) {
        $relacionModel = str_replace('_', '', ucwords($relacion, '_')) . 'Model';
        $relacionesInit .= "\${$relacion}Model = new {$relacionModel}();\n\${$relacion}s = \${$relacion}Model->getAll();\n";
    }
    
    return "<?php
require_once __DIR__ . '/../../model/{$modelClass}.php';
{$relacionesLoad}
\$model = new {$modelClass}();
{$relacionesInit}
if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    \$model->create(\$_POST);
    header('Location: index.php?msg=creado');
    exit;
}

\$pageTitle = \"Crear {$moduloTitle}\";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class=\"main-content\">
    <div class=\"form-container\">
        <h2>Crear Nuevo {$moduloTitle}</h2>
        <form method=\"POST\">
{$formFields}            <div class=\"btn-group\">
                <button type=\"submit\" class=\"btn btn-primary\">Guardar</button>
                <a href=\"index.php\" class=\"btn btn-secondary\">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>";
}

function generarEditar($modulo, $config) {
    $moduloTitle = ucwords(str_replace('_', ' ', $modulo));
    $modelClass = str_replace('_', '', ucwords($modulo, '_')) . 'Model';
    
    $formFields = '';
    foreach ($config['campos'] as $campo) {
        if (isset($config['relaciones'][$campo])) {
            $relacion = $config['relaciones'][$campo];
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <select name=\"{$campo}\" class=\"form-control\">
                    <option value=\"\">Seleccione...</option>
                    <?php foreach (\${$relacion}s as \$item): ?>
                        <option value=\"<?php echo \$item['id']; ?>\" <?php echo \$registro['{$campo}'] == \$item['id'] ? 'selected' : ''; ?>>
                            <?php echo \$item['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>\n";
        } elseif (strpos($campo, 'fecha') !== false) {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <input type=\"date\" name=\"{$campo}\" class=\"form-control\" value=\"<?php echo \$registro['{$campo}']; ?>\">
            </div>\n";
        } elseif (strpos($campo, 'hora') !== false) {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <input type=\"time\" name=\"{$campo}\" class=\"form-control\" value=\"<?php echo \$registro['{$campo}']; ?>\">
            </div>\n";
        } elseif ($campo == 'descripcion' || $campo == 'observaciones') {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . "</label>
                <textarea name=\"{$campo}\" class=\"form-control\" rows=\"3\"><?php echo \$registro['{$campo}']; ?></textarea>
            </div>\n";
        } else {
            $formFields .= "            <div class=\"form-group\">
                <label>" . ucwords(str_replace('_', ' ', $campo)) . " *</label>
                <input type=\"text\" name=\"{$campo}\" class=\"form-control\" value=\"<?php echo \$registro['{$campo}']; ?>\" required>
            </div>\n";
        }
    }
    
    $relacionesLoad = '';
    foreach ($config['relaciones'] as $campo => $relacion) {
        $relacionModel = str_replace('_', '', ucwords($relacion, '_')) . 'Model';
        $relacionesLoad .= "require_once __DIR__ . '/../../model/{$relacionModel}.php';\n";
    }
    
    $relacionesInit = '';
    foreach ($config['relaciones'] as $campo => $relacion) {
        $relacionModel = str_replace('_', '', ucwords($relacion, '_')) . 'Model';
        $relacionesInit .= "\${$relacion}Model = new {$relacionModel}();\n\${$relacion}s = \${$relacion}Model->getAll();\n";
    }
    
    return "<?php
require_once __DIR__ . '/../../model/{$modelClass}.php';
{$relacionesLoad}
\$model = new {$modelClass}();
{$relacionesInit}
\$id = \$_GET['id'];
\$registro = \$model->getById(\$id);

if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    \$model->update(\$id, \$_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

\$pageTitle = \"Editar {$moduloTitle}\";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class=\"main-content\">
    <div class=\"form-container\">
        <h2>Editar {$moduloTitle}</h2>
        <form method=\"POST\">
{$formFields}            <div class=\"btn-group\">
                <button type=\"submit\" class=\"btn btn-primary\">Actualizar</button>
                <a href=\"index.php\" class=\"btn btn-secondary\">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>";
}

function generarVer($modulo, $config) {
    $moduloTitle = ucwords(str_replace('_', ' ', $modulo));
    $modelClass = str_replace('_', '', ucwords($modulo, '_')) . 'Model';
    
    $detailRows = '';
    foreach ($config['campos'] as $campo) {
        $detailRows .= "        <div class=\"detail-row\">
            <div class=\"detail-label\">" . ucwords(str_replace('_', ' ', $campo)) . ":</div>
            <div><?php echo \$registro['{$campo}']; ?></div>
        </div>\n";
    }
    
    return "<?php
require_once __DIR__ . '/../../model/{$modelClass}.php';

\$model = new {$modelClass}();
\$id = \$_GET['id'];
\$registro = \$model->getById(\$id);

\$pageTitle = \"Ver {$moduloTitle}\";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class=\"main-content\">
    <div class=\"detail-card\">
        <h2>Detalle de {$moduloTitle}</h2>
        <div class=\"detail-row\">
            <div class=\"detail-label\">ID:</div>
            <div><?php echo \$registro['id']; ?></div>
        </div>
{$detailRows}        <div class=\"detail-row\">
            <div class=\"detail-label\">Fecha Creación:</div>
            <div><?php echo \$registro['created_at']; ?></div>
        </div>
        <div class=\"btn-group\" style=\"margin-top: 20px;\">
            <a href=\"editar.php?id=<?php echo \$registro['id']; ?>\" class=\"btn btn-primary\">Editar</a>
            <a href=\"index.php\" class=\"btn btn-secondary\">Volver</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>";
}

// Generar vistas para cada módulo
foreach ($modulos as $modulo => $config) {
    $dir = __DIR__ . "/views/{$modulo}";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    file_put_contents("{$dir}/index.php", generarIndex($modulo, $config));
    file_put_contents("{$dir}/crear.php", generarCrear($modulo, $config));
    file_put_contents("{$dir}/editar.php", generarEditar($modulo, $config));
    file_put_contents("{$dir}/ver.php", generarVer($modulo, $config));
    
    echo "✓ Vistas generadas para: {$modulo}\n";
}

echo "\n¡Todas las vistas CRUD han sido generadas exitosamente!\n";
?>
