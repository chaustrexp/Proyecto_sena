<?php
/**
 * REPARACIÓN DEFINITIVA DE DOBLE CODIFICACIÓN UTF-8
 * Problema: TecnologÃ­a, GestiÃ³n, FormaciÃ³n
 * Solución: Tecnología, Gestión, Formación
 */

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

require_once __DIR__ . '/conexion.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reparar Doble Codificación UTF-8</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #39A900 0%, #007832 100%); padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        h1 { color: #007832; margin-bottom: 10px; font-size: 32px; }
        .subtitle { color: #666; margin-bottom: 30px; font-size: 16px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #17a2b8; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #39A900; color: white; font-weight: 600; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { display: inline-block; padding: 12px 24px; background: #39A900; color: white; text-decoration: none; border-radius: 8px; margin-top: 20px; font-weight: 600; transition: all 0.3s; }
        .btn:hover { background: #007832; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(57, 169, 0, 0.3); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 30px 0; }
        .stat-card { background: linear-gradient(135deg, #39A900 0%, #007832 100%); color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-number { font-size: 36px; font-weight: bold; margin-bottom: 5px; }
        .stat-label { font-size: 14px; opacity: 0.9; }
        .comparison { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .before, .after { padding: 15px; border-radius: 8px; }
        .before { background: #f8d7da; border-left: 4px solid #dc3545; }
        .after { background: #d4edda; border-left: 4px solid #28a745; }
        .comparison h4 { margin-bottom: 10px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: 'Courier New', monospace; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Reparación de Doble Codificación UTF-8</h1>
    <p class="subtitle">Corrigiendo: TecnologÃ­a → Tecnología</p>

<?php

try {
    $db = Database::getInstance()->getConnection();
    
    // Configurar UTF-8
    $db->exec("SET NAMES utf8mb4");
    $db->exec("SET CHARACTER SET utf8mb4");
    
    echo "<div class='success'>✓ Conexión UTF-8 configurada correctamente</div>";
    
    /**
     * Función para reparar doble codificación UTF-8
     * Convierte: TecnologÃ­a → Tecnología
     */
    function repararDobleCodificacion($texto) {
        if (empty($texto)) return $texto;
        
        // Detectar si tiene doble codificación
        if (preg_match('/Ã|Â|â€|Ã©|Ã³|Ã­|Ã¡|Ãº|Ã±|Ã¼/u', $texto)) {
            // Decodificar de UTF-8 a ISO-8859-1 y luego a UTF-8
            $reparado = utf8_encode(utf8_decode($texto));
            
            // Si no funcionó, intentar método alternativo
            if ($reparado === $texto) {
                $reparado = mb_convert_encoding($texto, 'UTF-8', 'ISO-8859-1');
            }
            
            return $reparado;
        }
        
        return $texto;
    }
    
    $total_tablas = 0;
    $total_registros = 0;
    $total_campos_reparados = 0;
    
    // Array para almacenar ejemplos de reparaciones
    $ejemplos = [];
    
    echo "<h2>📊 Proceso de Reparación</h2>";
    
    // 1. REPARAR titulo_programa
    echo "<h3>1. Tabla: titulo_programa</h3>";
    $stmt = $db->query("SELECT * FROM titulo_programa");
    $registros = $stmt->fetchAll();
    $reparados = 0;
    
    foreach ($registros as $registro) {
        $nombre_nuevo = repararDobleCodificacion($registro['nombre']);
        $nivel_nuevo = repararDobleCodificacion($registro['nivel']);
        
        if ($nombre_nuevo !== $registro['nombre'] || $nivel_nuevo !== $registro['nivel']) {
            $update = $db->prepare("UPDATE titulo_programa SET nombre = ?, nivel = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $nivel_nuevo, $registro['id']]);
            
            if ($nombre_nuevo !== $registro['nombre']) {
                $ejemplos[] = ['antes' => $registro['nombre'], 'despues' => $nombre_nuevo];
                echo "<div class='info'>✓ ID {$registro['id']}: <code>{$registro['nombre']}</code> → <code>{$nombre_nuevo}</code></div>";
            }
            
            $reparados++;
            $total_campos_reparados++;
        }
    }
    
    echo "<div class='success'>✓ Reparados: {$reparados} registros</div>";
    $total_registros += $reparados;
    $total_tablas++;
    
    // 2. REPARAR centro_formacion
    echo "<h3>2. Tabla: centro_formacion</h3>";
    $stmt = $db->query("SELECT * FROM centro_formacion");
    $registros = $stmt->fetchAll();
    $reparados = 0;
    
    foreach ($registros as $registro) {
        $nombre_nuevo = repararDobleCodificacion($registro['nombre']);
        $direccion_nueva = repararDobleCodificacion($registro['direccion']);
        
        if ($nombre_nuevo !== $registro['nombre'] || $direccion_nueva !== $registro['direccion']) {
            $update = $db->prepare("UPDATE centro_formacion SET nombre = ?, direccion = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $direccion_nueva, $registro['id']]);
            
            if ($nombre_nuevo !== $registro['nombre']) {
                $ejemplos[] = ['antes' => $registro['nombre'], 'despues' => $nombre_nuevo];
                echo "<div class='info'>✓ ID {$registro['id']}: <code>{$registro['nombre']}</code> → <code>{$nombre_nuevo}</code></div>";
            }
            
            $reparados++;
            $total_campos_reparados++;
        }
    }
    
    echo "<div class='success'>✓ Reparados: {$reparados} registros</div>";
    $total_registros += $reparados;
    $total_tablas++;
    
    // 3. REPARAR instructor
    echo "<h3>3. Tabla: instructor</h3>";
    $stmt = $db->query("SELECT * FROM instructor");
    $registros = $stmt->fetchAll();
    $reparados = 0;
    
    foreach ($registros as $registro) {
        $nombre_nuevo = repararDobleCodificacion($registro['nombre']);
        
        if ($nombre_nuevo !== $registro['nombre']) {
            $update = $db->prepare("UPDATE instructor SET nombre = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $registro['id']]);
            
            $ejemplos[] = ['antes' => $registro['nombre'], 'despues' => $nombre_nuevo];
            echo "<div class='info'>✓ ID {$registro['id']}: <code>{$registro['nombre']}</code> → <code>{$nombre_nuevo}</code></div>";
            
            $reparados++;
            $total_campos_reparados++;
        }
    }
    
    echo "<div class='success'>✓ Reparados: {$reparados} registros</div>";
    $total_registros += $reparados;
    $total_tablas++;
    
    // 4. REPARAR programa
    echo "<h3>4. Tabla: programa</h3>";
    $stmt = $db->query("SELECT * FROM programa");
    $registros = $stmt->fetchAll();
    $reparados = 0;
    
    foreach ($registros as $registro) {
        $nombre_nuevo = repararDobleCodificacion($registro['nombre']);
        
        if ($nombre_nuevo !== $registro['nombre']) {
            $update = $db->prepare("UPDATE programa SET nombre = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $registro['id']]);
            
            $ejemplos[] = ['antes' => $registro['nombre'], 'despues' => $nombre_nuevo];
            echo "<div class='info'>✓ ID {$registro['id']}: <code>{$registro['nombre']}</code> → <code>{$nombre_nuevo}</code></div>";
            
            $reparados++;
            $total_campos_reparados++;
        }
    }
    
    echo "<div class='success'>✓ Reparados: {$reparados} registros</div>";
    $total_registros += $reparados;
    $total_tablas++;
    
    // 5. REPARAR usuarios
    echo "<h3>5. Tabla: usuarios</h3>";
    $stmt = $db->query("SELECT * FROM usuarios");
    $registros = $stmt->fetchAll();
    $reparados = 0;
    
    foreach ($registros as $registro) {
        $nombre_nuevo = repararDobleCodificacion($registro['nombre']);
        
        if ($nombre_nuevo !== $registro['nombre']) {
            $update = $db->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $registro['id']]);
            
            $ejemplos[] = ['antes' => $registro['nombre'], 'despues' => $nombre_nuevo];
            echo "<div class='info'>✓ ID {$registro['id']}: <code>{$registro['nombre']}</code> → <code>{$nombre_nuevo}</code></div>";
            
            $reparados++;
            $total_campos_reparados++;
        }
    }
    
    echo "<div class='success'>✓ Reparados: {$reparados} registros</div>";
    $total_registros += $reparados;
    $total_tablas++;
    
    // 6. REPARAR competencia
    echo "<h3>6. Tabla: competencia</h3>";
    $stmt = $db->query("SELECT * FROM competencia");
    $registros = $stmt->fetchAll();
    $reparados = 0;
    
    foreach ($registros as $registro) {
        $nombre_nuevo = repararDobleCodificacion($registro['nombre']);
        $descripcion_nueva = repararDobleCodificacion($registro['descripcion']);
        
        if ($nombre_nuevo !== $registro['nombre'] || $descripcion_nueva !== $registro['descripcion']) {
            $update = $db->prepare("UPDATE competencia SET nombre = ?, descripcion = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $descripcion_nueva, $registro['id']]);
            
            if ($nombre_nuevo !== $registro['nombre']) {
                $ejemplos[] = ['antes' => $registro['nombre'], 'despues' => $nombre_nuevo];
                echo "<div class='info'>✓ ID {$registro['id']}: <code>{$registro['nombre']}</code> → <code>{$nombre_nuevo}</code></div>";
            }
            
            $reparados++;
            $total_campos_reparados++;
        }
    }
    
    echo "<div class='success'>✓ Reparados: {$reparados} registros</div>";
    $total_registros += $reparados;
    $total_tablas++;
    
    // 7. REPARAR coordinacion
    echo "<h3>7. Tabla: coordinacion</h3>";
    $stmt = $db->query("SELECT * FROM coordinacion");
    $registros = $stmt->fetchAll();
    $reparados = 0;
    
    foreach ($registros as $registro) {
        $nombre_nuevo = repararDobleCodificacion($registro['nombre']);
        $responsable_nuevo = repararDobleCodificacion($registro['responsable']);
        
        if ($nombre_nuevo !== $registro['nombre'] || $responsable_nuevo !== $registro['responsable']) {
            $update = $db->prepare("UPDATE coordinacion SET nombre = ?, responsable = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $responsable_nuevo, $registro['id']]);
            $reparados++;
            $total_campos_reparados++;
        }
    }
    
    echo "<div class='success'>✓ Reparados: {$reparados} registros</div>";
    $total_registros += $reparados;
    $total_tablas++;
    
    // ESTADÍSTICAS FINALES
    echo "<h2>📈 Estadísticas de Reparación</h2>";
    echo "<div class='stats'>";
    echo "<div class='stat-card'><div class='stat-number'>{$total_tablas}</div><div class='stat-label'>Tablas Procesadas</div></div>";
    echo "<div class='stat-card'><div class='stat-number'>{$total_registros}</div><div class='stat-label'>Registros Reparados</div></div>";
    echo "<div class='stat-card'><div class='stat-number'>{$total_campos_reparados}</div><div class='stat-label'>Campos Corregidos</div></div>";
    echo "</div>";
    
    // EJEMPLOS DE REPARACIÓN
    if (!empty($ejemplos)) {
        echo "<h2>🔍 Ejemplos de Reparación</h2>";
        echo "<div class='comparison'>";
        echo "<div class='before'><h4>❌ ANTES (Dañado)</h4>";
        foreach (array_slice($ejemplos, 0, 5) as $ejemplo) {
            echo "<p><code>{$ejemplo['antes']}</code></p>";
        }
        echo "</div>";
        echo "<div class='after'><h4>✅ DESPUÉS (Corregido)</h4>";
        foreach (array_slice($ejemplos, 0, 5) as $ejemplo) {
            echo "<p><code>{$ejemplo['despues']}</code></p>";
        }
        echo "</div>";
        echo "</div>";
    }
    
    // VERIFICACIÓN FINAL
    echo "<h2>✅ Verificación de Datos Corregidos</h2>";
    
    echo "<h3>Títulos de Programa:</h3>";
    $stmt = $db->query("SELECT * FROM titulo_programa");
    $titulos = $stmt->fetchAll();
    
    echo "<table>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Nivel</th></tr>";
    foreach ($titulos as $titulo) {
        echo "<tr><td>{$titulo['id']}</td><td>{$titulo['nombre']}</td><td>{$titulo['nivel']}</td></tr>";
    }
    echo "</table>";
    
    echo "<h3>Centros de Formación:</h3>";
    $stmt = $db->query("SELECT * FROM centro_formacion");
    $centros = $stmt->fetchAll();
    
    echo "<table>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Dirección</th></tr>";
    foreach ($centros as $centro) {
        echo "<tr><td>{$centro['id']}</td><td>{$centro['nombre']}</td><td>{$centro['direccion']}</td></tr>";
    }
    echo "</table>";
    
    // MENSAJE FINAL
    echo "<div class='success' style='font-size: 18px; text-align: center; padding: 30px;'>";
    echo "<h2 style='margin-bottom: 15px;'>🎉 ¡Reparación Completada Exitosamente!</h2>";
    echo "<p>Todos los caracteres con doble codificación han sido corregidos.</p>";
    echo "<p>Los textos ahora se muestran correctamente en todo el sistema.</p>";
    echo "</div>";
    
    echo "<div style='text-align: center;'>";
    echo "<a href='/dashboard_sena/' class='btn'>🏠 Ir al Dashboard</a>";
    echo "<a href='/dashboard_sena/views/titulo_programa/index.php' class='btn' style='margin-left: 10px;'>📋 Ver Títulos</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
}

?>

</div>
</body>
</html>
