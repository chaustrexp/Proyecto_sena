<?php
/**
 * Script para crear un coordinador de prueba
 * Acceder desde: http://localhost/Gestion-sena/dashboard_sena/_scripts/crear_coordinador_prueba.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexion.php';

echo "<h1>Crear Coordinador de Prueba</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
    .success { color: green; padding: 10px; background: #e8f5e9; border-left: 4px solid green; margin: 10px 0; }
    .error { color: red; padding: 10px; background: #ffebee; border-left: 4px solid red; margin: 10px 0; }
    .info { color: blue; padding: 10px; background: #e3f2fd; border-left: 4px solid blue; margin: 10px 0; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background: #39A900; color: white; }
    .credentials { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; }
    .credentials strong { color: #856404; }
</style>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar si ya existe un coordinador de prueba
    echo "<h2>1. Verificando coordinadores existentes...</h2>";
    $stmt = $db->query("SELECT * FROM COORDINACION");
    $coordinadores = $stmt->fetchAll();
    
    if (count($coordinadores) > 0) {
        echo "<div class='info'>Se encontraron " . count($coordinadores) . " coordinador(es) existente(s):</div>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Descripción</th><th>Centro ID</th></tr>";
        foreach ($coordinadores as $coord) {
            echo "<tr>";
            echo "<td>" . $coord['coord_id'] . "</td>";
            echo "<td>" . htmlspecialchars($coord['coord_nombre_coordinador']) . "</td>";
            echo "<td>" . htmlspecialchars($coord['coord_correo']) . "</td>";
            echo "<td>" . htmlspecialchars($coord['coord_descripcion']) . "</td>";
            echo "<td>" . $coord['CENTRO_FORMACION_cent_id'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>No se encontraron coordinadores existentes.</div>";
    }
    
    // Verificar si existe un centro de formación
    echo "<h2>2. Verificando centros de formación...</h2>";
    $stmt = $db->query("SELECT * FROM CENTRO_FORMACION LIMIT 1");
    $centro = $stmt->fetch();
    
    if (!$centro) {
        echo "<div class='error'>No hay centros de formación en la base de datos. Creando uno...</div>";
        
        $stmt = $db->prepare("INSERT INTO CENTRO_FORMACION (cent_nombre, cent_direccion, cent_telefono, cent_ciudad) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            'Centro de Formación SENA Cúcuta',
            'Av. Libertadores',
            '5751234',
            'Cúcuta'
        ]);
        
        $centro_id = $db->lastInsertId();
        echo "<div class='success'>Centro de formación creado con ID: $centro_id</div>";
    } else {
        $centro_id = $centro['cent_id'];
        echo "<div class='success'>Centro de formación encontrado: " . htmlspecialchars($centro['cent_nombre']) . " (ID: $centro_id)</div>";
    }
    
    // Crear coordinador de prueba
    echo "<h2>3. Creando coordinador de prueba...</h2>";
    
    $correo_prueba = 'coordinador@sena.edu.co';
    $password_prueba = 'Coord123';
    $password_hash = password_hash($password_prueba, PASSWORD_DEFAULT);
    
    // Verificar si ya existe este correo
    $stmt = $db->prepare("SELECT * FROM COORDINACION WHERE coord_correo = ?");
    $stmt->execute([$correo_prueba]);
    $existe = $stmt->fetch();
    
    if ($existe) {
        echo "<div class='info'>Ya existe un coordinador con el correo: $correo_prueba</div>";
        echo "<div class='info'>Actualizando contraseña...</div>";
        
        $stmt = $db->prepare("UPDATE COORDINACION SET coord_password = ? WHERE coord_correo = ?");
        $stmt->execute([$password_hash, $correo_prueba]);
        
        echo "<div class='success'>Contraseña actualizada correctamente</div>";
    } else {
        $stmt = $db->prepare("
            INSERT INTO COORDINACION (coord_descripcion, CENTRO_FORMACION_cent_id, coord_nombre_coordinador, coord_correo, coord_password) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            'Coordinación Académica',
            $centro_id,
            'Coordinador de Prueba',
            $correo_prueba,
            $password_hash
        ]);
        
        echo "<div class='success'>Coordinador creado exitosamente</div>";
    }
    
    // Mostrar credenciales
    echo "<div class='credentials'>";
    echo "<h3>✓ Credenciales de Coordinador</h3>";
    echo "<p><strong>Correo:</strong> $correo_prueba</p>";
    echo "<p><strong>Contraseña:</strong> $password_prueba</p>";
    echo "<p><strong>Rol:</strong> Coordinador</p>";
    echo "</div>";
    
    echo "<h2>4. Verificación final</h2>";
    $stmt = $db->prepare("SELECT * FROM COORDINACION WHERE coord_correo = ?");
    $stmt->execute([$correo_prueba]);
    $coord_final = $stmt->fetch();
    
    if ($coord_final) {
        echo "<div class='success'>Coordinador verificado en la base de datos:</div>";
        echo "<table>";
        echo "<tr><th>Campo</th><th>Valor</th></tr>";
        echo "<tr><td>ID</td><td>" . $coord_final['coord_id'] . "</td></tr>";
        echo "<tr><td>Nombre</td><td>" . htmlspecialchars($coord_final['coord_nombre_coordinador']) . "</td></tr>";
        echo "<tr><td>Correo</td><td>" . htmlspecialchars($coord_final['coord_correo']) . "</td></tr>";
        echo "<tr><td>Descripción</td><td>" . htmlspecialchars($coord_final['coord_descripcion']) . "</td></tr>";
        echo "<tr><td>Centro ID</td><td>" . $coord_final['CENTRO_FORMACION_cent_id'] . "</td></tr>";
        echo "<tr><td>Password Hash</td><td>" . substr($coord_final['coord_password'], 0, 30) . "...</td></tr>";
        echo "</table>";
        
        // Verificar que la contraseña funciona
        if (password_verify($password_prueba, $coord_final['coord_password'])) {
            echo "<div class='success'>✓ Verificación de contraseña: CORRECTA</div>";
        } else {
            echo "<div class='error'>✗ Verificación de contraseña: FALLIDA</div>";
        }
    }
    
    echo "<hr>";
    echo "<h2>Siguiente paso</h2>";
    echo "<p>Ahora puedes iniciar sesión con estas credenciales:</p>";
    echo "<p><a href='../auth/login.php' style='padding: 10px 20px; background: #39A900; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Ir al Login</a></p>";
    
} catch (Exception $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
