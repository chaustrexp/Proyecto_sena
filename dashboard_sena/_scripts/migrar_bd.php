<?php
/**
 * Script de Migración Automática
 * Crea la nueva base de datos ProgSENA
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migración a ProgSENA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        .header {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 { color: #39A900; font-size: 32px; margin-bottom: 10px; }
        .header p { color: #6b7280; font-size: 16px; }
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
        .info { border-left: 5px solid #3b82f6; }
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
        .btn:hover { background: #2d8700; transform: translateY(-2px); }
        .btn-secondary { background: #6b7280; }
        .btn-secondary:hover { background: #4b5563; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .step {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #39A900;
        }
        .step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: #39A900;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: 700;
            margin-right: 10px;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #ef4444;
        }
        .log {
            background: #1f2937;
            color: #10b981;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 15px;
        }
        .log-error { color: #ef4444; }
        .log-success { color: #10b981; }
        .log-info { color: #3b82f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 Migración a ProgSENA</h1>
            <p>Asistente de Migración de Base de Datos</p>
        </div>

        <?php
        if (isset($_POST['ejecutar_migracion'])) {
            echo '<div class="card info">';
            echo '<div class="title">📝 Ejecutando Migración...</div>';
            echo '<div class="log">';
            
            try {
                // Conectar a MySQL sin seleccionar base de datos
                $conn = new PDO("mysql:host=localhost", "root", "");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                echo '<div class="log-info">✓ Conectado a MySQL</div>';
                
                // Leer el archivo SQL
                $sqlFile = __DIR__ . '/_database/estructura_completa_ProgSENA.sql';
                if (!file_exists($sqlFile)) {
                    throw new Exception("No se encontró el archivo SQL: $sqlFile");
                }
                
                $sql = file_get_contents($sqlFile);
                echo '<div class="log-info">✓ Archivo SQL cargado</div>';
                
                // Dividir en statements individuales y ejecutar
                $statements = explode(';', $sql);
                
                $success = 0;
                $errors = 0;
                
                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    
                    // Saltar comentarios y líneas vacías
                    if (empty($statement) || 
                        preg_match('/^--/', $statement) || 
                        preg_match('/^\/\*/', $statement)) {
                        continue;
                    }
                    
                    // Saltar SET statements iniciales
                    if (preg_match('/^SET @OLD_/', $statement) || 
                        preg_match('/^SET SQL_MODE/', $statement) ||
                        preg_match('/^SET FOREIGN_KEY_CHECKS/', $statement) ||
                        preg_match('/^SET UNIQUE_CHECKS/', $statement)) {
                        continue;
                    }
                    
                    try {
                        $conn->exec($statement);
                        $success++;
                        
                        // Mostrar qué se está ejecutando
                        if (preg_match('/CREATE DATABASE/i', $statement)) {
                            echo '<div class="log-success">✓ Base de datos creada</div>';
                        } elseif (preg_match('/CREATE TABLE.*`(\w+)`/i', $statement, $matches)) {
                            echo '<div class="log-info">→ Tabla ' . $matches[1] . ' creada</div>';
                        }
                    } catch (PDOException $e) {
                        // Ignorar errores de "ya existe"
                        if (strpos($e->getMessage(), 'already exists') === false && 
                            strpos($e->getMessage(), 'Can\'t create database') === false) {
                            echo '<div class="log-error">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                            $errors++;
                        }
                    }
                }
                
                echo '<div class="log-success">✓ Base de datos ProgSENA creada exitosamente</div>';
                echo '<div class="log-info">→ Statements ejecutados: ' . $success . '</div>';
                if ($errors > 0) {
                    echo '<div class="log-error">→ Errores: ' . $errors . '</div>';
                }
                
                // Verificar tablas creadas
                $conn->exec("USE progsena");
                $stmt = $conn->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                echo '<div class="log-success">✓ Tablas creadas: ' . count($tables) . '</div>';
                foreach ($tables as $table) {
                    echo '<div class="log-info">  → ' . $table . '</div>';
                }
                
                echo '</div>';
                echo '<div class="message" style="margin-top: 20px;">';
                echo '<strong>¡Migración completada!</strong><br>';
                echo 'La base de datos ProgSENA ha sido creada exitosamente.';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="card success">';
                echo '<div class="title">✅ Próximos Pasos</div>';
                echo '<div class="message">';
                echo '<ol style="margin-left: 20px; line-height: 2;">';
                echo '<li>Verificar la conexión usando el script de diagnóstico</li>';
                echo '<li>Insertar datos de prueba (opcional)</li>';
                echo '<li>Actualizar las vistas del sistema</li>';
                echo '</ol>';
                echo '</div>';
                echo '<a href="test_conexion.php" class="btn">🔍 Verificar Conexión</a>';
                echo '<a href="test_insertar_datos.php" class="btn btn-secondary">➕ Insertar Datos de Prueba</a>';
                echo '</div>';
                
            } catch (Exception $e) {
                echo '<div class="log-error">✗ ERROR: ' . htmlspecialchars($e->getMessage()) . '</div>';
                echo '</div>';
                echo '<div class="message" style="margin-top: 20px; color: #ef4444;">';
                echo '<strong>Error durante la migración.</strong><br>';
                echo 'Por favor, revisa el error y vuelve a intentarlo.';
                echo '</div>';
                echo '</div>';
            }
        } else {
            ?>
            
            <div class="card warning">
                <div class="title">⚠️ Antes de Continuar</div>
                <div class="message">
                    <p><strong>Este script creará una nueva base de datos llamada <code>ProgSENA</code>.</strong></p>
                    <p style="margin-top: 10px;">Si ya tienes datos en <code>dashboard_sena</code>, asegúrate de hacer un backup antes de continuar.</p>
                </div>
            </div>

            <div class="card">
                <div class="title">📋 Pasos de la Migración</div>
                <div class="message">
                    <div class="step">
                        <span class="step-number">1</span>
                        <strong>Crear Base de Datos:</strong> Se creará la base de datos <code>ProgSENA</code> con la nueva estructura
                    </div>
                    <div class="step">
                        <span class="step-number">2</span>
                        <strong>Crear Tablas:</strong> Se crearán todas las tablas con sus relaciones
                    </div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <strong>Verificar:</strong> Se verificará que todo se haya creado correctamente
                    </div>
                </div>
            </div>

            <div class="card info">
                <div class="title">📊 Cambios Principales</div>
                <div class="message">
                    <ul style="margin-left: 20px; line-height: 2;">
                        <li>Nombre de BD: <code>dashboard_sena</code> → <code>ProgSENA</code></li>
                        <li>Nombres de campos con prefijos (<code>titpro_</code>, <code>prog_</code>, <code>comp_</code>, etc.)</li>
                        <li>Nuevos campos de contraseña para instructores y coordinadores</li>
                        <li>Nueva tabla <code>INSTRU_COMPETENCIA</code></li>
                        <li>Tabla <code>DETALLExASIGNACION</code> reestructurada</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="title">🚀 Ejecutar Migración</div>
                <div class="message">
                    <p>Haz clic en el botón para iniciar la migración automática.</p>
                    <form method="POST" onsubmit="return confirm('¿Estás seguro de que quieres ejecutar la migración?');">
                        <button type="submit" name="ejecutar_migracion" class="btn">
                            ▶️ Ejecutar Migración
                        </button>
                        <a href="_docs/MIGRACION_NUEVA_BD.md" class="btn btn-secondary" target="_blank">
                            📖 Ver Documentación
                        </a>
                        <a href="index.php" class="btn btn-secondary">
                            ← Volver al Dashboard
                        </a>
                    </form>
                </div>
            </div>
            
            <?php
        }
        ?>
    </div>
</body>
</html>
