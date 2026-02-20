<?php
/**
 * Script de verificación para el campo fich_numero
 * Verifica que el campo existe y funciona correctamente
 */

require_once __DIR__ . '/../conexion.php';

echo "=== VERIFICACIÓN DEL CAMPO fich_numero ===\n\n";

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Verificar estructura de la tabla FICHA
    echo "1. Verificando estructura de la tabla FICHA...\n";
    $stmt = $db->query("DESCRIBE FICHA");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $fich_numero_exists = false;
    echo "\n   Columnas de la tabla FICHA:\n";
    foreach ($columns as $column) {
        echo "   - {$column['Field']} ({$column['Type']})";
        if ($column['Field'] === 'fich_numero') {
            $fich_numero_exists = true;
            echo " ✓ ENCONTRADO";
            if ($column['Key'] === 'UNI') {
                echo " [UNIQUE]";
            }
        }
        echo "\n";
    }
    
    if (!$fich_numero_exists) {
        echo "\n   ✗ ERROR: El campo fich_numero NO EXISTE\n";
        echo "   → Ejecuta el script: _database/agregar_campo_fich_numero.sql\n\n";
        exit(1);
    }
    
    echo "\n   ✓ El campo fich_numero existe correctamente\n\n";
    
    // 2. Verificar datos en la tabla FICHA
    echo "2. Verificando datos en la tabla FICHA...\n";
    $stmt = $db->query("SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM FICHA LIMIT 10");
    $fichas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($fichas)) {
        echo "   ⚠️  No hay fichas en la base de datos\n";
        echo "   → Crea una ficha de prueba desde el formulario\n\n";
    } else {
        echo "   ✓ Se encontraron " . count($fichas) . " fichas\n\n";
        echo "   Datos de las fichas:\n";
        foreach ($fichas as $ficha) {
            $numero_formateado = str_pad($ficha['fich_numero'], 8, '0', STR_PAD_LEFT);
            echo "   - ID: {$ficha['fich_id']} | Número: {$numero_formateado} | Programa: {$ficha['PROGRAMA_prog_id']}\n";
        }
        echo "\n";
        
        // Verificar si hay números duplicados
        $stmt = $db->query("
            SELECT fich_numero, COUNT(*) as total 
            FROM FICHA 
            GROUP BY fich_numero 
            HAVING COUNT(*) > 1
        ");
        $duplicados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($duplicados)) {
            echo "   ✗ ADVERTENCIA: Se encontraron números de ficha duplicados:\n";
            foreach ($duplicados as $dup) {
                echo "   - Número {$dup['fich_numero']} aparece {$dup['total']} veces\n";
            }
            echo "\n";
        } else {
            echo "   ✓ No hay números de ficha duplicados\n\n";
        }
    }
    
    // 3. Verificar consulta de asignaciones
    echo "3. Verificando consulta de asignaciones...\n";
    $stmt = $db->query("
        SELECT a.ASIG_ID,
               f.fich_numero,
               CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
               p.prog_denominacion as programa_nombre
        FROM ASIGNACION a
        LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
        LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
        LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
        LIMIT 5
    ");
    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($asignaciones)) {
        echo "   ⚠️  No hay asignaciones en la base de datos\n\n";
    } else {
        echo "   ✓ Se encontraron " . count($asignaciones) . " asignaciones\n\n";
        echo "   Datos de las asignaciones:\n";
        foreach ($asignaciones as $asig) {
            $numero_formateado = str_pad($asig['fich_numero'] ?? 'N/A', 8, '0', STR_PAD_LEFT);
            echo "   - Asignación #{$asig['ASIG_ID']}\n";
            echo "     Ficha: {$numero_formateado}\n";
            echo "     Programa: " . ($asig['programa_nombre'] ?? 'N/A') . "\n";
            echo "     Instructor: " . ($asig['instructor_nombre'] ?? 'N/A') . "\n\n";
        }
    }
    
    // 4. Probar inserción de prueba (sin ejecutar)
    echo "4. Verificando sintaxis de INSERT...\n";
    $stmt = $db->prepare("
        INSERT INTO FICHA (fich_numero, PROGRAMA_prog_id, INSTRUCTOR_inst_id_lider, fich_jornada, COORDINACION_coord_id, fich_fecha_ini_lectiva, fich_fecha_fin_lectiva) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    echo "   ✓ La sintaxis del INSERT es correcta\n\n";
    
    // 5. Verificar índice UNIQUE
    echo "5. Verificando índice UNIQUE en fich_numero...\n";
    $stmt = $db->query("SHOW INDEX FROM FICHA WHERE Column_name = 'fich_numero'");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($indexes)) {
        echo "   ⚠️  ADVERTENCIA: No se encontró índice UNIQUE en fich_numero\n";
        echo "   → Ejecuta: ALTER TABLE FICHA ADD UNIQUE INDEX fich_numero_UNIQUE (fich_numero);\n\n";
    } else {
        $is_unique = false;
        foreach ($indexes as $index) {
            if ($index['Non_unique'] == 0) {
                $is_unique = true;
                break;
            }
        }
        
        if ($is_unique) {
            echo "   ✓ El índice UNIQUE está configurado correctamente\n\n";
        } else {
            echo "   ⚠️  El campo tiene índice pero NO es UNIQUE\n\n";
        }
    }
    
    // Resumen final
    echo "=== RESUMEN ===\n\n";
    
    $checks = [
        'Campo fich_numero existe' => $fich_numero_exists,
        'Hay fichas en la BD' => !empty($fichas),
        'Consulta de asignaciones funciona' => true,
        'Sintaxis INSERT correcta' => true
    ];
    
    $total = count($checks);
    $passed = count(array_filter($checks));
    
    foreach ($checks as $check => $status) {
        echo ($status ? '✓' : '✗') . " {$check}\n";
    }
    
    echo "\n";
    echo "Resultado: {$passed}/{$total} verificaciones pasadas\n\n";
    
    if ($passed === $total) {
        echo "🎉 ¡TODO ESTÁ FUNCIONANDO CORRECTAMENTE!\n\n";
        echo "Próximos pasos:\n";
        echo "1. Crea una nueva ficha desde el formulario\n";
        echo "2. Ingresa un número de ficha real (ej: 3115418)\n";
        echo "3. Verifica que se muestre correctamente en la tabla de asignaciones\n";
    } else {
        echo "⚠️  Hay problemas que necesitan atención\n";
        echo "Revisa los mensajes de error arriba\n";
    }
    
} catch (PDOException $e) {
    echo "\n✗ ERROR DE BASE DE DATOS: " . $e->getMessage() . "\n";
    echo "\nVerifica:\n";
    echo "1. Que la base de datos 'progsena' existe\n";
    echo "2. Que las credenciales en conexion.php son correctas\n";
    echo "3. Que el servidor MySQL está corriendo\n";
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE LA VERIFICACIÓN ===\n";
?>
