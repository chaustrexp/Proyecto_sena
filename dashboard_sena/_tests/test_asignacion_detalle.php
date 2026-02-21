<?php
// Test para diagnosticar el problema en ver.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../model/AsignacionModel.php';
require_once __DIR__ . '/../helpers/functions.php';

echo "<h1>Diagnóstico de Detalle de Asignación</h1>";

// Obtener el ID de la URL o usar el primero disponible
$id = $_GET['id'] ?? null;

$model = new AsignacionModel();

// Si no hay ID, obtener el primero disponible
if (!$id) {
    echo "<h2>Obteniendo primera asignación disponible...</h2>";
    $todas = $model->getAll();
    if (!empty($todas)) {
        $primera = $todas[0];
        $id = $primera['asig_id'] ?? $primera['ASIG_ID'] ?? $primera['id'] ?? null;
        echo "<p>ID encontrado: <strong>$id</strong></p>";
    } else {
        echo "<p style='color: red;'>No hay asignaciones en la base de datos</p>";
        exit;
    }
}

echo "<h2>Consultando asignación con ID: $id</h2>";

try {
    $registro = $model->getById($id);
    
    echo "<h3>Resultado de getById():</h3>";
    echo "<pre>";
    print_r($registro);
    echo "</pre>";
    
    if ($registro) {
        echo "<h3>Campos disponibles:</h3>";
        echo "<ul>";
        foreach ($registro as $key => $value) {
            echo "<li><strong>$key</strong>: " . htmlspecialchars($value ?? 'NULL') . "</li>";
        }
        echo "</ul>";
        
        echo "<h3>Prueba de funciones safe:</h3>";
        echo "<ul>";
        echo "<li>safe(\$registro, 'asig_id'): " . safe($registro, 'asig_id', 'NO ENCONTRADO') . "</li>";
        echo "<li>safe(\$registro, 'ASIG_ID'): " . safe($registro, 'ASIG_ID', 'NO ENCONTRADO') . "</li>";
        echo "<li>safe(\$registro, 'id'): " . safe($registro, 'id', 'NO ENCONTRADO') . "</li>";
        echo "<li>safe(\$registro, 'ficha_numero'): " . safe($registro, 'ficha_numero', 'NO ENCONTRADO') . "</li>";
        echo "<li>safe(\$registro, 'instructor_nombre'): " . safe($registro, 'instructor_nombre', 'NO ENCONTRADO') . "</li>";
        echo "</ul>";
        
        echo "<h3>Prueba de registroValido():</h3>";
        echo "<p>registroValido(\$registro): " . (registroValido($registro) ? 'TRUE' : 'FALSE') . "</p>";
        
    } else {
        echo "<p style='color: red;'>getById() devolvió FALSE o NULL</p>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Error:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h3>Probar ver.php con este ID:</h3>";
echo "<a href='../views/asignacion/ver.php?id=$id' style='padding: 10px 20px; background: #39A900; color: white; text-decoration: none; border-radius: 5px;'>Ir a ver.php?id=$id</a>";
?>
