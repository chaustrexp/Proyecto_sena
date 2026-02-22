<?php
/**
 * Script de Reparación: Tabla coordinacion
 * Soluciona el error: Table 'progsena.coordinacion' doesn't exist in engine
 */

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'progsena');
define('DB_USER', 'root');
define('DB_PASS', '');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reparar Tabla Coordinación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .content { padding: 30px; }
        .step {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #39A900;
        }
        .step h2 {
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid;
        }
        .success {
            background: #d1fae5;
            border-color: #065f46;
            color: #065f46;
        }
        .error {
            background: #fee2e2;
            border-color: #991b1b;
            color: #991b1b;
        }
        .info {
            background: #dbeafe;
            border-color: #1e40af;
            color: #1e40af;
        }
        .warning {
            background: #fef3c7;
            border-color: #92400e;
            color: #92400e;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #39A900;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        .btn:hover {
            background: #2d8000;
            transform: translateY(-2px);
        }
        pre {
            background: #1f2937;
            color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 12px;
            line-height: 1.6;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        th {
            background: #f3f4f6;
            font-weight: 600;
        }
        .code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Reparación de Tabla coordinacion</h1>
            <p>Solución al error: Table doesn't exist in engine</p>
        </div>
        
        <div class="content">
            <?php
            $pasos_exitosos = 0;
            $total_pasos = 5;
            
            // PASO 1: Conectar a la base de datos
            echo "<div class='step'>";
            echo "<h2>Paso 1: Conectando a la base de datos</h2>";
            
            try {
                $conn = new PDO(
                    "mysql:host=" . DB_HOST . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                
                echo "<div class='message success'>✓ Conexión exitosa al servidor MySQL</div>";
                $pasos_exitosos++;
                
            } catch (PDOException $e) {
                echo "<div class='message error'>✗ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</div>";
                echo "<div class='message info'>";
                echo "<strong>Soluciones posibles:</strong><br>";
                echo "1. Verifica que XAMPP esté ejecutándose<br>";
                echo "2. Verifica que MySQL esté activo<br>";
                echo "3. Verifica las credenciales de conexión";
                echo "</div>";
                echo "</div></div></body></html>";
                exit;
            }
            echo "</div>";
            
            // PASO 2: Verificar/Crear base de datos
            echo "<div class='step'>";
            echo "<h2>Paso 2: Verificando base de datos 'progsena'</h2>";
            
            try {
                // Verificar si existe la base de datos
                $stmt = $conn->query("SHOW DATABASES LIKE 'progsena'");
                $db_existe = $stmt->rowCount() > 0;
                
                if (!$db_existe) {
                    echo "<div class='message warning'>⚠ La base de datos 'progsena' no existe. Creándola...</div>";
                    $conn->exec("CREATE DATABASE IF NOT EXISTS progsena CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    echo "<div class='message success'>✓ Base de datos 'progsena' creada</div>";
                } else {
                    echo "<div class='message success'>✓ Base de datos 'progsena' existe</div>";
                }
                
                // Conectar a la base de datos específica
                $conn->exec("USE progsena");
                $pasos_exitosos++;
                
            } catch (PDOException $e) {
                echo "<div class='message error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            echo "</div>";
            
            // PASO 3: Verificar tabla COORDINACION
            echo "<div class='step'>";
            echo "<h2>Paso 3: Verificando tabla 'COORDINACION'</h2>";
            
            try {
                $stmt = $conn->query("SHOW TABLES LIKE 'COORDINACION'");
                $tabla_existe = $stmt->rowCount() > 0;
                
                if ($tabla_existe) {
                    echo "<div class='message info'>ℹ️ La tabla 'COORDINACION' existe. Verificando integridad...</div>";
                    
                    // Intentar hacer una consulta simple
                    try {
                        $stmt = $conn->query("SELECT COUNT(*) as total FROM COORDINACION");
                        $result = $stmt->fetch();
                        echo "<div class='message success'>✓ Tabla funcional con {$result['total']} registros</div>";
                        $pasos_exitosos++;
                        
                        // Mostrar estructura
                        echo "<h3>Estructura actual:</h3>";
                        $stmt = $conn->query("DESCRIBE COORDINACION");
                        $columnas = $stmt->fetchAll();
                        
                        echo "<table>";
                        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th></tr>";
                        foreach ($columnas as $col) {
                            echo "<tr>";
                            echo "<td>{$col['Field']}</td>";
                            echo "<td>{$col['Type']}</td>";
                            echo "<td>{$col['Null']}</td>";
                            echo "<td>{$col['Key']}</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        
                    } catch (PDOException $e) {
                        echo "<div class='message error'>✗ La tabla existe pero está dañada: " . htmlspecialchars($e->getMessage()) . "</div>";
                        echo "<div class='message warning'>⚠ Eliminando tabla dañada...</div>";
                        $conn->exec("DROP TABLE IF EXISTS COORDINACION");
                        $tabla_existe = false;
                    }
                }
                
                if (!$tabla_existe) {
                    echo "<div class='message warning'>⚠ La tabla 'COORDINACION' no existe o está dañada</div>";
                }
                
            } catch (PDOException $e) {
                echo "<div class='message error'>✗ Error al verificar tabla: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            echo "</div>";
            
            // PASO 4: Crear tabla si no existe
            if (!$tabla_existe || !$pasos_exitosos >= 3) {
                echo "<div class='step'>";
                echo "<h2>Paso 4: Creando tabla 'COORDINACION'</h2>";
                
                try {
                    // Primero verificar que existe CENTRO_FORMACION
                    $stmt = $conn->query("SHOW TABLES LIKE 'CENTRO_FORMACION'");
                    $centro_existe = $stmt->rowCount() > 0;
                    
                    if (!$centro_existe) {
                        echo "<div class='message error'>✗ La tabla CENTRO_FORMACION no existe. Es necesaria para la relación.</div>";
                        echo "<div class='message info'>";
                        echo "<strong>Solución:</strong> Primero debes crear la tabla CENTRO_FORMACION o ejecutar el script completo de estructura de base de datos.";
                        echo "</div>";
                    } else {
                        $sql = "CREATE TABLE COORDINACION (
                            coord_id INT AUTO_INCREMENT PRIMARY KEY,
                            coord_descripcion TEXT NOT NULL,
                            coord_nombre_coordinador VARCHAR(100),
                            coord_correo VARCHAR(100),
                            coord_password VARCHAR(255),
                            CENTRO_FORMACION_cent_id INT,
                            coord_fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            coord_activo TINYINT(1) DEFAULT 1,
                            INDEX idx_centro (CENTRO_FORMACION_cent_id),
                            CONSTRAINT fk_coordinacion_centro 
                                FOREIGN KEY (CENTRO_FORMACION_cent_id) 
                                REFERENCES CENTRO_FORMACION(cent_id)
                                ON DELETE SET NULL
                                ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                        
                        echo "<h3>SQL a ejecutar:</h3>";
                        echo "<pre>" . htmlspecialchars($sql) . "</pre>";
                        
                        $conn->exec($sql);
                        
                        echo "<div class='message success'>✓ Tabla 'COORDINACION' creada exitosamente</div>";
                        $pasos_exitosos++;
                        
                        // Insertar datos de prueba
                        echo "<h3>Insertando datos de prueba...</h3>";
                        
                        $stmt = $conn->query("SELECT cent_id FROM CENTRO_FORMACION LIMIT 1");
                        $centro = $stmt->fetch();
                        
                        if ($centro) {
                            $stmt = $conn->prepare("
                                INSERT INTO COORDINACION 
                                (coord_descripcion, coord_nombre_coordinador, coord_correo, coord_password, CENTRO_FORMACION_cent_id) 
                                VALUES 
                                ('Coordinación Académica', 'Coordinador Principal', 'coordinacion@sena.edu.co', ?, ?),
                                ('Coordinación de Formación', 'Coordinador Técnico', 'formacion@sena.edu.co', ?, ?),
                                ('Coordinación de Proyectos', 'Coordinador de Proyectos', 'proyectos@sena.edu.co', ?, ?)
                            ");
                            
                            $password_hash = password_hash('123456', PASSWORD_DEFAULT);
                            $stmt->execute([
                                $password_hash, $centro['cent_id'],
                                $password_hash, $centro['cent_id'],
                                $password_hash, $centro['cent_id']
                            ]);
                            
                            echo "<div class='message success'>✓ Se insertaron 3 registros de prueba (password: 123456)</div>";
                        } else {
                            echo "<div class='message warning'>⚠ No hay centros de formación para crear la relación. Tabla creada sin datos.</div>";
                        }
                    }
                    
                } catch (PDOException $e) {
                    echo "<div class='message error'>✗ Error al crear tabla: " . htmlspecialchars($e->getMessage()) . "</div>";
                    
                    // Mostrar información adicional del error
                    echo "<div class='message info'>";
                    echo "<strong>Código de error:</strong> " . $e->getCode() . "<br>";
                    echo "<strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage());
                    echo "</div>";
                }
                echo "</div>";
            }
            
            // PASO 5: Verificación final
            echo "<div class='step'>";
            echo "<h2>Paso 5: Verificación Final</h2>";
            
            try {
                $stmt = $conn->query("SELECT * FROM COORDINACION LIMIT 5");
                $coordinaciones = $stmt->fetchAll();
                
                if (count($coordinaciones) > 0) {
                    echo "<div class='message success'>✓ Tabla 'COORDINACION' funcionando correctamente</div>";
                    $pasos_exitosos++;
                    
                    echo "<h3>Registros actuales:</h3>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Descripción</th><th>Coordinador</th><th>Correo</th><th>Activo</th></tr>";
                    foreach ($coordinaciones as $coord) {
                        echo "<tr>";
                        echo "<td>{$coord['coord_id']}</td>";
                        echo "<td>{$coord['coord_descripcion']}</td>";
                        echo "<td>" . ($coord['coord_nombre_coordinador'] ?? 'N/A') . "</td>";
                        echo "<td>" . ($coord['coord_correo'] ?? 'N/A') . "</td>";
                        echo "<td>" . ($coord['coord_activo'] ? '✓' : '✗') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div class='message warning'>⚠ La tabla existe pero no tiene registros</div>";
                }
                
            } catch (PDOException $e) {
                echo "<div class='message error'>✗ Error en verificación final: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            echo "</div>";
            
            // Resumen
            echo "<div class='step' style='border-left-color: " . ($pasos_exitosos >= 4 ? "#065f46" : "#991b1b") . ";'>";
            echo "<h2>📊 Resumen</h2>";
            
            $porcentaje = ($pasos_exitosos / $total_pasos) * 100;
            echo "<div style='background: #e5e7eb; height: 30px; border-radius: 15px; overflow: hidden; margin: 15px 0;'>";
            echo "<div style='background: linear-gradient(90deg, #39A900, #2d8000); height: 100%; width: {$porcentaje}%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;'>";
            echo "{$porcentaje}%";
            echo "</div>";
            echo "</div>";
            
            echo "<p style='text-align: center; font-size: 16px; margin: 15px 0;'>";
            echo "<strong>Pasos completados: {$pasos_exitosos}/{$total_pasos}</strong>";
            echo "</p>";
            
            if ($pasos_exitosos >= 4) {
                echo "<div class='message success' style='font-size: 16px; text-align: center; padding: 20px;'>";
                echo "🎉 <strong>¡Reparación exitosa!</strong><br>";
                echo "La tabla 'COORDINACION' está lista para usar.";
                echo "</div>";
            } else {
                echo "<div class='message error' style='font-size: 16px; text-align: center; padding: 20px;'>";
                echo "⚠️ <strong>Reparación incompleta</strong><br>";
                echo "Revisa los errores anteriores y contacta al administrador si persiste el problema.";
                echo "</div>";
            }
            
            echo "</div>";
            
            // Botones de acción
            echo "<div style='text-align: center; margin-top: 30px;'>";
            if ($pasos_exitosos >= 4) {
                echo "<a href='/Gestion-sena/dashboard_sena/' class='btn'>✓ Ir al Dashboard</a>";
            }
            echo "<a href='" . $_SERVER['PHP_SELF'] . "' class='btn' style='background: #6b7280;'>🔄 Ejecutar Nuevamente</a>";
            echo "</div>";
            ?>
        </div>
    </div>
</body>
</html>
