<?php
/**
 * Script de diagnóstico para instru_competencia
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico de Instru_Competencia</h1><pre>";

try {
    echo "1. Cargando conexión...\n";
    require_once __DIR__ . '/../conexion.php';
    $db = Database::getInstance()->getConnection();
    echo "   ✓ Conexión exitosa\n\n";
    
    echo "2. Probando consulta SQL...\n";
    $sql = "
        SELECT ic.*,
               CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
               p.prog_denominacion,
               c.comp_nombre_corto
        FROM INSTRU_COMPETENCIA ic
        LEFT JOIN INSTRUCTOR i ON ic.INSTRUCTOR_inst_id = i.inst_id
        LEFT JOIN COMPETxPROGRAMA cp ON ic.COMPETxPROGRAMA_PROGRAMA_prog_id = cp.PROGRAMA_prog_id 
            AND ic.COMPETxPROGRAMA_COMPETENCIA_comp_id = cp.COMPETENCIA_comp_id
        LEFT JOIN PROGRAMA p ON cp.PROGRAMA_prog_id = p.prog_codigo
        LEFT JOIN COMPETENCIA c ON cp.COMPETENCIA_comp_id = c.comp_id
        ORDER BY ic.inscomp_vigencia DESC
        LIMIT 5
    ";
    
    $stmt = $db->query($sql);
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   ✓ Consulta ejecutada: " . count($registros) . " registros\n\n";
    
    if (!empty($registros)) {
        echo "3. Datos de ejemplo:\n";
        foreach ($registros as $reg) {
            echo "   - ID: " . ($reg['inscomp_id'] ?? 'N/A') . "\n";
            echo "     Instructor: " . ($reg['instructor_nombre'] ?? 'N/A') . "\n";
            echo "     Programa: " . ($reg['prog_denominacion'] ?? 'N/A') . "\n";
            echo "     Competencia: " . ($reg['comp_nombre_corto'] ?? 'N/A') . "\n\n";
        }
    }
    
    echo "4. Cargando modelo...\n";
    require_once __DIR__ . '/../model/InstruCompetenciaModel.php';
    $model = new InstruCompetenciaModel();
    echo "   ✓ Modelo cargado\n\n";
    
    echo "5. Obteniendo datos con modelo...\n";
    $registros = $model->getAll();
    echo "   ✓ Registros obtenidos: " . count($registros) . "\n\n";
    
    echo "6. Cargando otros modelos...\n";
    require_once __DIR__ . '/../model/InstructorModel.php';
    require_once __DIR__ . '/../model/ProgramaModel.php';
    require_once __DIR__ . '/../model/CompetenciaModel.php';
    
    $instructorModel = new InstructorModel();
    $programaModel = new ProgramaModel();
    $competenciaModel = new CompetenciaModel();
    
    $instructores = $instructorModel->getAll();
    $programas = $programaModel->getAll();
    $competencias = $competenciaModel->getAll();
    
    echo "   ✓ Instructores: " . count($instructores) . "\n";
    echo "   ✓ Programas: " . count($programas) . "\n";
    echo "   ✓ Competencias: " . count($competencias) . "\n\n";
    
    echo "7. Verificando campos en registros...\n";
    if (!empty($registros)) {
        $primer_registro = $registros[0];
        $campos_requeridos = ['inscomp_id', 'instructor_nombre', 'prog_denominacion', 'comp_nombre_corto', 'inscomp_vigencia'];
        
        foreach ($campos_requeridos as $campo) {
            if (isset($primer_registro[$campo])) {
                echo "   ✓ Campo '$campo' existe\n";
            } else {
                echo "   ✗ Campo '$campo' NO EXISTE\n";
            }
        }
    }
    
    echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
    echo "✓ Todo parece funcionar correctamente\n";
    
} catch (PDOException $e) {
    echo "\n✗ ERROR SQL:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
} catch (Exception $e) {
    echo "\n✗ ERROR:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
