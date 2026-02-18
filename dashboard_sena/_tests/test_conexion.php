<?php
/**
 * Script de Diagnóstico de Base de Datos
 * Verifica la conexión y muestra estadísticas de datos
 */

require_once 'conexion.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Base de Datos - SENA</title>
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
            max-width: 1200px;
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
        .header p {
            color: #6b7280;
            font-size: 16px;
        }
        .status-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .status-success {
            border-left: 5px solid #10b981;
        }
        .status-error {
            border-left: 5px solid #ef4444;
        }
        .status-warning {
            border-left: 5px solid #f59e0b;
        }
        .status-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-success .status-title {
            color: #10b981;
        }
        .status-error .status-title {
            color: #ef4444;
        }
        .status-warning .status-title {
            color: #f59e0b;
        }
        .status-message {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 36px;
            font-weight: 800;
            color: #39A900;
            margin-bottom: 8px;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        .info-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table th {
            background: #f3f4f6;
            padding: 15px;
            text-align: left;
            font-weight: 700;
            color: #1f2937;
            font-size: 13px;
            text-transform: uppercase;
        }
        .info-table td {
            padding: 15px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #39A900;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #2d8700;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(57, 169, 0, 0.3);
        }
        .icon-success { color: #10b981; }
        .icon-error { color: #ef4444; }
        .icon-warning { color: #f59e0b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Diagnóstico de Base de Datos</h1>
            <p>Sistema de Gestión SENA - Verificación de Conexión y Datos</p>
        </div>

        <?php
        $conexionExitosa = false;
        $db = null;
        $error = null;

        try {
            $db = Database::getInstance()->getConnection();
            $conexionExitosa = true;
            
            echo '<div class="status-card status-success">';
            echo '<div class="status-title"><span class="icon-success">✓</span> Conexión Exitosa</div>';
            echo '<div class="status-message">';
            echo 'La conexión a la base de datos <strong>' . DB_NAME . '</strong> se estableció correctamente.<br>';
            echo 'Host: <strong>' . DB_HOST . '</strong><br>';
            echo 'Usuario: <strong>' . DB_USER . '</strong>';
            echo '</div>';
            echo '</div>';
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo '<div class="status-card status-error">';
            echo '<div class="status-title"><span class="icon-error">✗</span> Error de Conexión</div>';
            echo '<div class="status-message">';
            echo 'No se pudo conectar a la base de datos.<br>';
            echo '<strong>Error:</strong> ' . htmlspecialchars($error);
            echo '</div>';
            echo '</div>';
        }

        if ($conexionExitosa && $db) {
            // Verificar tablas y contar registros
            $tablas = [
                'centro_formacion' => '🏢',
                'sede' => '🏛️',
                'coordinacion' => '👥',
                'ambiente' => '🚪',
                'titulo_programa' => '📚',
                'programa' => '📖',
                'competencia' => '🎯',
                'competencia_programa' => '🔗',
                'ficha' => '📋',
                'instructor' => '👨‍🏫',
                'asignacion' => '📅',
                'detalle_asignacion' => '📝'
            ];

            echo '<div class="stats-grid">';
            
            $totalRegistros = 0;
            $tablasConDatos = 0;
            
            foreach ($tablas as $tabla => $icono) {
                try {
                    $stmt = $db->query("SELECT COUNT(*) as total FROM $tabla");
                    $resultado = $stmt->fetch();
                    $count = $resultado['total'];
                    $totalRegistros += $count;
                    
                    if ($count > 0) {
                        $tablasConDatos++;
                    }
                    
                    echo '<div class="stat-card">';
                    echo '<div class="stat-icon">' . $icono . '</div>';
                    echo '<div class="stat-number">' . $count . '</div>';
                    echo '<div class="stat-label">' . ucwords(str_replace('_', ' ', $tabla)) . '</div>';
                    echo '</div>';
                    
                } catch (Exception $e) {
                    echo '<div class="stat-card">';
                    echo '<div class="stat-icon">⚠️</div>';
                    echo '<div class="stat-number">-</div>';
                    echo '<div class="stat-label">' . ucwords(str_replace('_', ' ', $tabla)) . '</div>';
                    echo '</div>';
                }
            }
            
            echo '</div>';

            // Resumen general
            if ($totalRegistros > 0) {
                echo '<div class="status-card status-success" style="margin-top: 30px;">';
                echo '<div class="status-title"><span class="icon-success">✓</span> Base de Datos Operativa</div>';
                echo '<div class="status-message">';
                echo 'Se encontraron <strong>' . $totalRegistros . '</strong> registros en total.<br>';
                echo '<strong>' . $tablasConDatos . '</strong> de ' . count($tablas) . ' tablas contienen datos.<br>';
                echo 'El sistema está listo para ser utilizado.';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="status-card status-warning" style="margin-top: 30px;">';
                echo '<div class="status-title"><span class="icon-warning">⚠</span> Base de Datos Vacía</div>';
                echo '<div class="status-message">';
                echo 'La base de datos existe pero no contiene registros.<br>';
                echo 'Necesitas importar datos o crear registros manualmente para comenzar a usar el sistema.';
                echo '</div>';
                echo '</div>';
            }

            // Información de la base de datos
            echo '<div class="info-table">';
            echo '<table>';
            echo '<thead><tr><th>Información del Sistema</th><th>Valor</th></tr></thead>';
            echo '<tbody>';
            
            try {
                $stmt = $db->query("SELECT VERSION() as version");
                $version = $stmt->fetch();
                echo '<tr><td>Versión de MySQL</td><td>' . $version['version'] . '</td></tr>';
            } catch (Exception $e) {
                echo '<tr><td>Versión de MySQL</td><td>No disponible</td></tr>';
            }
            
            try {
                $stmt = $db->query("SELECT DATABASE() as db_name");
                $dbName = $stmt->fetch();
                echo '<tr><td>Base de Datos Activa</td><td>' . $dbName['db_name'] . '</td></tr>';
            } catch (Exception $e) {
                echo '<tr><td>Base de Datos Activa</td><td>No disponible</td></tr>';
            }
            
            try {
                $stmt = $db->query("SHOW TABLES");
                $numTablas = $stmt->rowCount();
                echo '<tr><td>Número de Tablas</td><td>' . $numTablas . '</td></tr>';
            } catch (Exception $e) {
                echo '<tr><td>Número de Tablas</td><td>No disponible</td></tr>';
            }
            
            echo '<tr><td>Charset</td><td>utf8mb4</td></tr>';
            echo '<tr><td>Collation</td><td>utf8mb4_unicode_ci</td></tr>';
            echo '<tr><td>Estado de Conexión</td><td><span style="color: #10b981; font-weight: 700;">✓ Conectado</span></td></tr>';
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
        ?>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" class="btn">← Volver al Dashboard</a>
            <a href="auth/login.php" class="btn" style="background: #6b7280; margin-left: 10px;">Ir al Login</a>
        </div>
    </div>
</body>
</html>
