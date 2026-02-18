<?php
/**
 * Script para reparar caracteres dañados por doble codificación UTF-8
 * Ejecutar una sola vez para corregir datos existentes
 */

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

require_once __DIR__ . '/conexion.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Reparar Caracteres</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #007832; }
        .success { color: #28a745; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #39A900; color: white; }
        .btn { display: inline-block; padding: 10px 20px; background: #39A900; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
<div class='container'>
<h1>🔧 Reparación de Caracteres UTF-8</h1>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Configurar UTF-8
    $db->exec("SET NAMES utf8mb4");
    $db->exec("SET CHARACTER SET utf8mb4");
    
    echo "<div class='success'>✓ Conexión UTF-8 configurada</div>";
    
    // Función para reparar texto con doble codificación
    function repararTexto($texto) {
        if (empty($texto)) return $texto;
        
        // Detectar y reparar doble codificación
        $reparado = mb_convert_encoding($texto, 'UTF-8', 'UTF-8');
        
        // Si no funcionó, intentar desde ISO-8859-1
        if ($reparado === $texto && preg_match('/[│├®│¡]/u', $texto)) {
            $reparado = mb_convert_encoding($texto, 'UTF-8', 'ISO-8859-1');
        }
        
        // Reemplazos específicos conocidos
        $reemplazos = [
            '│' => 'í',
            '├│' => 'ó',
            '├®' => 'é',
            '├¡' => 'á',
            '├║' => 'ú',
            '├▒' => 'ñ',
            '├ü' => 'ü',
            'Tecn├│logo' => 'Tecnólogo',
            'T├®cnico' => 'Técnico',
            'Especializaci├│n' => 'Especialización',
            'Tecnolog├¡a' => 'Tecnología',
            'Gesti├│n' => 'Gestión',
            'Administraci├│n' => 'Administración',
            'Coordinaci├│n' => 'Coordinación',
            'Formaci├│n' => 'Formación',
            'P├®rez' => 'Pérez',
            'Garc├¡a' => 'García',
            'Mart├¡nez' => 'Martínez',
            'L├│pez' => 'López',
            'Rodr├¡guez' => 'Rodríguez'
        ];
        
        foreach ($reemplazos as $malo => $bueno) {
            $reparado = str_replace($malo, $bueno, $reparado);
        }
        
        return $reparado;
    }
    
    $tablas_reparadas = 0;
    $registros_reparados = 0;
    
    // 1. Reparar titulo_programa
    echo "<h2>Reparando: titulo_programa</h2>";
    $stmt = $db->query("SELECT * FROM titulo_programa");
    $titulos = $stmt->fetchAll();
    
    foreach ($titulos as $titulo) {
        $nombre_nuevo = repararTexto($titulo['nombre']);
        $nivel_nuevo = repararTexto($titulo['nivel']);
        
        if ($nombre_nuevo !== $titulo['nombre'] || $nivel_nuevo !== $titulo['nivel']) {
            $update = $db->prepare("UPDATE titulo_programa SET nombre = ?, nivel = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $nivel_nuevo, $titulo['id']]);
            echo "<div class='success'>✓ Reparado ID {$titulo['id']}: '{$titulo['nombre']}' → '{$nombre_nuevo}'</div>";
            $registros_reparados++;
        }
    }
    $tablas_reparadas++;
    
    // 2. Reparar centro_formacion
    echo "<h2>Reparando: centro_formacion</h2>";
    $stmt = $db->query("SELECT * FROM centro_formacion");
    $centros = $stmt->fetchAll();
    
    foreach ($centros as $centro) {
        $nombre_nuevo = repararTexto($centro['nombre']);
        $direccion_nueva = repararTexto($centro['direccion']);
        
        if ($nombre_nuevo !== $centro['nombre'] || $direccion_nueva !== $centro['direccion']) {
            $update = $db->prepare("UPDATE centro_formacion SET nombre = ?, direccion = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $direccion_nueva, $centro['id']]);
            echo "<div class='success'>✓ Reparado ID {$centro['id']}: '{$centro['nombre']}' → '{$nombre_nuevo}'</div>";
            $registros_reparados++;
        }
    }
    $tablas_reparadas++;
    
    // 3. Reparar instructor
    echo "<h2>Reparando: instructor</h2>";
    $stmt = $db->query("SELECT * FROM instructor");
    $instructores = $stmt->fetchAll();
    
    foreach ($instructores as $instructor) {
        $nombre_nuevo = repararTexto($instructor['nombre']);
        
        if ($nombre_nuevo !== $instructor['nombre']) {
            $update = $db->prepare("UPDATE instructor SET nombre = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $instructor['id']]);
            echo "<div class='success'>✓ Reparado ID {$instructor['id']}: '{$instructor['nombre']}' → '{$nombre_nuevo}'</div>";
            $registros_reparados++;
        }
    }
    $tablas_reparadas++;
    
    // 4. Reparar programa
    echo "<h2>Reparando: programa</h2>";
    $stmt = $db->query("SELECT * FROM programa");
    $programas = $stmt->fetchAll();
    
    foreach ($programas as $programa) {
        $nombre_nuevo = repararTexto($programa['nombre']);
        
        if ($nombre_nuevo !== $programa['nombre']) {
            $update = $db->prepare("UPDATE programa SET nombre = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $programa['id']]);
            echo "<div class='success'>✓ Reparado ID {$programa['id']}: '{$programa['nombre']}' → '{$nombre_nuevo}'</div>";
            $registros_reparados++;
        }
    }
    $tablas_reparadas++;
    
    // 5. Reparar usuarios
    echo "<h2>Reparando: usuarios</h2>";
    $stmt = $db->query("SELECT * FROM usuarios");
    $usuarios = $stmt->fetchAll();
    
    foreach ($usuarios as $usuario) {
        $nombre_nuevo = repararTexto($usuario['nombre']);
        
        if ($nombre_nuevo !== $usuario['nombre']) {
            $update = $db->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $usuario['id']]);
            echo "<div class='success'>✓ Reparado ID {$usuario['id']}: '{$usuario['nombre']}' → '{$nombre_nuevo}'</div>";
            $registros_reparados++;
        }
    }
    $tablas_reparadas++;
    
    // 6. Reparar competencia
    echo "<h2>Reparando: competencia</h2>";
    $stmt = $db->query("SELECT * FROM competencia");
    $competencias = $stmt->fetchAll();
    
    foreach ($competencias as $competencia) {
        $nombre_nuevo = repararTexto($competencia['nombre']);
        $descripcion_nueva = repararTexto($competencia['descripcion']);
        
        if ($nombre_nuevo !== $competencia['nombre'] || $descripcion_nueva !== $competencia['descripcion']) {
            $update = $db->prepare("UPDATE competencia SET nombre = ?, descripcion = ? WHERE id = ?");
            $update->execute([$nombre_nuevo, $descripcion_nueva, $competencia['id']]);
            echo "<div class='success'>✓ Reparado ID {$competencia['id']}</div>";
            $registros_reparados++;
        }
    }
    $tablas_reparadas++;
    
    // Verificación final
    echo "<h2>✅ Verificación de Datos Reparados</h2>";
    
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
    echo "<tr><th>ID</th><th>Nombre</th></tr>";
    foreach ($centros as $centro) {
        echo "<tr><td>{$centro['id']}</td><td>{$centro['nombre']}</td></tr>";
    }
    echo "</table>";
    
    echo "<div class='success'>";
    echo "<h2>🎉 ¡Reparación Completada!</h2>";
    echo "<p><strong>Tablas reparadas:</strong> {$tablas_reparadas}</p>";
    echo "<p><strong>Registros corregidos:</strong> {$registros_reparados}</p>";
    echo "</div>";
    
    echo "<a href='/dashboard_sena/' class='btn'>Ir al Dashboard</a>";
    
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "</div></body></html>";
?>
