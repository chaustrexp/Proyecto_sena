<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tabla Coordinación</title>
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
            background: linear-gradient(135deg, #39A900 0%, #2d8000 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .content { padding: 30px; }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
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
        }
        .btn:hover {
            background: #2d8000;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(57, 169, 0, 0.3);
        }
        pre {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f3f4f6;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Crear Tabla Coordinación</h1>
            <p>Script de creación de tabla faltante</p>
        </div>
        
        <div class="content">
            <?php
            require_once __DIR__ . '/../conexion.php';
            
            try {
                echo "<h2>1. Verificando si la tabla existe...</h2>";
                
                // Verificar si la tabla existe
                $stmt = $conn->query("SHOW TABLES LIKE 'coordinacion'");
                $existe = $stmt->rowCount() > 0;
                
                if ($existe) {
                    echo "<div class='message info'>✓ La tabla 'coordinacion' ya existe</div>";
                    
                    // Mostrar estructura
                    echo "<h3>Estructura actual:</h3>";
                    $stmt = $conn->query("DESCRIBE coordinacion");
                    $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo "<table>";
                    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th></tr>";
                    foreach ($columnas as $col) {
                        echo "<tr>";
                        echo "<td>{$col['Field']}</td>";
                        echo "<td>{$col['Type']}</td>";
                        echo "<td>{$col['Null']}</td>";
                        echo "<td>{$col['Key']}</td>";
                        echo "<td>{$col['Default']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    // Contar registros
                    $stmt = $conn->query("SELECT COUNT(*) as total FROM coordinacion");
                    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                    echo "<div class='message info'>Total de registros: <strong>{$total}</strong></div>";
                    
                } else {
                    echo "<div class='message warning'>⚠ La tabla 'coordinacion' NO existe</div>";
                    
                    echo "<h2>2. Creando tabla...</h2>";
                    
                    $sql = "CREATE TABLE IF NOT EXISTS coordinacion (
                        coord_id INT PRIMARY KEY AUTO_INCREMENT,
                        coord_nombre VARCHAR(100) NOT NULL,
                        coord_descripcion TEXT,
                        coord_responsable VARCHAR(100),
                        coord_correo VARCHAR(100),
                        coord_telefono VARCHAR(20),
                        CENTRO_FORMACION_cent_id INT,
                        coord_fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        coord_activo TINYINT(1) DEFAULT 1,
                        FOREIGN KEY (CENTRO_FORMACION_cent_id) 
                            REFERENCES CENTRO_FORMACION(cent_id)
                            ON DELETE SET NULL
                            ON UPDATE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                    
                    echo "<h3>SQL a ejecutar:</h3>";
                    echo "<pre>" . htmlspecialchars($sql) . "</pre>";
                    
                    $conn->exec($sql);
                    
                    echo "<div class='message success'>✓ Tabla 'coordinacion' creada exitosamente</div>";
                    
                    echo "<h2>3. Insertando datos de prueba...</h2>";
                    
                    // Obtener un centro de formación para la relación
                    $stmt = $conn->query("SELECT cent_id FROM CENTRO_FORMACION LIMIT 1");
                    $centro = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($centro) {
                        $stmt = $conn->prepare("
                            INSERT INTO coordinacion 
                            (coord_nombre, coord_descripcion, coord_responsable, coord_correo, CENTRO_FORMACION_cent_id) 
                            VALUES 
                            ('Coordinación Académica', 'Coordinación de programas académicos', 'Coordinador Principal', 'coordinacion@sena.edu.co', ?),
                            ('Coordinación de Formación', 'Coordinación de formación técnica', 'Coordinador Técnico', 'formacion@sena.edu.co', ?),
                            ('Coordinación de Proyectos', 'Coordinación de proyectos especiales', 'Coordinador de Proyectos', 'proyectos@sena.edu.co', ?)
                        ");
                        
                        $stmt->execute([$centro['cent_id'], $centro['cent_id'], $centro['cent_id']]);
                        
                        echo "<div class='message success'>✓ Se insertaron 3 registros de prueba</div>";
                    } else {
                        echo "<div class='message warning'>⚠ No se encontraron centros de formación para crear la relación</div>";
                    }
                    
                    echo "<h2>4. Verificación final</h2>";
                    $stmt = $conn->query("SELECT * FROM coordinacion");
                    $coordinaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($coordinaciones) > 0) {
                        echo "<table>";
                        echo "<tr><th>ID</th><th>Nombre</th><th>Responsable</th><th>Correo</th></tr>";
                        foreach ($coordinaciones as $coord) {
                            echo "<tr>";
                            echo "<td>{$coord['coord_id']}</td>";
                            echo "<td>{$coord['coord_nombre']}</td>";
                            echo "<td>{$coord['coord_responsable']}</td>";
                            echo "<td>{$coord['coord_correo']}</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                }
                
                echo "<div style='margin-top: 30px; text-align: center;'>";
                echo "<a href='/Gestion-sena/dashboard_sena/' class='btn'>Volver al Dashboard</a>";
                echo "</div>";
                
            } catch (PDOException $e) {
                echo "<div class='message error'>";
                echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage());
                echo "</div>";
                
                echo "<h3>Posibles soluciones:</h3>";
                echo "<ul style='line-height: 2;'>";
                echo "<li>Verifica que la base de datos 'progsena' exista</li>";
                echo "<li>Verifica que la tabla CENTRO_FORMACION exista (es una dependencia)</li>";
                echo "<li>Verifica los permisos del usuario de la base de datos</li>";
                echo "</ul>";
            }
            ?>
        </div>
    </div>
</body>
</html>
