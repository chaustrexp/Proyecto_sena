<?php
/**
 * Script para agregar el campo fich_numero a la tabla FICHA
 * Ejecuta este script UNA SOLA VEZ desde el navegador
 */

require_once __DIR__ . '/../conexion.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Agregar Campo fich_numero</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
        h1 { color: #39A900; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #39A900; background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>🔧 Agregar Campo fich_numero a la Tabla FICHA</h1>";

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<div class='step'><strong>Paso 1:</strong> Verificando si el campo ya existe...</div>";
    
    // Verificar si el campo ya existe
    $stmt = $db->query("SHOW COLUMNS FROM FICHA LIKE 'fich_numero'");
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "<div class='warning'>⚠️ El campo 'fich_numero' ya existe en la tabla FICHA. No es necesario agregarlo nuevamente.</div>";
        
        // Mostrar información del campo
        $stmt = $db->query("DESCRIBE FICHA");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div class='info'><strong>Estructura actual de la tabla FICHA:</strong><br><pre>";
        foreach ($columns as $col) {
            if ($col['Field'] === 'fich_numero') {
                echo "✓ {$col['Field']} - {$col['Type']} - {$col['Key']}\n";
            }
        }
        echo "</pre></div>";
        
    } else {
        echo "<div class='info'>✓ El campo no existe. Procediendo a agregarlo...</div>";
        
        echo "<div class='step'><strong>Paso 2:</strong> Agregando el campo fich_numero...</div>";
        
        // Agregar el campo
        $db->exec("ALTER TABLE `FICHA` ADD COLUMN `fich_numero` INT NOT NULL DEFAULT 0 AFTER `fich_id`");
        
        echo "<div class='success'>✓ Campo 'fich_numero' agregado exitosamente</div>";
        
        echo "<div class='step'><strong>Paso 3:</strong> Agregando índice UNIQUE...</div>";
        
        // Agregar índice UNIQUE
        $db->exec("ALTER TABLE `FICHA` ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC)");
        
        echo "<div class='success'>✓ Índice UNIQUE agregado exitosamente</div>";
        
        echo "<div class='step'><strong>Paso 4:</strong> Actualizando registros existentes...</div>";
        
        // Actualizar registros existentes
        $stmt = $db->query("SELECT COUNT(*) as total FROM FICHA WHERE fich_numero = 0");
        $result = $stmt->fetch();
        $total = $result['total'];
        
        if ($total > 0) {
            $db->exec("UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` = 0");
            echo "<div class='success'>✓ {$total} registros actualizados con valores temporales</div>";
            echo "<div class='warning'>⚠️ IMPORTANTE: Debes actualizar manualmente los números de ficha con los valores reales.</div>";
        } else {
            echo "<div class='info'>✓ No hay registros que actualizar</div>";
        }
        
        echo "<div class='step'><strong>Paso 5:</strong> Verificando la estructura final...</div>";
        
        // Verificar estructura final
        $stmt = $db->query("DESCRIBE FICHA");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div class='success'><strong>Estructura actualizada de la tabla FICHA:</strong><br><pre>";
        foreach ($columns as $col) {
            $marker = ($col['Field'] === 'fich_numero') ? '✓ ' : '  ';
            echo "{$marker}{$col['Field']} - {$col['Type']} - Null: {$col['Null']} - Key: {$col['Key']}\n";
        }
        echo "</pre></div>";
    }
    
    // Mostrar datos actuales
    echo "<div class='step'><strong>Datos actuales en la tabla FICHA:</strong></div>";
    $stmt = $db->query("SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM FICHA LIMIT 10");
    $fichas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($fichas)) {
        echo "<div class='info'>No hay fichas en la base de datos</div>";
    } else {
        echo "<div class='info'><pre>";
        echo "ID  | Número Ficha | Programa\n";
        echo "----+--------------+---------\n";
        foreach ($fichas as $ficha) {
            $numero = str_pad($ficha['fich_numero'], 8, '0', STR_PAD_LEFT);
            echo sprintf("%-3s | %-12s | %s\n", $ficha['fich_id'], $numero, $ficha['PROGRAMA_prog_id']);
        }
        echo "</pre></div>";
        
        if ($fichas[0]['fich_numero'] < 1000000) {
            echo "<div class='warning'>
                <strong>⚠️ ACCIÓN REQUERIDA:</strong><br>
                Los números de ficha actuales son valores temporales. Actualízalos con los números reales:<br><br>
                <pre>UPDATE FICHA SET fich_numero = 3115418 WHERE fich_id = {$fichas[0]['fich_id']};
UPDATE FICHA SET fich_numero = 3115419 WHERE fich_id = " . ($fichas[0]['fich_id'] + 1) . ";</pre>
            </div>";
        }
    }
    
    echo "<div class='success'>
        <h2>🎉 ¡Proceso Completado!</h2>
        <p><strong>Próximos pasos:</strong></p>
        <ol>
            <li>Actualiza los números de ficha con valores reales (si es necesario)</li>
            <li>Recarga la página de asignaciones: <a href='../views/asignacion/index.php'>Ver Asignaciones</a></li>
            <li>Crea una nueva ficha: <a href='../views/ficha/crear.php'>Crear Ficha</a></li>
        </ol>
    </div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>
        <strong>❌ Error de Base de Datos:</strong><br>
        {$e->getMessage()}<br><br>
        <strong>Código de Error:</strong> {$e->getCode()}
    </div>";
    
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "<div class='info'>El campo ya existe. Intenta recargar la página de asignaciones.</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>
        <strong>❌ Error:</strong><br>
        {$e->getMessage()}
    </div>";
}

echo "</body></html>";
?>
