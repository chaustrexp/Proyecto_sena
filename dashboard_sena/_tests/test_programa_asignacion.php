<?php
/**
 * Script de prueba para verificar que el campo programa_nombre
 * se obtiene correctamente en las asignaciones
 */

require_once __DIR__ . '/../model/AsignacionModel.php';

echo "=== TEST: Verificación de programa_nombre en Asignaciones ===\n\n";

try {
    $model = new AsignacionModel();
    
    // Obtener todas las asignaciones
    echo "1. Obteniendo todas las asignaciones...\n";
    $asignaciones = $model->getAll();
    
    if (empty($asignaciones)) {
        echo "   ⚠️  No hay asignaciones en la base de datos\n\n";
    } else {
        echo "   ✓ Se encontraron " . count($asignaciones) . " asignaciones\n\n";
        
        // Verificar que cada asignación tenga el campo programa_nombre
        echo "2. Verificando campos en cada asignación:\n";
        $errores = 0;
        
        foreach ($asignaciones as $index => $asig) {
            $num = $index + 1;
            echo "\n   Asignación #{$num}:\n";
            echo "   - ID: " . ($asig['asig_id'] ?? 'N/A') . "\n";
            echo "   - Ficha: " . str_pad($asig['ficha_numero'] ?? '', 8, '0', STR_PAD_LEFT) . "\n";
            
            // Verificar programa_nombre
            if (isset($asig['programa_nombre'])) {
                echo "   - Programa: ✓ " . $asig['programa_nombre'] . "\n";
            } else {
                echo "   - Programa: ✗ NO DISPONIBLE\n";
                $errores++;
            }
            
            echo "   - Instructor: " . ($asig['instructor_nombre'] ?? 'N/A') . "\n";
            echo "   - Ambiente: " . ($asig['ambiente_nombre'] ?? 'N/A') . "\n";
            echo "   - Competencia: " . ($asig['competencia_nombre'] ?? 'N/A') . "\n";
        }
        
        echo "\n\n3. Resumen:\n";
        if ($errores === 0) {
            echo "   ✓ TODAS las asignaciones tienen el campo programa_nombre\n";
        } else {
            echo "   ✗ {$errores} asignaciones NO tienen el campo programa_nombre\n";
        }
    }
    
    // Probar getById
    if (!empty($asignaciones)) {
        echo "\n\n4. Probando getById con la primera asignación:\n";
        $primeraId = $asignaciones[0]['asig_id'];
        $asignacion = $model->getById($primeraId);
        
        if ($asignacion) {
            echo "   ✓ Asignación encontrada\n";
            echo "   - ID: " . ($asignacion['asig_id'] ?? 'N/A') . "\n";
            echo "   - Ficha: " . str_pad($asignacion['ficha_numero'] ?? '', 8, '0', STR_PAD_LEFT) . "\n";
            
            if (isset($asignacion['programa_nombre'])) {
                echo "   - Programa: ✓ " . $asignacion['programa_nombre'] . "\n";
            } else {
                echo "   - Programa: ✗ NO DISPONIBLE\n";
            }
        } else {
            echo "   ✗ No se pudo obtener la asignación\n";
        }
    }
    
    echo "\n\n=== FIN DEL TEST ===\n";
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
