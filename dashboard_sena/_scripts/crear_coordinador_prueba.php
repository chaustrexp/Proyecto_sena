<?php
/**
 * Script para Crear Coordinador de Prueba
 * Crea un coordinador con contraseña para probar el login
 */

require_once 'conexion.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Crear Coordinador de Prueba - Sistema SENA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container { 
            max-width: 700px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #1f2937; 
            margin-bottom: 10px; 
            font-size: 28px;
        }
        .subtitle { 
            color: #6b7280; 
            margin-bottom: 30px; 
            font-size: 14px;
        }
        .success { 
            color: #10b981; 
            padding: 16px; 
            background: #d1fae5; 
            border-radius: 8px; 
            margin: 16px 0;
            border-left: 4px solid #10b981;
        }
        .error { 
            color: #ef4444; 
            padding: 16px; 
            background: #fee2e2; 
            border-radius: 8px; 
            margin: 16px 0;
            border-left: 4px solid #ef4444;
        }
        .info { 
            color: #3b82f6; 
            padding: 16px; 
            background: #dbeafe; 
            border-radius: 8px; 
            margin: 16px 0;
            border-left: 4px solid #3b82f6;
        }
        .credentials { 
            background: #f9fafb; 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0;
            border: 2px solid #e5e7eb;
        }
        .credentials h3 { 
            color: #1f2937; 
            margin-bottom: 12px;
            font-size: 16px;
        }
        .credentials p { 
            margin: 8px 0;
            color: #374151;
        }
        code { 
            background: white; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-family: 'Courier New', monospace;
            color: #39A900;
            font-weight: 600;
        }
        .btn { 
            display: inline-block; 
            padding: 12px 24px; 
            background: #39A900; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            margin: 10px 10px 0 0;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn:hover { 
            background: #2d8700;
            transform: translateY(-2px);
        }
        .btn-secondary { 
            background: #6b7280; 
        }
        .btn-secondary:hover { 
            background: #4b5563; 
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>👥 Crear Coordinador de Prueba</h1>
        <p class='subtitle'>Script para agregar un coordinador con contraseña para testing</p>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar si existe un centro de formación
    $stmt = $db->query("SELECT cent_id, cent_nombre FROM CENTRO_FORMACION LIMIT 1");
    $centro = $stmt->fetch();
    
    if (!$centro) {
        echo "<div class='error'>✗ No hay centros de formación registrados. Cree uno primero.</div>";
        echo "<a href='views/centro_formacion/crear.php' class='btn'>Crear Centro de Formación</a>";
    } else {
        // Verificar si ya existe el coordinador de prueba
        $stmt = $db->prepare("SELECT * FROM COORDINACION WHERE coord_correo = ?");
        $stmt->execute(['coordinador@sena.edu.co']);
        $existe = $stmt->fetch();
        
        if ($existe) {
            echo "<div class='info'>ℹ El coordinador de prueba ya existe</div>";
            
            // Actualizar password si no tiene
            if (empty($existe['coord_password']) || strlen($existe['coord_password']) < 10) {
                $stmt = $db->prepare("UPDATE COORDINACION SET coord_password = ? WHERE coord_id = ?");
                $stmt->execute([
                    password_hash('password', PASSWORD_DEFAULT),
                    $existe['coord_id']
                ]);
                echo "<div class='success'>✓ Contraseña actualizada</div>";
            }
            
            echo "<div class='credentials'>
                    <h3>🔑 Credenciales del Coordinador</h3>
                    <p><strong>Email:</strong> <code>coordinador@sena.edu.co</code></p>
                    <p><strong>Password:</strong> <code>password</code></p>
                    <p><strong>Nombre:</strong> {$existe['coord_nombre_coordinador']}</p>
                    <p><strong>Centro:</strong> {$centro['cent_nombre']}</p>
                  </div>";
        } else {
            // Crear nuevo coordinador
            $stmt = $db->prepare("
                INSERT INTO COORDINACION 
                (coord_descripcion, CENTRO_FORMACION_cent_id, coord_nombre_coordinador, coord_correo, coord_password) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $resultado = $stmt->execute([
                'Coordinación de Prueba',
                $centro['cent_id'],
                'Coordinador de Prueba',
                'coordinador@sena.edu.co',
                password_hash('password', PASSWORD_DEFAULT)
            ]);
            
            if ($resultado) {
                echo "<div class='success'>✓ Coordinador creado exitosamente</div>";
                echo "<div class='credentials'>
                        <h3>🔑 Credenciales del Coordinador</h3>
                        <p><strong>Email:</strong> <code>coordinador@sena.edu.co</code></p>
                        <p><strong>Password:</strong> <code>password</code></p>
                        <p><strong>Nombre:</strong> Coordinador de Prueba</p>
                        <p><strong>Centro:</strong> {$centro['cent_nombre']}</p>
                      </div>";
            } else {
                echo "<div class='error'>✗ Error al crear el coordinador</div>";
            }
        }
        
        echo "<div class='info'>
                <strong>💡 Nota:</strong> Ahora puede iniciar sesión con el rol de Coordinador usando estas credenciales.
              </div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
}

echo "
        <div style='margin-top: 30px; padding-top: 30px; border-top: 2px solid #e5e7eb;'>
            <a href='auth/login.php' class='btn'>🔐 Ir al Login</a>
            <a href='verificar_roles.php' class='btn btn-secondary'>🔍 Verificar Sistema</a>
            <a href='index.php' class='btn btn-secondary'>📊 Dashboard</a>
        </div>
    </div>
</body>
</html>";
?>
