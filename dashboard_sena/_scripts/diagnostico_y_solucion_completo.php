<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico y Solución Completa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
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
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #39A900;
        }
        .section h2 {
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 20px;
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
            box-shadow: 0 4px 12px rgba(57, 169, 0, 0.3);
        }
        .btn-secondary {
            background: #6b7280;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        th {
            background: #39A900;
            color: white;
            font-weight: 600;
        }
        tr:hover { background: #f9fafb; }
        pre {
            background: #1f2937;
            color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 12px;
            line-height: 1.6;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .step-number {
            width: 30px;
            height: 30px;
            background: #39A900;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #39A900 0%, #2d8000 100%);
            transition: width 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Diagnóstico y Solución Completa</h1>
            <p>Verificación y reparación del sistema</p>
        </div>
        
        <div class="content">
            <?php
            $errores = [];
            $advertencias = [];
            $exitos = [];
            $pasos_completados = 0;
            $total_pasos = 6;
            
            // PASO 1: Verificar conexión a base de datos
            echo "<div class='section'>";
            echo "<h2>Paso 1: Verificando Conexión a Base de Datos</h2>";
            
            try {
                $conn = new PDO(
                    "mysql:host=localhost;dbname=progsena;charset=utf8mb4",
                    "root",
                    "",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                
                echo "<div class='message success'>✓ Conexión a base de datos exitosa</div>";
                $exitos[] = "Conexión a base de datos";
                $pasos_completados++;
                
                // Verificar charset
                $stmt = $conn->query("SELECT @@character_set_database, @@collation_database");
                $charset = $stmt->fetch();
                echo "<div class='message info'>";
                echo "Charset: {$charset['@@character_set_database']}<br>";
                echo "Collation: {$charset['@@collation_database']}";
                echo "</div>";
                
            } catch (PDOException $e) {
                echo "<div class='message error'>✗ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</div>";
                $errores[] = "Conexión a base de datos: " . $e->getMessage();
            }
            echo "</div>";
            
            // PASO 2: Verificar tablas existentes
            if (isset($conn)) {
                echo "<div class='section'>";
                echo "<h2>Paso 2: Verificando Tablas</h2>";
                
                $tablas_requeridas = [
                    'CENTRO_FORMACION',
                    'SEDE',
                    'coordinacion',
                    'AMBIENTE',
                    'INSTRUCTOR',
                    'TITULO_PROGRAMA',
                    'PROGRAMA',
                    'COMPETENCIA',
                    'FICHA',
                    'ASIGNACION'
                ];
                
                $stmt = $conn->query("SHOW TABLES");
                $tablas_existentes = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                echo "<table>";
                echo "<tr><th>Tabla</th><th>Estado</th><th>Registros</th></tr>";
                
                $tablas_faltantes = [];
                foreach ($tablas_requeridas as $tabla) {
                    $existe = in_array($tabla, $tablas_existentes);
                    $count = 0;
                    
                    if ($existe) {
                        try {
                            $stmt = $conn->query("SELECT COUNT(*) as total FROM `$tabla`");
                            $count = $stmt->fetch()['total'];
                        } catch (Exception $e) {
                            $count = "Error";
                        }
                    } else {
                        $tablas_faltantes[] = $tabla;
                    }
                    
                    echo "<tr>";
                    echo "<td>$tabla</td>";
                    echo "<td>" . ($existe ? "<span style='color: #065f46;'>✓ Existe</span>" : "<span style='color: #991b1b;'>✗ Falta</span>") . "</td>";
                    echo "<td>$count</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                if (empty($tablas_faltantes)) {
                    echo "<div class='message success'>✓ Todas las tablas requeridas existen</div>";
                    $pasos_completados++;
                } else {
                    echo "<div class='message warning'>⚠ Faltan " . count($tablas_faltantes) . " tablas: " . implode(', ', $tablas_faltantes) . "</div>";
                    $advertencias[] = "Tablas faltantes: " . implode(', ', $tablas_faltantes);
                }
                
                echo "</div>";
                
                // PASO 3: Crear tabla coordinacion si no existe
                if (in_array('coordinacion', $tablas_faltantes)) {
                    echo "<div class='section'>";
                    echo "<h2>Paso 3: Creando Tabla 'coordinacion'</h2>";
                    
                    try {
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
                        
                        $conn->exec($sql);
                        echo "<div class='message success'>✓ Tabla 'coordinacion' creada exitosamente</div>";
                        
                        // Insertar datos de prueba
                        $stmt = $conn->query("SELECT cent_id FROM CENTRO_FORMACION LIMIT 1");
                        $centro = $stmt->fetch();
                        
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
                        }
                        
                        $exitos[] = "Tabla coordinacion creada";
                        $pasos_completados++;
                        
                    } catch (PDOException $e) {
                        echo "<div class='message error'>✗ Error al crear tabla: " . htmlspecialchars($e->getMessage()) . "</div>";
                        $errores[] = "Crear tabla coordinacion: " . $e->getMessage();
                    }
                    
                    echo "</div>";
                } else {
                    $pasos_completados++;
                }
                
                // PASO 4: Verificar archivos del sistema
                echo "<div class='section'>";
                echo "<h2>Paso 4: Verificando Archivos del Sistema</h2>";
                
                $archivos_criticos = [
                    'routing.php' => 'Sistema de routing',
                    'conexion.php' => 'Archivo de conexión',
                    'index.php' => 'Archivo principal',
                    'controller/BaseController.php' => 'Controlador base',
                    'controller/AsignacionController.php' => 'Controlador de asignaciones',
                    'views/layout/header.php' => 'Header del sistema',
                    'views/layout/sidebar.php' => 'Sidebar del sistema',
                    'helpers/functions.php' => 'Funciones helper'
                ];
                
                echo "<table>";
                echo "<tr><th>Archivo</th><th>Descripción</th><th>Estado</th></tr>";
                
                $archivos_faltantes = [];
                foreach ($archivos_criticos as $archivo => $descripcion) {
                    $ruta = __DIR__ . '/../' . $archivo;
                    $existe = file_exists($ruta);
                    
                    if (!$existe) {
                        $archivos_faltantes[] = $archivo;
                    }
                    
                    echo "<tr>";
                    echo "<td>$archivo</td>";
                    echo "<td>$descripcion</td>";
                    echo "<td>" . ($existe ? "<span style='color: #065f46;'>✓ Existe</span>" : "<span style='color: #991b1b;'>✗ Falta</span>") . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                if (empty($archivos_faltantes)) {
                    echo "<div class='message success'>✓ Todos los archivos críticos existen</div>";
                    $pasos_completados++;
                } else {
                    echo "<div class='message error'>✗ Faltan archivos: " . implode(', ', $archivos_faltantes) . "</div>";
                    $errores[] = "Archivos faltantes: " . implode(', ', $archivos_faltantes);
                }
                
                echo "</div>";
                
                // PASO 5: Verificar permisos
                echo "<div class='section'>";
                echo "<h2>Paso 5: Verificando Permisos</h2>";
                
                $directorios_escritura = [
                    'logs' => 'Directorio de logs',
                    'assets/images' => 'Directorio de imágenes'
                ];
                
                echo "<table>";
                echo "<tr><th>Directorio</th><th>Descripción</th><th>Escritura</th></tr>";
                
                $permisos_ok = true;
                foreach ($directorios_escritura as $dir => $descripcion) {
                    $ruta = __DIR__ . '/../' . $dir;
                    $escribible = is_writable($ruta);
                    
                    if (!$escribible) {
                        $permisos_ok = false;
                    }
                    
                    echo "<tr>";
                    echo "<td>$dir</td>";
                    echo "<td>$descripcion</td>";
                    echo "<td>" . ($escribible ? "<span style='color: #065f46;'>✓ Escribible</span>" : "<span style='color: #991b1b;'>✗ No escribible</span>") . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                if ($permisos_ok) {
                    echo "<div class='message success'>✓ Todos los permisos están correctos</div>";
                    $pasos_completados++;
                } else {
                    echo "<div class='message warning'>⚠ Algunos directorios no tienen permisos de escritura</div>";
                    $advertencias[] = "Permisos de escritura insuficientes";
                }
                
                echo "</div>";
                
                // PASO 6: Test de routing
                echo "<div class='section'>";
                echo "<h2>Paso 6: Verificando Sistema de Routing</h2>";
                
                $rutas_test = [
                    '/dashboard_sena/' => 'Dashboard principal',
                    '/dashboard_sena/asignacion' => 'Listar asignaciones',
                    '/dashboard_sena/ficha' => 'Listar fichas',
                    '/dashboard_sena/instructor' => 'Listar instructores'
                ];
                
                echo "<table>";
                echo "<tr><th>Ruta</th><th>Descripción</th><th>Acción</th></tr>";
                
                foreach ($rutas_test as $ruta => $descripcion) {
                    echo "<tr>";
                    echo "<td><code>$ruta</code></td>";
                    echo "<td>$descripcion</td>";
                    echo "<td><a href='$ruta' target='_blank' class='btn' style='padding: 6px 12px; font-size: 12px;'>Probar</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                echo "<div class='message info'>ℹ️ Haz clic en 'Probar' para verificar cada ruta</div>";
                $pasos_completados++;
                
                echo "</div>";
            }
            
            // Resumen final
            echo "<div class='section' style='border-left-color: " . (empty($errores) ? "#065f46" : "#991b1b") . ";'>";
            echo "<h2>📊 Resumen del Diagnóstico</h2>";
            
            $progreso = ($pasos_completados / $total_pasos) * 100;
            echo "<div class='progress-bar'>";
            echo "<div class='progress-fill' style='width: {$progreso}%;'></div>";
            echo "</div>";
            echo "<p style='text-align: center; margin: 10px 0;'><strong>Progreso: {$pasos_completados}/{$total_pasos} pasos completados ({$progreso}%)</strong></p>";
            
            if (!empty($exitos)) {
                echo "<div class='message success'>";
                echo "<strong>✓ Éxitos (" . count($exitos) . "):</strong><br>";
                echo "<ul style='margin: 10px 0 0 20px;'>";
                foreach ($exitos as $exito) {
                    echo "<li>$exito</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
            
            if (!empty($advertencias)) {
                echo "<div class='message warning'>";
                echo "<strong>⚠ Advertencias (" . count($advertencias) . "):</strong><br>";
                echo "<ul style='margin: 10px 0 0 20px;'>";
                foreach ($advertencias as $advertencia) {
                    echo "<li>$advertencia</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
            
            if (!empty($errores)) {
                echo "<div class='message error'>";
                echo "<strong>✗ Errores (" . count($errores) . "):</strong><br>";
                echo "<ul style='margin: 10px 0 0 20px;'>";
                foreach ($errores as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
            
            if (empty($errores) && empty($advertencias)) {
                echo "<div class='message success' style='font-size: 18px; text-align: center; padding: 20px;'>";
                echo "🎉 <strong>¡Sistema completamente funcional!</strong><br>";
                echo "Todos los componentes están operativos.";
                echo "</div>";
            }
            
            echo "</div>";
            
            // Botones de acción
            echo "<div style='text-align: center; margin-top: 30px;'>";
            echo "<a href='/Gestion-sena/dashboard_sena/' class='btn'>Ir al Dashboard</a>";
            echo "<a href='" . $_SERVER['PHP_SELF'] . "' class='btn btn-secondary'>Ejecutar Diagnóstico Nuevamente</a>";
            echo "</div>";
            ?>
        </div>
    </div>
</body>
</html>
