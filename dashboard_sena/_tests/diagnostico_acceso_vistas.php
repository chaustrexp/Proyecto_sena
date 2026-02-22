<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico: Acceso a Vistas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
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
            font-size: 24px;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-left: 10px;
        }
        .status.ok {
            background: #d1fae5;
            color: #065f46;
        }
        .status.error {
            background: #fee2e2;
            color: #991b1b;
        }
        .status.warning {
            background: #fef3c7;
            color: #92400e;
        }
        .url-box {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .url-box.correct {
            border-color: #39A900;
            background: #f0fdf4;
        }
        .url-box.incorrect {
            border-color: #ef4444;
            background: #fef2f2;
        }
        .url-label {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .url-label.correct {
            color: #39A900;
        }
        .url-label.incorrect {
            color: #ef4444;
        }
        .url-text {
            font-size: 14px;
            word-break: break-all;
        }
        .test-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .test-link {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-decoration: none;
            color: #1f2937;
            transition: all 0.3s;
            display: block;
        }
        .test-link:hover {
            border-color: #39A900;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(57, 169, 0, 0.2);
        }
        .test-link-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #39A900;
        }
        .test-link-url {
            font-size: 12px;
            color: #6b7280;
            font-family: 'Courier New', monospace;
        }
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .info-box strong {
            color: #1e40af;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #39A900;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        tr:hover {
            background: #f9fafb;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Diagnóstico de Acceso a Vistas</h1>
            <p>Sistema de Gestión SENA - Verificación de Rutas</p>
        </div>
        
        <div class="content">
            <!-- Problema Detectado -->
            <div class="section">
                <h2><span class="icon">⚠️</span>Problema Detectado</h2>
                <p style="margin-bottom: 15px;">Estás accediendo directamente a las vistas PHP en lugar de usar el sistema de routing.</p>
                
                <div class="url-box incorrect">
                    <div class="url-label incorrect">❌ ACCESO INCORRECTO (Causa Errores)</div>
                    <div class="url-text">http://localhost/Gestion-sena/dashboard_sena/views/asignacion/crear.php</div>
                </div>
                
                <div class="url-box correct">
                    <div class="url-label correct">✅ ACCESO CORRECTO (A través del Routing)</div>
                    <div class="url-text">http://localhost/Gestion-sena/dashboard_sena/asignacion/create</div>
                </div>
            </div>

            <!-- Estado del Sistema -->
            <div class="section">
                <h2><span class="icon">📊</span>Estado del Sistema</h2>
                <?php
                $checks = [
                    'Routing' => file_exists(__DIR__ . '/../routing.php'),
                    'Helper Functions' => file_exists(__DIR__ . '/../helpers/functions.php'),
                    'AsignacionController' => file_exists(__DIR__ . '/../controller/AsignacionController.php'),
                    'Vista crear.php' => file_exists(__DIR__ . '/../views/asignacion/crear.php'),
                    'Conexión DB' => file_exists(__DIR__ . '/../conexion.php')
                ];
                
                echo '<table>';
                echo '<tr><th>Componente</th><th>Estado</th></tr>';
                foreach ($checks as $component => $exists) {
                    $status = $exists ? '<span class="status ok">✓ OK</span>' : '<span class="status error">✗ ERROR</span>';
                    echo "<tr><td>$component</td><td>$status</td></tr>";
                }
                echo '</table>';
                ?>
            </div>

            <!-- Rutas Correctas -->
            <div class="section">
                <h2><span class="icon">🔗</span>Rutas Correctas del Sistema</h2>
                <p style="margin-bottom: 15px;">Usa estas URLs para acceder a las diferentes secciones:</p>
                
                <div class="test-links">
                    <a href="/Gestion-sena/dashboard_sena/" class="test-link">
                        <div class="test-link-title">🏠 Dashboard Principal</div>
                        <div class="test-link-url">/dashboard_sena/</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/asignacion" class="test-link">
                        <div class="test-link-title">📋 Listar Asignaciones</div>
                        <div class="test-link-url">/dashboard_sena/asignacion</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/asignacion/create" class="test-link">
                        <div class="test-link-title">➕ Crear Asignación</div>
                        <div class="test-link-url">/dashboard_sena/asignacion/create</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/ficha" class="test-link">
                        <div class="test-link-title">📁 Listar Fichas</div>
                        <div class="test-link-url">/dashboard_sena/ficha</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/ficha/create" class="test-link">
                        <div class="test-link-title">➕ Crear Ficha</div>
                        <div class="test-link-url">/dashboard_sena/ficha/create</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/instructor" class="test-link">
                        <div class="test-link-title">👨‍🏫 Listar Instructores</div>
                        <div class="test-link-url">/dashboard_sena/instructor</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/ambiente" class="test-link">
                        <div class="test-link-title">🏢 Listar Ambientes</div>
                        <div class="test-link-url">/dashboard_sena/ambiente</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/programa" class="test-link">
                        <div class="test-link-title">📚 Listar Programas</div>
                        <div class="test-link-url">/dashboard_sena/programa</div>
                    </a>
                    
                    <a href="/Gestion-sena/dashboard_sena/competencia" class="test-link">
                        <div class="test-link-title">🎯 Listar Competencias</div>
                        <div class="test-link-url">/dashboard_sena/competencia</div>
                    </a>
                </div>
            </div>

            <!-- Tabla de Coordinación -->
            <div class="section">
                <h2><span class="icon">⚠️</span>Problema: Tabla coordinacion</h2>
                <p style="margin-bottom: 15px;">El sistema intenta acceder a una tabla <code>coordinacion</code> que no existe en la base de datos.</p>
                
                <div class="info-box">
                    <strong>Solución:</strong> Ejecuta este SQL en tu base de datos para crear la tabla:
                </div>
                
                <div class="url-box">
                    <pre style="margin: 0; white-space: pre-wrap;">CREATE TABLE IF NOT EXISTS coordinacion (
    coord_id INT PRIMARY KEY AUTO_INCREMENT,
    coord_nombre VARCHAR(100) NOT NULL,
    coord_descripcion TEXT,
    centro_formacion_id INT,
    FOREIGN KEY (centro_formacion_id) 
        REFERENCES centro_formacion(centro_id)
);</pre>
                </div>
            </div>

            <!-- Recomendaciones -->
            <div class="section">
                <h2><span class="icon">💡</span>Recomendaciones</h2>
                <ol style="line-height: 2; padding-left: 20px;">
                    <li><strong>Nunca accedas directamente a archivos en /views/</strong></li>
                    <li><strong>Usa siempre el sistema de routing</strong></li>
                    <li><strong>Navega desde el menú lateral del dashboard</strong></li>
                    <li><strong>Si necesitas un enlace directo, usa: /dashboard_sena/modulo/accion</strong></li>
                    <li><strong>Revisa routing.php para ver todas las rutas disponibles</strong></li>
                </ol>
            </div>

            <!-- Documentación -->
            <div class="section">
                <h2><span class="icon">📖</span>Documentación Completa</h2>
                <p>Para más detalles, consulta:</p>
                <div class="url-box">
                    <div class="url-text">dashboard_sena/_docs/SOLUCION_ACCESO_DIRECTO_VISTAS.md</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
