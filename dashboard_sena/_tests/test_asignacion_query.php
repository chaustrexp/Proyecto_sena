<?php
/**
 * Test de la consulta de asignaciones
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexion.php';

echo "<h1>Test de Consulta de Asignaciones</h1><pre>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar si fich_numero existe
    echo "1. Verificando campo fich_numero...\n";
    $stmt = $db->query("SHOW COLUMNS FROM FICHA LIKE 'fich_numero'");
    $exists = $stmt->fetch();
    
    if (!$exists) {
        echo "   ✗ El campo fich_numero NO EXISTE\n\n";
        echo "SOLUCIÓN:\n";
        echo "Ejecuta este script: http://localhost/Gestion-sena/dashboard_sena/_scripts/agregar_campo_fich_numero.php\n\n";
        
        echo "O ejecuta este SQL en phpMyAdmin:\n\n";
        echo "ALTER TABLE `FICHA` ADD COLUMN `fich_numero` INT NOT NULL DEFAULT 0 AFTER `fich_id`;\n";
        echo "ALTER TABLE `FICHA` ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);\n";
        echo "UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` = 0;\n\n";
        
        echo "Mientras tanto, probando consulta SIN fich_numero...\n\n";
        
        // Consulta sin fich_numero
        $sql = "
            SELECT a.*,
                   a.ASIG_ID as asig_id,
                   f.fich_id as ficha_numero,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                   amb.amb_nombre as ambiente_nombre,
                   c.comp_nombre_corto as competencia_nombre,
                   p.prog_denominacion as programa_nombre
            FROM ASIGNACION a
            LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
            LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
            LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
            LIMIT 5
        ";
        
        $stmt = $db->query($sql);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✓ Consulta alternativa ejecutada: " . count($registros) . " registros\n\n";
        
        if (!empty($registros)) {
            echo "Datos:\n";
            foreach ($registros as $reg) {
                echo "- Ficha ID: " . $reg['ficha_numero'] . " | Programa: " . ($reg['programa_nombre'] ?? 'N/A') . "\n";
            }
        }
        
    } else {
        echo "   ✓ El campo fich_numero EXISTE\n\n";
        
        // Consulta con fich_numero
        echo "2. Probando consulta con fich_numero...\n";
        $sql = "
            SELECT a.*,
                   a.ASIG_ID as asig_id,
                   f.fich_numero as ficha_numero,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                   amb.amb_nombre as ambiente_nombre,
                   c.comp_nombre_corto as competencia_nombre,
                   p.prog_denominacion as programa_nombre
            FROM ASIGNACION a
            LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
            LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
            LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
            LIMIT 5
        ";
        
        $stmt = $db->query($sql);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "   ✓ Consulta ejecutada: " . count($registros) . " registros\n\n";
        
        if (!empty($registros)) {
            echo "3. Datos de ejemplo:\n";
            foreach ($registros as $reg) {
                $numero = str_pad($reg['ficha_numero'], 8, '0', STR_PAD_LEFT);
                echo "   - Ficha: $numero | Programa: " . ($reg['programa_nombre'] ?? 'N/A') . "\n";
            }
        }
        
        echo "\n✓ TODO FUNCIONA CORRECTAMENTE\n";
    }
    
} catch (PDOException $e) {
    echo "\n✗ ERROR SQL:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
}

echo "</pre>";
?>
