<?php
/**
 * Script de diagnóstico para ver.php
 * Acceder desde: http://localhost/Gestion-sena/dashboard_sena/test_ver_asignacion.php?id=1
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico de ver.php - Detalle de Asignación</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #39A900; color: white; }
</style>";

// 1. Verificar conexión
echo "<h2>1. Verificando conexión a base de datos...</h2>";
try {
    require_once __DIR__ . '/conexion.php';
    $db = Database::getInstance()->getConnection();
    echo "<p class='success'>✓ Conexión exitosa</p>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// 2. Verificar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
echo "<h2>2. ID recibido</h2>";
if ($id > 0) {
    echo "<p class='success'>✓ ID: <strong>$id</strong></p>";
} else {
    echo "<p class='error'>✗ No se proporcionó un ID válido</p>";
    echo "<p class='info'>Buscando primera asignación disponible...</p>";
    
    $stmt = $db->query("SELECT ASIG_ID FROM ASIGNACION LIMIT 1");
    $primera = $stmt->fetch();
    if ($primera) {
        $id = $primera['ASIG_ID'];
        echo "<p class='success'>✓ Usando ID: <strong>$id</strong></p>";
        echo "<p><a href='?id=$id'>Recargar con este ID</a></p>";
    } else {
        echo "<p class='error'>✗ No hay asignaciones en la base de datos</p>";
        exit;
    }
}

// 3. Verificar estructura de tabla ASIGNACION
echo "<h2>3. Estructura de tabla ASIGNACION</h2>";
try {
    $stmt = $db->query("DESCRIBE ASIGNACION");
    $columnas = $stmt->fetchAll();
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
    foreach ($columnas as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}

// 4. Verificar estructura de tabla FICHA
echo "<h2>4. Estructura de tabla FICHA</h2>";
try {
    $stmt = $db->query("DESCRIBE FICHA");
    $columnas = $stmt->fetchAll();
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
    foreach ($columnas as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}

// 5. Probar consulta del modelo
echo "<h2>5. Probando consulta del modelo AsignacionModel::getById($id)</h2>";
try {
    require_once __DIR__ . '/model/AsignacionModel.php';
    $model = new AsignacionModel();
    $registro = $model->getById($id);
    
    if ($registro) {
        echo "<p class='success'>✓ Consulta exitosa</p>";
        echo "<h3>Datos devueltos:</h3>";
        echo "<table>";
        echo "<tr><th>Clave</th><th>Valor</th></tr>";
        foreach ($registro as $key => $value) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($key) . "</strong></td>";
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Verificación de campos críticos:</h3>";
        echo "<ul>";
        echo "<li>¿Existe 'asig_id'? " . (isset($registro['asig_id']) ? "<span class='success'>SÍ: " . $registro['asig_id'] . "</span>" : "<span class='error'>NO</span>") . "</li>";
        echo "<li>¿Existe 'ASIG_ID'? " . (isset($registro['ASIG_ID']) ? "<span class='success'>SÍ: " . $registro['ASIG_ID'] . "</span>" : "<span class='error'>NO</span>") . "</li>";
        echo "<li>¿Existe 'id'? " . (isset($registro['id']) ? "<span class='success'>SÍ: " . $registro['id'] . "</span>" : "<span class='error'>NO</span>") . "</li>";
        echo "<li>¿Existe 'ficha_numero'? " . (isset($registro['ficha_numero']) ? "<span class='success'>SÍ: " . $registro['ficha_numero'] . "</span>" : "<span class='error'>NO</span>") . "</li>";
        echo "<li>¿Existe 'fich_numero'? " . (isset($registro['fich_numero']) ? "<span class='success'>SÍ: " . $registro['fich_numero'] . "</span>" : "<span class='error'>NO</span>") . "</li>";
        echo "</ul>";
        
    } else {
        echo "<p class='error'>✗ La consulta no devolvió resultados</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error en modelo: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// 6. Probar consulta SQL directa
echo "<h2>6. Probando consulta SQL directa</h2>";
try {
    $sql = "
        SELECT a.*,
               a.ASIG_ID as asig_id,
               a.ASIG_ID as id,
               COALESCE(f.fich_numero, f.fich_id) as ficha_numero,
               CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
               amb.amb_nombre as ambiente_nombre,
               c.comp_nombre_corto as competencia_nombre,
               p.prog_denominacion as programa_nombre,
               DATE(a.asig_fecha_ini) as asig_fecha_inicio,
               DATE(a.asig_fecha_fin) as asig_fecha_fin,
               DATE(a.asig_fecha_ini) as fecha_inicio,
               DATE(a.asig_fecha_fin) as fecha_fin
        FROM ASIGNACION a
        LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
        LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
        LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
        LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
        LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
        WHERE a.ASIG_ID = ?
    ";
    
    echo "<p class='info'>SQL:</p>";
    echo "<pre>" . htmlspecialchars($sql) . "</pre>";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $resultado = $stmt->fetch();
    
    if ($resultado) {
        echo "<p class='success'>✓ Consulta SQL directa exitosa</p>";
        echo "<table>";
        echo "<tr><th>Clave</th><th>Valor</th></tr>";
        foreach ($resultado as $key => $value) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($key) . "</strong></td>";
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>✗ No se encontraron resultados</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error en SQL: " . $e->getMessage() . "</p>";
}

// 7. Probar funciones helper
echo "<h2>7. Probando funciones helper</h2>";
try {
    require_once __DIR__ . '/helpers/functions.php';
    echo "<p class='success'>✓ Funciones helper cargadas</p>";
    
    if ($registro) {
        echo "<h3>Pruebas con el registro:</h3>";
        echo "<ul>";
        echo "<li>safe(\$registro, 'asig_id'): <strong>" . safe($registro, 'asig_id', 'NO ENCONTRADO') . "</strong></li>";
        echo "<li>safe(\$registro, 'ASIG_ID'): <strong>" . safe($registro, 'ASIG_ID', 'NO ENCONTRADO') . "</strong></li>";
        echo "<li>safe(\$registro, 'id'): <strong>" . safe($registro, 'id', 'NO ENCONTRADO') . "</strong></li>";
        echo "<li>registroValido(\$registro): <strong>" . (registroValido($registro) ? 'TRUE' : 'FALSE') . "</strong></li>";
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>8. Probar página real</h2>";
echo "<p><a href='views/asignacion/ver.php?id=$id' target='_blank' style='padding: 10px 20px; background: #39A900; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Abrir ver.php?id=$id</a></p>";
?>
