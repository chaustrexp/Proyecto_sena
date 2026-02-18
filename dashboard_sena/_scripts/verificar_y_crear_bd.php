<?php
/**
 * Script de Verificación y Creación de Base de Datos
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Base de Datos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container { max-width: 800px; margin: 0 auto; }
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .success { border-left: 5px solid #10b981; }
        .error { border-left: 5px solid #ef4444; }
        .warning { border-left: 5px solid #f59e0b; }
        .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .message { color: #6b7280; font-size: 14px; line-height: 1.8; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #39A900;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px 10px 0 0;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { background: #2d8700; }
        .step {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }
        .step-icon.success { background: #10b981; color: white; }
        .step-icon.error { background: #ef4444; color: white; }
        .step-icon.pending { background: #f59e0b; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="title">🔍 Verificación del Sistema</div>
            <div class="message">
                <p>Verificando la configuración de la base de datos...</p>
            </div>
        </div>

        <?php
        $pasos = [];
        $todoOk = true;

        // Paso 1: Verificar conexión a MySQL
        try {
            $conn = new PDO("mysql:host=localhost", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pasos[] = ['icon' => 'success', 'text' => 'Conexión a MySQL establecida'];
        } catch (PDOException $e) {
            $pasos[] = ['icon' => 'error', 'text' => 'Error al conectar a MySQL: ' . $e->getMessage()];
            $todoOk = false;
        }

        // Paso 2: Verificar si existe la base de datos
        if ($todoOk) {
            try {
                $stmt = $conn->query("SHOW DATABASES LIKE 'progsena'");
                $existe = $stmt->rowCount() > 0;
                
                if ($existe) {
                    $pasos[] = ['icon' => 'success', 'text' => 'Base de datos "progsena" encontrada'];
                } else {
                    $pasos[] = ['icon' => 'pending', 'text' => 'Base de datos "progsena" NO existe - Necesita crearse'];
                    $todoOk = false;
                }
            } catch (PDOException $e) {
                $pasos[] = ['icon' => 'error', 'text' => 'Error al verificar base de datos: ' . $e->getMessage()];
                $todoOk = false;
            }
        }

        // Paso 3: Si existe, verificar tablas
        if ($todoOk) {
            try {
                $conn->exec("USE progsena");
                $stmt = $conn->query("SHOW TABLES");
                $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if (count($tablas) > 0) {
                    $pasos[] = ['icon' => 'success', 'text' => 'Base de datos contiene ' . count($tablas) . ' tablas'];
                } else {
                    $pasos[] = ['icon' => 'pending', 'text' => 'Base de datos existe pero está vacía'];
                    $todoOk = false;
                }
            } catch (PDOException $e) {
                $pasos[] = ['icon' => 'error', 'text' => 'Error al verificar tablas: ' . $e->getMessage()];
                $todoOk = false;
            }
        }

        // Mostrar resultados
        echo '<div class="card">';
        echo '<div class="title">📋 Resultados de la Verificación</div>';
        echo '<div class="message">';
        
        foreach ($pasos as $paso) {
            echo '<div class="step">';
            echo '<div class="step-icon ' . $paso['icon'] . '">';
            if ($paso['icon'] == 'success') echo '✓';
            elseif ($paso['icon'] == 'error') echo '✗';
            else echo '!';
            echo '</div>';
            echo '<div>' . $paso['text'] . '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';

        // Mostrar acciones según el resultado
        if ($todoOk) {
            echo '<div class="card success">';
            echo '<div class="title">✅ Sistema Listo</div>';
            echo '<div class="message">';
            echo '<p>La base de datos está configurada correctamente y lista para usar.</p>';
            echo '<a href="auth/login.php" class="btn">🔐 Ir al Login</a>';
            echo '<a href="test_conexion.php" class="btn" style="background: #6b7280;">🔍 Ver Diagnóstico Completo</a>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="card warning">';
            echo '<div class="title">⚠️ Acción Requerida</div>';
            echo '<div class="message">';
            echo '<p><strong>La base de datos necesita ser creada.</strong></p>';
            echo '<p style="margin-top: 10px;">Tienes dos opciones:</p>';
            echo '<ol style="margin: 15px 0 15px 20px; line-height: 2;">';
            echo '<li><strong>Automática:</strong> Usar el script de migración (Recomendado)</li>';
            echo '<li><strong>Manual:</strong> Crear la base de datos en phpMyAdmin</li>';
            echo '</ol>';
            
            // Botón para crear automáticamente
            if (isset($_POST['crear_bd'])) {
                echo '<div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #3b82f6;">';
                echo '<strong>Creando base de datos...</strong><br>';
                
                try {
                    // Crear base de datos
                    $conn->exec("CREATE DATABASE IF NOT EXISTS progsena DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    echo '✓ Base de datos "progsena" creada<br>';
                    
                    // Leer y ejecutar el script SQL
                    $sqlFile = __DIR__ . '/_database/estructura_completa_ProgSENA.sql';
                    if (file_exists($sqlFile)) {
                        $sql = file_get_contents($sqlFile);
                        $conn->exec("USE progsena");
                        
                        // Ejecutar el script
                        $statements = explode(';', $sql);
                        $creadas = 0;
                        
                        foreach ($statements as $statement) {
                            $statement = trim($statement);
                            if (!empty($statement) && 
                                !preg_match('/^--/', $statement) && 
                                !preg_match('/^SET/', $statement) &&
                                !preg_match('/^CREATE DATABASE/', $statement) &&
                                !preg_match('/^USE/', $statement)) {
                                try {
                                    $conn->exec($statement);
                                    if (preg_match('/CREATE TABLE/i', $statement)) {
                                        $creadas++;
                                    }
                                } catch (PDOException $e) {
                                    // Ignorar errores de "ya existe"
                                }
                            }
                        }
                        
                        echo "✓ $creadas tablas creadas<br>";
                        echo '<strong style="color: #10b981;">¡Base de datos lista!</strong><br>';
                        echo '<a href="auth/login.php" class="btn" style="margin-top: 10px;">🔐 Ir al Login</a>';
                    } else {
                        echo '✗ No se encontró el archivo SQL<br>';
                    }
                } catch (PDOException $e) {
                    echo '✗ Error: ' . htmlspecialchars($e->getMessage()) . '<br>';
                }
                
                echo '</div>';
            } else {
                echo '<form method="POST" style="margin-top: 15px;">';
                echo '<button type="submit" name="crear_bd" class="btn">🚀 Crear Base de Datos Automáticamente</button>';
                echo '<a href="migrar_bd.php" class="btn" style="background: #6b7280;">📋 Usar Asistente de Migración</a>';
                echo '<a href="http://localhost/phpmyadmin" target="_blank" class="btn" style="background: #6b7280;">🔧 Abrir phpMyAdmin</a>';
                echo '</form>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
