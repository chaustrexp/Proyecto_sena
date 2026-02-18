<?php
/**
 * Script de Prueba - Insertar Datos de Ejemplo
 * Verifica que se puedan guardar datos en la base de datos
 */

require_once 'conexion.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Inserción de Datos - SENA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 {
            color: #39A900;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .success {
            border-left: 5px solid #10b981;
        }
        .error {
            border-left: 5px solid #ef4444;
        }
        .warning {
            border-left: 5px solid #f59e0b;
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .message {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 15px;
        }
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
        .btn-danger {
            background: #ef4444;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .result-item {
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .result-item strong {
            color: #1f2937;
        }
        .result-item span {
            color: #6b7280;
            font-size: 13px;
        }
        .icon-success { color: #10b981; font-size: 24px; }
        .icon-error { color: #ef4444; font-size: 24px; }
        .icon-warning { color: #f59e0b; font-size: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Prueba de Inserción de Datos</h1>
            <p>Verifica que el sistema pueda guardar información en la base de datos</p>
        </div>

        <?php
        $db = null;
        $resultados = [];
        $errores = [];

        try {
            $db = Database::getInstance()->getConnection();
            
            // Si se presionó el botón de insertar
            if (isset($_POST['insertar'])) {
                
                // 1. Insertar Centro de Formación
                try {
                    $stmt = $db->prepare("INSERT INTO centro_formacion (nombre, direccion, telefono, email) VALUES (?, ?, ?, ?)");
                    $stmt->execute(['Centro de Prueba', 'Calle Test 123', '3001234567', 'test@sena.edu.co']);
                    $resultados[] = "✓ Centro de Formación insertado (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Centro de Formación: " . $e->getMessage();
                }

                // 2. Insertar Sede
                try {
                    $stmt = $db->prepare("INSERT INTO sede (nombre, direccion, centro_formacion_id) VALUES (?, ?, ?)");
                    $stmt->execute(['Sede de Prueba', 'Avenida Test 456', 1]);
                    $resultados[] = "✓ Sede insertada (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Sede: " . $e->getMessage();
                }

                // 3. Insertar Coordinación
                try {
                    $stmt = $db->prepare("INSERT INTO coordinacion (nombre, descripcion, sede_id) VALUES (?, ?, ?)");
                    $stmt->execute(['Coordinación de Prueba', 'Descripción de prueba', 1]);
                    $resultados[] = "✓ Coordinación insertada (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Coordinación: " . $e->getMessage();
                }

                // 4. Insertar Ambiente
                try {
                    $stmt = $db->prepare("INSERT INTO ambiente (nombre, capacidad, tipo, sede_id) VALUES (?, ?, ?, ?)");
                    $stmt->execute(['Ambiente 101', 30, 'Aula', 1]);
                    $resultados[] = "✓ Ambiente insertado (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Ambiente: " . $e->getMessage();
                }

                // 5. Insertar Instructor
                try {
                    $stmt = $db->prepare("INSERT INTO instructor (nombre, email, telefono, especialidad) VALUES (?, ?, ?, ?)");
                    $stmt->execute(['Instructor de Prueba', 'instructor@sena.edu.co', '3009876543', 'Programación']);
                    $resultados[] = "✓ Instructor insertado (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Instructor: " . $e->getMessage();
                }

                // 6. Insertar Título de Programa
                try {
                    $stmt = $db->prepare("INSERT INTO titulo_programa (nombre, nivel, duracion_meses) VALUES (?, ?, ?)");
                    $stmt->execute(['Tecnólogo en Pruebas', 'Tecnólogo', 24]);
                    $resultados[] = "✓ Título de Programa insertado (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Título de Programa: " . $e->getMessage();
                }

                // 7. Insertar Programa
                try {
                    $stmt = $db->prepare("INSERT INTO programa (codigo, titulo_programa_id, coordinacion_id) VALUES (?, ?, ?)");
                    $stmt->execute(['TEST001', 1, 1]);
                    $resultados[] = "✓ Programa insertado (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Programa: " . $e->getMessage();
                }

                // 8. Insertar Competencia
                try {
                    $stmt = $db->prepare("INSERT INTO competencia (codigo, nombre, descripcion) VALUES (?, ?, ?)");
                    $stmt->execute(['COMP001', 'Competencia de Prueba', 'Descripción de la competencia']);
                    $resultados[] = "✓ Competencia insertada (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Competencia: " . $e->getMessage();
                }

                // 9. Insertar Ficha
                try {
                    $stmt = $db->prepare("INSERT INTO ficha (numero, programa_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
                    $stmt->execute(['2024001', 1, '2024-01-15', '2025-12-15']);
                    $resultados[] = "✓ Ficha insertada (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Ficha: " . $e->getMessage();
                }

                // 10. Insertar Asignación
                try {
                    $stmt = $db->prepare("INSERT INTO asignacion (ficha_id, instructor_id, ambiente_id, competencia_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([1, 1, 1, 1, '2024-01-15', '2024-06-15']);
                    $resultados[] = "✓ Asignación insertada (ID: " . $db->lastInsertId() . ")";
                } catch (Exception $e) {
                    $errores[] = "Asignación: " . $e->getMessage();
                }
            }

            // Si se presionó el botón de limpiar
            if (isset($_POST['limpiar'])) {
                $tablas = ['detalle_asignacion', 'asignacion', 'ficha', 'competencia_programa', 'competencia', 'programa', 'titulo_programa', 'instructor', 'ambiente', 'coordinacion', 'sede', 'centro_formacion'];
                
                foreach ($tablas as $tabla) {
                    try {
                        $db->exec("DELETE FROM $tabla WHERE id > 0");
                        $db->exec("ALTER TABLE $tabla AUTO_INCREMENT = 1");
                        $resultados[] = "✓ Tabla '$tabla' limpiada";
                    } catch (Exception $e) {
                        $errores[] = "Error al limpiar '$tabla': " . $e->getMessage();
                    }
                }
            }

            // Mostrar resultados
            if (!empty($resultados)) {
                echo '<div class="card success">';
                echo '<div class="title"><span class="icon-success">✓</span> Operaciones Exitosas</div>';
                echo '<div class="message">';
                foreach ($resultados as $resultado) {
                    echo '<div class="result-item">' . $resultado . '</div>';
                }
                echo '</div>';
                echo '</div>';
            }

            // Mostrar errores
            if (!empty($errores)) {
                echo '<div class="card error">';
                echo '<div class="title"><span class="icon-error">✗</span> Errores Encontrados</div>';
                echo '<div class="message">';
                foreach ($errores as $error) {
                    echo '<div class="result-item" style="background: #fee2e2;">' . $error . '</div>';
                }
                echo '</div>';
                echo '</div>';
            }

            // Mostrar estado actual
            echo '<div class="card">';
            echo '<div class="title">📊 Estado Actual de la Base de Datos</div>';
            echo '<div class="message">';
            
            $tablas = ['centro_formacion', 'sede', 'coordinacion', 'ambiente', 'instructor', 'titulo_programa', 'programa', 'competencia', 'ficha', 'asignacion'];
            
            foreach ($tablas as $tabla) {
                try {
                    $stmt = $db->query("SELECT COUNT(*) as total FROM $tabla");
                    $resultado = $stmt->fetch();
                    $count = $resultado['total'];
                    
                    echo '<div class="result-item">';
                    echo '<strong>' . ucwords(str_replace('_', ' ', $tabla)) . '</strong>';
                    echo '<span>' . $count . ' registro(s)</span>';
                    echo '</div>';
                } catch (Exception $e) {
                    echo '<div class="result-item" style="background: #fee2e2;">';
                    echo '<strong>' . ucwords(str_replace('_', ' ', $tabla)) . '</strong>';
                    echo '<span>Error</span>';
                    echo '</div>';
                }
            }
            
            echo '</div>';
            echo '</div>';

        } catch (Exception $e) {
            echo '<div class="card error">';
            echo '<div class="title"><span class="icon-error">✗</span> Error de Conexión</div>';
            echo '<div class="message">' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '</div>';
        }
        ?>

        <div class="card">
            <div class="title">🎯 Acciones Disponibles</div>
            <div class="message">
                <p><strong>Insertar Datos de Prueba:</strong> Crea registros de ejemplo en todas las tablas principales.</p>
                <p><strong>Limpiar Base de Datos:</strong> Elimina todos los registros de prueba (¡Cuidado! Esta acción no se puede deshacer).</p>
            </div>
            
            <form method="POST" style="display: inline;">
                <button type="submit" name="insertar" class="btn">
                    ➕ Insertar Datos de Prueba
                </button>
            </form>
            
            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres limpiar TODOS los datos? Esta acción no se puede deshacer.');">
                <button type="submit" name="limpiar" class="btn btn-danger">
                    🗑️ Limpiar Base de Datos
                </button>
            </form>
            
            <a href="test_conexion.php" class="btn btn-secondary">
                🔍 Ver Diagnóstico
            </a>
            
            <a href="index.php" class="btn btn-secondary">
                🏠 Ir al Dashboard
            </a>
        </div>
    </div>
</body>
</html>
