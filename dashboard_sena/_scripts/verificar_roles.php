<?php
/**
 * Script de Verificación del Sistema de Roles
 * Verifica que las tablas y usuarios estén configurados correctamente
 */

require_once 'conexion.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Verificación de Roles - Sistema SENA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #1f2937; 
            margin-bottom: 10px; 
            font-size: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .subtitle { 
            color: #6b7280; 
            margin-bottom: 30px; 
            font-size: 14px;
        }
        .section { 
            margin: 30px 0; 
            padding: 24px; 
            background: #f9fafb; 
            border-radius: 12px;
            border-left: 4px solid #39A900;
        }
        .section h2 { 
            color: #1f2937; 
            margin-bottom: 16px; 
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .success { 
            color: #10b981; 
            padding: 12px 16px; 
            background: #d1fae5; 
            border-radius: 8px; 
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .error { 
            color: #ef4444; 
            padding: 12px 16px; 
            background: #fee2e2; 
            border-radius: 8px; 
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info { 
            color: #3b82f6; 
            padding: 12px 16px; 
            background: #dbeafe; 
            border-radius: 8px; 
            margin: 8px 0;
        }
        .warning { 
            color: #f59e0b; 
            padding: 12px 16px; 
            background: #fef3c7; 
            border-radius: 8px; 
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 16px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th { 
            background: #39A900; 
            color: white; 
            padding: 12px; 
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }
        td { 
            padding: 12px; 
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover { background: #f9fafb; }
        .btn { 
            display: inline-block; 
            padding: 12px 24px; 
            background: #39A900; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            margin: 20px 10px 0 0;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn:hover { 
            background: #2d8700;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(57, 169, 0, 0.3);
        }
        .btn-secondary { 
            background: #6b7280; 
        }
        .btn-secondary:hover { 
            background: #4b5563; 
        }
        code { 
            background: #f3f4f6; 
            padding: 2px 8px; 
            border-radius: 4px; 
            font-family: 'Courier New', monospace;
            color: #39A900;
            font-size: 13px;
        }
        .badge { 
            display: inline-block; 
            padding: 4px 12px; 
            border-radius: 12px; 
            font-size: 12px; 
            font-weight: 600;
        }
        .badge-success { background: #d1fae5; color: #10b981; }
        .badge-error { background: #fee2e2; color: #ef4444; }
        .badge-warning { background: #fef3c7; color: #f59e0b; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔐 Verificación del Sistema de Roles</h1>
        <p class='subtitle'>Diagnóstico completo del sistema de autenticación</p>";

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Verificar tabla ADMINISTRADOR
    echo "<div class='section'>
            <h2>📋 Tabla ADMINISTRADOR</h2>";
    
    $stmt = $db->query("SHOW TABLES LIKE 'ADMINISTRADOR'");
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>✓ Tabla ADMINISTRADOR existe</div>";
        
        // Contar administradores
        $stmt = $db->query("SELECT COUNT(*) as total FROM ADMINISTRADOR");
        $total = $stmt->fetch()['total'];
        echo "<div class='info'>Total de administradores: <strong>$total</strong></div>";
        
        // Listar administradores
        $stmt = $db->query("SELECT admin_id, admin_nombre, admin_correo, admin_estado, admin_ultimo_acceso FROM ADMINISTRADOR");
        $admins = $stmt->fetchAll();
        
        if (!empty($admins)) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Último Acceso</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($admins as $admin) {
                $estado = $admin['admin_estado'] == 'Activo' 
                    ? "<span class='badge badge-success'>Activo</span>" 
                    : "<span class='badge badge-error'>Inactivo</span>";
                $ultimo = $admin['admin_ultimo_acceso'] 
                    ? date('d/m/Y H:i', strtotime($admin['admin_ultimo_acceso'])) 
                    : 'Nunca';
                echo "<tr>
                        <td>{$admin['admin_id']}</td>
                        <td>{$admin['admin_nombre']}</td>
                        <td>{$admin['admin_correo']}</td>
                        <td>$estado</td>
                        <td>$ultimo</td>
                      </tr>";
            }
            echo "</tbody></table>";
        }
    } else {
        echo "<div class='error'>✗ Tabla ADMINISTRADOR no existe</div>";
        echo "<div class='warning'>⚠ Ejecute el script <code>agregar_tabla_admin.php</code> para crear la tabla</div>";
    }
    echo "</div>";
    
    // 2. Verificar tabla COORDINACION
    echo "<div class='section'>
            <h2>👥 Tabla COORDINACION</h2>";
    
    $stmt = $db->query("SHOW TABLES LIKE 'COORDINACION'");
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>✓ Tabla COORDINACION existe</div>";
        
        // Verificar si tiene campo de password
        $stmt = $db->query("SHOW COLUMNS FROM COORDINACION LIKE 'coord_password'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='success'>✓ Campo coord_password existe</div>";
        } else {
            echo "<div class='warning'>⚠ Campo coord_password no existe - Los coordinadores no podrán iniciar sesión</div>";
            echo "<div class='info'>Agregue el campo con: <code>ALTER TABLE COORDINACION ADD coord_password VARCHAR(255) NOT NULL;</code></div>";
        }
        
        // Contar coordinadores
        $stmt = $db->query("SELECT COUNT(*) as total FROM COORDINACION");
        $total = $stmt->fetch()['total'];
        echo "<div class='info'>Total de coordinadores: <strong>$total</strong></div>";
        
        // Listar coordinadores
        $stmt = $db->query("SELECT coord_id, coord_nombre_coordinador, coord_correo FROM COORDINACION LIMIT 10");
        $coords = $stmt->fetchAll();
        
        if (!empty($coords)) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($coords as $coord) {
                $hasPassword = isset($coord['coord_password']) && !empty($coord['coord_password']);
                $passwordStatus = $hasPassword 
                    ? "<span class='badge badge-success'>Configurado</span>" 
                    : "<span class='badge badge-warning'>Sin configurar</span>";
                echo "<tr>
                        <td>{$coord['coord_id']}</td>
                        <td>{$coord['coord_nombre_coordinador']}</td>
                        <td>{$coord['coord_correo']}</td>
                        <td>$passwordStatus</td>
                      </tr>";
            }
            echo "</tbody></table>";
            
            if (!$hasPassword) {
                echo "<div class='warning'>⚠ Los coordinadores necesitan contraseñas configuradas para poder iniciar sesión</div>";
            }
        } else {
            echo "<div class='info'>No hay coordinadores registrados</div>";
        }
    } else {
        echo "<div class='error'>✗ Tabla COORDINACION no existe</div>";
    }
    echo "</div>";
    
    // 3. Verificar archivos del sistema
    echo "<div class='section'>
            <h2>📁 Archivos del Sistema</h2>";
    
    $archivos = [
        'auth/login.php' => 'Página de login',
        'auth/check_auth.php' => 'Verificación de autenticación',
        'auth/logout.php' => 'Cerrar sesión',
        'model/AdministradorModel.php' => 'Modelo de Administrador',
        'conexion.php' => 'Conexión a base de datos'
    ];
    
    foreach ($archivos as $archivo => $descripcion) {
        $ruta = __DIR__ . '/' . $archivo;
        if (file_exists($ruta)) {
            echo "<div class='success'>✓ $descripcion: <code>$archivo</code></div>";
        } else {
            echo "<div class='error'>✗ $descripcion: <code>$archivo</code> no encontrado</div>";
        }
    }
    echo "</div>";
    
    // 4. Resumen y acciones
    echo "<div class='section'>
            <h2>📊 Resumen</h2>";
    
    $stmt = $db->query("SHOW TABLES LIKE 'ADMINISTRADOR'");
    $tablaAdmin = $stmt->rowCount() > 0;
    
    $stmt = $db->query("SHOW TABLES LIKE 'COORDINACION'");
    $tablaCoord = $stmt->rowCount() > 0;
    
    if ($tablaAdmin && $tablaCoord) {
        echo "<div class='success'>✓ Sistema de roles configurado correctamente</div>";
        echo "<div class='info'>
                <strong>Credenciales de prueba:</strong><br>
                <strong>Administrador:</strong> <code>admin@sena.edu.co</code> / <code>password</code>
              </div>";
    } else {
        echo "<div class='warning'>⚠ El sistema necesita configuración adicional</div>";
    }
    
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error de conexión: " . $e->getMessage() . "</div>";
}

echo "
        <div style='margin-top: 30px; padding-top: 30px; border-top: 2px solid #e5e7eb;'>
            <a href='auth/login.php' class='btn'>🔐 Ir al Login</a>
            <a href='index.php' class='btn btn-secondary'>📊 Ir al Dashboard</a>
            <a href='agregar_tabla_admin.php' class='btn btn-secondary'>⚙️ Crear Tabla Admin</a>
        </div>
    </div>
</body>
</html>";
?>
