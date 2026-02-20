<?php
/**
 * Diagnóstico completo de INSTRU_COMPETENCIA
 * Identifica problemas con claves foráneas y datos faltantes
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.success { color: #39A900; }
.error { color: #dc2626; }
.warning { color: #f59e0b; }
h1 { color: #1f2937; }
h2 { color: #374151; border-bottom: 2px solid #39A900; padding-bottom: 8px; }
pre { background: #f9fafb; padding: 12px; border-radius: 4px; overflow-x: auto; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; border-bottom: 1px solid #e5e7eb; }
th { background: #f9fafb; font-weight: 600; }
</style></head><body>";

echo "<h1>🔍 Diagnóstico Completo: INSTRU_COMPETENCIA</h1>";

try {
    require_once __DIR__ . '/../conexion.php';
    $db = Database::getInstance()->getConnection();
    
    // 1. Verificar estructura de tablas
    echo "<div class='section'>";
    echo "<h2>1. Estructura de Tablas</h2>";
    
    $tables = ['INSTRU_COMPETENCIA', 'COMPETxPROGRAMA', 'INSTRUCTOR', 'PROGRAMA', 'COMPETENCIA'];
    foreach ($tables as $table) {
        $stmt = $db->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Tabla: $table</h3>";
        echo "<table><tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
        foreach ($columns as $col) {
            echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td></tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    
    // 2. Contar registros
    echo "<div class='section'>";
    echo "<h2>2. Conteo de Registros</h2>";
    echo "<table><tr><th>Tabla</th><th>Cantidad</th></tr>";
    
    foreach ($tables as $table) {
        $stmt = $db->query("SELECT COUNT(*) as total FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $result['total'];
        $class = $count > 0 ? 'success' : 'warning';
        echo "<tr><td>$table</td><td class='$class'>$count registros</td></tr>";
    }
    echo "</table></div>";
    
    // 3. Verificar datos en COMPETxPROGRAMA
    echo "<div class='section'>";
    echo "<h2>3. Datos en COMPETxPROGRAMA (Tabla Intermedia)</h2>";
    $stmt = $db->query("
        SELECT cp.*, 
               p.prog_denominacion, 
               c.comp_nombre_corto
        FROM COMPETxPROGRAMA cp
        LEFT JOIN PROGRAMA p ON cp.PROGRAMA_prog_id = p.prog_codigo
        LEFT JOIN COMPETENCIA c ON cp.COMPETENCIA_comp_id = c.comp_id
        LIMIT 10
    ");
    $competxprograma = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($competxprograma)) {
        echo "<p class='warning'>⚠️ NO HAY DATOS en COMPETxPROGRAMA</p>";
        echo "<p>Esta tabla es REQUERIDA para crear asignaciones de competencias a instructores.</p>";
        echo "<p><strong>Solución:</strong> Primero debes asociar competencias con programas en la sección 'Competencias por Programa'</p>";
    } else {
        echo "<p class='success'>✓ Encontrados " . count($competxprograma) . " registros</p>";
        echo "<table><tr><th>Programa ID</th><th>Programa</th><th>Competencia ID</th><th>Competencia</th></tr>";
        foreach ($competxprograma as $cp) {
            echo "<tr>";
            echo "<td>{$cp['PROGRAMA_prog_id']}</td>";
            echo "<td>{$cp['prog_denominacion']}</td>";
            echo "<td>{$cp['COMPETENCIA_comp_id']}</td>";
            echo "<td>{$cp['comp_nombre_corto']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    
    // 4. Verificar INSTRU_COMPETENCIA existentes
    echo "<div class='section'>";
    echo "<h2>4. Registros en INSTRU_COMPETENCIA</h2>";
    $stmt = $db->query("
        SELECT ic.*,
               CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
               ic.COMPETxPROGRAMA_PROGRAMA_prog_id,
               ic.COMPETxPROGRAMA_COMPETENCIA_comp_id
        FROM INSTRU_COMPETENCIA ic
        LEFT JOIN INSTRUCTOR i ON ic.INSTRUCTOR_inst_id = i.inst_id
        LIMIT 10
    ");
    $instru_comp = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($instru_comp)) {
        echo "<p class='warning'>⚠️ NO HAY DATOS en INSTRU_COMPETENCIA</p>";
    } else {
        echo "<p class='success'>✓ Encontrados " . count($instru_comp) . " registros</p>";
        echo "<table><tr><th>ID</th><th>Instructor</th><th>Programa ID</th><th>Competencia ID</th><th>Vigencia</th></tr>";
        foreach ($instru_comp as $ic) {
            echo "<tr>";
            echo "<td>{$ic['inscomp_id']}</td>";
            echo "<td>{$ic['instructor_nombre']}</td>";
            echo "<td>{$ic['COMPETxPROGRAMA_PROGRAMA_prog_id']}</td>";
            echo "<td>{$ic['COMPETxPROGRAMA_COMPETENCIA_comp_id']}</td>";
            echo "<td>{$ic['inscomp_vigencia']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    
    // 5. Verificar integridad referencial
    echo "<div class='section'>";
    echo "<h2>5. Verificación de Integridad Referencial</h2>";
    
    if (!empty($instru_comp)) {
        echo "<p>Verificando que cada registro en INSTRU_COMPETENCIA tenga su correspondiente en COMPETxPROGRAMA...</p>";
        
        $problemas = 0;
        foreach ($instru_comp as $ic) {
            $prog_id = $ic['COMPETxPROGRAMA_PROGRAMA_prog_id'];
            $comp_id = $ic['COMPETxPROGRAMA_COMPETENCIA_comp_id'];
            
            $stmt = $db->prepare("
                SELECT COUNT(*) as existe 
                FROM COMPETxPROGRAMA 
                WHERE PROGRAMA_prog_id = ? AND COMPETENCIA_comp_id = ?
            ");
            $stmt->execute([$prog_id, $comp_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['existe'] == 0) {
                echo "<p class='error'>✗ Registro {$ic['inscomp_id']}: Programa $prog_id + Competencia $comp_id NO EXISTE en COMPETxPROGRAMA</p>";
                $problemas++;
            }
        }
        
        if ($problemas == 0) {
            echo "<p class='success'>✓ Todos los registros tienen integridad referencial correcta</p>";
        } else {
            echo "<p class='error'>⚠️ Se encontraron $problemas problemas de integridad</p>";
        }
    }
    echo "</div>";
    
    // 6. Probar consulta del modelo
    echo "<div class='section'>";
    echo "<h2>6. Prueba de Consulta del Modelo</h2>";
    
    try {
        require_once __DIR__ . '/../model/InstruCompetenciaModel.php';
        $model = new InstruCompetenciaModel();
        $registros = $model->getAll();
        echo "<p class='success'>✓ Modelo ejecutado correctamente: " . count($registros) . " registros</p>";
        
        if (!empty($registros)) {
            echo "<table><tr><th>ID</th><th>Instructor</th><th>Programa</th><th>Competencia</th><th>Vigencia</th></tr>";
            foreach (array_slice($registros, 0, 5) as $reg) {
                echo "<tr>";
                echo "<td>{$reg['inscomp_id']}</td>";
                echo "<td>" . ($reg['instructor_nombre'] ?? 'N/A') . "</td>";
                echo "<td>" . ($reg['prog_denominacion'] ?? 'N/A') . "</td>";
                echo "<td>" . ($reg['comp_nombre_corto'] ?? 'N/A') . "</td>";
                echo "<td>{$reg['inscomp_vigencia']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error al ejecutar modelo: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    
    // 7. Recomendaciones
    echo "<div class='section'>";
    echo "<h2>7. Recomendaciones</h2>";
    
    if (empty($competxprograma)) {
        echo "<div style='background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; margin: 10px 0;'>";
        echo "<strong>⚠️ PROBLEMA PRINCIPAL:</strong><br>";
        echo "La tabla COMPETxPROGRAMA está vacía. Esta tabla es necesaria para asociar competencias con programas.<br><br>";
        echo "<strong>Pasos para solucionar:</strong><br>";
        echo "1. Ve a la sección 'Competencias por Programa' en el dashboard<br>";
        echo "2. Crea asociaciones entre programas y competencias<br>";
        echo "3. Luego podrás asignar esas competencias a instructores<br>";
        echo "</div>";
    } else {
        echo "<div style='background: #d1fae5; padding: 15px; border-left: 4px solid #39A900; margin: 10px 0;'>";
        echo "<strong>✓ Sistema configurado correctamente</strong><br>";
        echo "Puedes crear asignaciones de competencias a instructores sin problemas.";
        echo "</div>";
    }
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='section'>";
    echo "<h2 class='error'>Error de Base de Datos</h2>";
    echo "<pre class='error'>";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "</pre>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='section'>";
    echo "<h2 class='error'>Error General</h2>";
    echo "<pre class='error'>";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "</pre>";
    echo "</div>";
}

echo "</body></html>";
?>
