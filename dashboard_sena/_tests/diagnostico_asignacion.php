<?php
/**
 * Script de diagnóstico para la página de asignaciones
 * Muestra errores detallados
 */

// Activar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Diagnóstico de Asignaciones</h1>";
echo "<pre>";

try {
    echo "1. Verificando conexión a la base de datos...\n";
    require_once __DIR__ . '/../conexion.php';
    $db = Database::getInstance()->getConnection();
    echo "   ✓ Conexión exitosa\n\n";
    
    echo "2. Verificando tabla FICHA...\n";
    $stmt = $db->query("DESCRIBE FICHA");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_fich_numero = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'fich_numero') {
            $has_fich_numero = true;
            echo "   ✓ Campo fich_numero existe\n";
        }
    }
    if (!$has_fich_numero) {
        echo "   ✗ ERROR: Campo fich_numero NO EXISTE\n";
        echo "   → Ejecuta: http://localhost/Gestion-sena/dashboard_sena/_scripts/agregar_campo_fich_numero.php\n\n";
        exit;
    }
    echo "\n";
    
    echo "3. Probando consulta de asignaciones...\n";
    $sql = "
        SELECT a.*,
               a.ASIG_ID as asig_id,
               f.fich_numero as ficha_numero,
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
        ORDER BY a.asig_fecha_ini DESC
        LIMIT 5
    ";
    
    $stmt = $db->query($sql);
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   ✓ Consulta ejecutada exitosamente\n";
    echo "   ✓ Se encontraron " . count($registros) . " registros\n\n";
    
    if (!empty($registros)) {
        echo "4. Datos de ejemplo:\n";
        foreach ($registros as $i => $reg) {
            echo "   Registro " . ($i + 1) . ":\n";
            echo "   - ID: " . ($reg['asig_id'] ?? 'N/A') . "\n";
            echo "   - Ficha: " . ($reg['ficha_numero'] ?? 'N/A') . "\n";
            echo "   - Programa: " . ($reg['programa_nombre'] ?? 'N/A') . "\n";
            echo "   - Instructor: " . ($reg['instructor_nombre'] ?? 'N/A') . "\n";
            echo "\n";
        }
    }
    
    echo "5. Cargando modelos...\n";
    require_once __DIR__ . '/../model/AsignacionModel.php';
    require_once __DIR__ . '/../model/FichaModel.php';
    require_once __DIR__ . '/../model/InstructorModel.php';
    require_once __DIR__ . '/../model/AmbienteModel.php';
    require_once __DIR__ . '/../model/CompetenciaModel.php';
    echo "   ✓ Modelos cargados\n\n";
    
    echo "6. Instanciando modelos...\n";
    $model = new AsignacionModel();
    $fichaModel = new FichaModel();
    $instructorModel = new InstructorModel();
    $ambienteModel = new AmbienteModel();
    $competenciaModel = new CompetenciaModel();
    echo "   ✓ Modelos instanciados\n\n";
    
    echo "7. Obteniendo datos con modelos...\n";
    $registros = $model->getAll();
    $fichas = $fichaModel->getAll();
    $instructores = $instructorModel->getAll();
    $ambientes = $ambienteModel->getAll();
    $competencias = $competenciaModel->getAll();
    echo "   ✓ Asignaciones: " . count($registros) . "\n";
    echo "   ✓ Fichas: " . count($fichas) . "\n";
    echo "   ✓ Instructores: " . count($instructores) . "\n";
    echo "   ✓ Ambientes: " . count($ambientes) . "\n";
    echo "   ✓ Competencias: " . count($competencias) . "\n\n";
    
    echo "8. Verificando helpers...\n";
    require_once __DIR__ . '/../helpers/functions.php';
    echo "   ✓ Helpers cargados\n\n";
    
    echo "9. Simulando carga de la vista...\n";
    $pageTitle = "Gestión de Asignaciones";
    echo "   ✓ pageTitle definido: $pageTitle\n\n";
    
    echo "=== DIAGNÓSTICO COMPLETADO ===\n";
    echo "✓ Todo parece estar funcionando correctamente\n\n";
    echo "Si ves este mensaje, el problema puede estar en:\n";
    echo "1. El archivo views/asignacion/index.php tiene un error de sintaxis\n";
    echo "2. Hay un problema con el header o sidebar\n";
    echo "3. Hay un problema con la sesión de autenticación\n\n";
    echo "Próximo paso: Revisar el archivo index.php línea por línea\n";
    
} catch (PDOException $e) {
    echo "\n✗ ERROR DE BASE DE DATOS:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
} catch (Exception $e) {
    echo "\n✗ ERROR:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
