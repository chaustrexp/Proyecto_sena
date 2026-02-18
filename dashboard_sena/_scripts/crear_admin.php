<?php
/**
 * Script para crear usuario administrador
 */
require_once __DIR__ . '/conexion.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar si ya existe un administrador
    $stmt = $db->query("SELECT COUNT(*) as total FROM ADMINISTRADOR");
    $result = $stmt->fetch();
    
    if ($result['total'] == 0) {
        // Crear administrador por defecto
        $email = 'admin@sena.edu.co';
        $password = password_hash('password', PASSWORD_DEFAULT);
        $nombre = 'Administrador';
        
        $stmt = $db->prepare("INSERT INTO ADMINISTRADOR (admin_nombre, admin_correo, admin_password, admin_estado) VALUES (?, ?, ?, 'Activo')");
        $stmt->execute([$nombre, $email, $password]);
        
        echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Administrador Creado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        h1 {
            color: #39A900;
            margin-bottom: 20px;
        }
        .success {
            background: #E8F5E8;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #39A900;
        }
        .credentials {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .credentials strong {
            color: #39A900;
        }
        .btn {
            display: inline-block;
            padding: 12px 32px;
            background: #39A900;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 20px;
        }
        .btn:hover {
            background: #2d8700;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>✅ Administrador Creado</h1>
        <div class='success'>
            <p>El usuario administrador ha sido creado exitosamente.</p>
        </div>
        <div class='credentials'>
            <p><strong>Email:</strong> admin@sena.edu.co</p>
            <p><strong>Password:</strong> password</p>
        </div>
        <a href='auth/login.php' class='btn'>🔐 Ir al Login</a>
    </div>
</body>
</html>";
    } else {
        echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Administrador Existente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        h1 {
            color: #f59e0b;
            margin-bottom: 20px;
        }
        .info {
            background: #FEF3C7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .btn {
            display: inline-block;
            padding: 12px 32px;
            background: #39A900;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 20px;
        }
        .btn:hover {
            background: #2d8700;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>ℹ️ Administrador Ya Existe</h1>
        <div class='info'>
            <p>Ya existe un usuario administrador en el sistema.</p>
            <p>Total de administradores: {$result['total']}</p>
        </div>
        <a href='auth/login.php' class='btn'>🔐 Ir al Login</a>
    </div>
</body>
</html>";
    }
    
} catch (Exception $e) {
    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        h1 {
            color: #ef4444;
            margin-bottom: 20px;
        }
        .error {
            background: #FEE2E2;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ef4444;
            text-align: left;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>❌ Error</h1>
        <div class='error'>
            <p><strong>Error al crear administrador:</strong></p>
            <code>" . htmlspecialchars($e->getMessage()) . "</code>
        </div>
    </div>
</body>
</html>";
}
?>
