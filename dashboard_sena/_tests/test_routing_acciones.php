<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: Routing de Acciones</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1400px;
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
        .module-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #39A900;
        }
        .module-section h2 {
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 24px;
        }
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
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
            font-size: 14px;
        }
        .test-link-url {
            font-size: 11px;
            color: #6b7280;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }
        .status.spanish { background: #fef3c7; color: #92400e; }
        .status.english { background: #dbeafe; color: #1e40af; }
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
            font-size: 13px;
        }
        th {
            background: #39A900;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        tr:hover { background: #f9fafb; }
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test de Routing - Mapeo de Acciones</h1>
            <p>Verificación de controladores y métodos disponibles</p>
        </div>
        
        <div class="content">
            <!-- Resumen de Controladores -->
            <div class="module-section">
                <h2>📊 Resumen de Controladores</h2>
                <table>
                    <tr>
                        <th>Controlador</th>
                        <th>Métodos</th>
                        <th>Idioma</th>
                        <th>Mapeo Necesario</th>
                    </tr>
                    <tr>
                        <td>AsignacionController</td>
                        <td>index, crear, store, ver, editar, update, eliminar</td>
                        <td><span class="status spanish">Español</span></td>
                        <td>✅ Sí</td>
                    </tr>
                    <tr>
                        <td>FichaController</td>
                        <td>index, create, store, show, edit, update, delete</td>
                        <td><span class="status english">Inglés</span></td>
                        <td>❌ No</td>
                    </tr>
                    <tr>
                        <td>InstructorController</td>
                        <td>index, crear, store, ver, editar, update, eliminar</td>
                        <td><span class="status spanish">Español</span></td>
                        <td>✅ Sí</td>
                    </tr>
                    <tr>
                        <td>AmbienteController</td>
                        <td>index, crear, store, ver, editar, update, eliminar</td>
                        <td><span class="status spanish">Español</span></td>
                        <td>✅ Sí</td>
                    </tr>
                    <tr>
                        <td>ProgramaController</td>
                        <td>index, create, store, show, edit, update, delete</td>
                        <td><span class="status english">Inglés</span></td>
                        <td>❌ No</td>
                    </tr>
                    <tr>
                        <td>CompetenciaController</td>
                        <td>index, crear, store, ver, editar, update, eliminar</td>
                        <td><span class="status spanish">Español</span></td>
                        <td>✅ Sí</td>
                    </tr>
                </table>
            </div>

            <div class="info-box">
                <strong>ℹ️ Mapeo de Acciones:</strong> El routing traduce automáticamente las URLs en inglés (create, show, edit, delete) a los métodos en español (crear, ver, editar, eliminar) cuando es necesario.
            </div>

            <!-- Asignaciones -->
            <div class="module-section">
                <h2>📅 Asignaciones <span class="status spanish">Métodos en Español</span></h2>
                <div class="test-grid">
                    <a href="/Gestion-sena/dashboard_sena/asignacion" class="test-link" target="_blank">
                        <div class="test-link-title">📋 Listar</div>
                        <div class="test-link-url">/asignacion → index()</div>
                    </a>
                    <a href="/Gestion-sena/dashboard_sena/asignacion/create" class="test-link" target="_blank">
                        <div class="test-link-title">➕ Crear</div>
                        <div class="test-link-url">/asignacion/create → crear()</div>
                    </a>
                </div>
            </div>

            <!-- Fichas -->
            <div class="module-section">
                <h2>📁 Fichas <span class="status english">Métodos en Inglés</span></h2>
                <div class="test-grid">
                    <a href="/Gestion-sena/dashboard_sena/ficha" class="test-link" target="_blank">
                        <div class="test-link-title">📋 Listar</div>
                        <div class="test-link-url">/ficha → index()</div>
                    </a>
                    <a href="/Gestion-sena/dashboard_sena/ficha/create" class="test-link" target="_blank">
                        <div class="test-link-title">➕ Crear</div>
                        <div class="test-link-url">/ficha/create → create()</div>
                    </a>
                </div>
            </div>

            <!-- Instructores -->
            <div class="module-section">
                <h2>👨‍🏫 Instructores <span class="status spanish">Métodos en Español</span></h2>
                <div class="test-grid">
                    <a href="/Gestion-sena/dashboard_sena/instructor" class="test-link" target="_blank">
                        <div class="test-link-title">📋 Listar</div>
                        <div class="test-link-url">/instructor → index()</div>
                    </a>
                    <a href="/Gestion-sena/dashboard_sena/instructor/create" class="test-link" target="_blank">
                        <div class="test-link-title">➕ Crear</div>
                        <div class="test-link-url">/instructor/create → crear()</div>
                    </a>
                </div>
            </div>

            <!-- Ambientes -->
            <div class="module-section">
                <h2>🏢 Ambientes <span class="status spanish">Métodos en Español</span></h2>
                <div class="test-grid">
                    <a href="/Gestion-sena/dashboard_sena/ambiente" class="test-link" target="_blank">
                        <div class="test-link-title">📋 Listar</div>
                        <div class="test-link-url">/ambiente → index()</div>
                    </a>
                    <a href="/Gestion-sena/dashboard_sena/ambiente/create" class="test-link" target="_blank">
                        <div class="test-link-title">➕ Crear</div>
                        <div class="test-link-url">/ambiente/create → crear()</div>
                    </a>
                </div>
            </div>

            <!-- Programas -->
            <div class="module-section">
                <h2>📚 Programas <span class="status english">Métodos en Inglés</span></h2>
                <div class="test-grid">
                    <a href="/Gestion-sena/dashboard_sena/programa" class="test-link" target="_blank">
                        <div class="test-link-title">📋 Listar</div>
                        <div class="test-link-url">/programa → index()</div>
                    </a>
                    <a href="/Gestion-sena/dashboard_sena/programa/create" class="test-link" target="_blank">
                        <div class="test-link-title">➕ Crear</div>
                        <div class="test-link-url">/programa/create → create()</div>
                    </a>
                </div>
            </div>

            <!-- Competencias -->
            <div class="module-section">
                <h2>🎯 Competencias <span class="status spanish">Métodos en Español</span></h2>
                <div class="test-grid">
                    <a href="/Gestion-sena/dashboard_sena/competencia" class="test-link" target="_blank">
                        <div class="test-link-title">📋 Listar</div>
                        <div class="test-link-url">/competencia → index()</div>
                    </a>
                    <a href="/Gestion-sena/dashboard_sena/competencia/create" class="test-link" target="_blank">
                        <div class="test-link-title">➕ Crear</div>
                        <div class="test-link-url">/competencia/create → crear()</div>
                    </a>
                </div>
            </div>

            <!-- Cómo Funciona -->
            <div class="module-section">
                <h2>⚙️ Cómo Funciona el Mapeo</h2>
                <p style="margin-bottom: 15px;">El sistema de routing traduce automáticamente las acciones en inglés a español cuando es necesario:</p>
                <table>
                    <tr>
                        <th>URL (Inglés)</th>
                        <th>Método Real</th>
                        <th>Controladores Afectados</th>
                    </tr>
                    <tr>
                        <td>/modulo/create</td>
                        <td>crear()</td>
                        <td>Asignacion, Instructor, Ambiente, Competencia</td>
                    </tr>
                    <tr>
                        <td>/modulo/show/ID</td>
                        <td>ver(ID)</td>
                        <td>Asignacion, Instructor, Ambiente, Competencia</td>
                    </tr>
                    <tr>
                        <td>/modulo/edit/ID</td>
                        <td>editar(ID)</td>
                        <td>Asignacion, Instructor, Ambiente, Competencia</td>
                    </tr>
                    <tr>
                        <td>/modulo/delete/ID</td>
                        <td>eliminar(ID)</td>
                        <td>Asignacion, Instructor, Ambiente, Competencia</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
