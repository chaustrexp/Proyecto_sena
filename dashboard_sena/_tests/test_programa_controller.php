<?php
/**
 * Test del Controlador de Programas
 * Verifica que el módulo de programas esté funcionando correctamente
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../model/ProgramaModel.php';
require_once __DIR__ . '/../model/TituloProgramaModel.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Controlador de Programas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #39A900 0%, #007832 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; }
        .header h1 { font-size: 2em; margin-bottom: 10px; }
        .section { background: white; padding: 25px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section h2 { color: #007832; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #39A900; }
        .test-item { padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #ddd; }
        .test-item.success { background: #d4edda; border-color: #28a745; }
        .test-item.error { background: #f8d7da; border-color: #dc3545; }
        .test-item.warning { background: #fff3cd; border-color: #ffc107; }
        .test-item.info { background: #d1ecf1; border-color: #17a2b8; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 12px; font-size: 0.85em; font-weight: 600; margin-right: 10px; }
        .badge.success { background: #28a745; color: white; }
        .badge.error { background: #dc3545; color: white; }
        .badge.warning { background: #ffc107; color: #000; }
        .badge.info { background: #17a2b8; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; }
        table td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .code { background: #f8f9fa; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; overflow-x: auto; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #39A900; color: white; text-decoration: none; border-radius: 8px; margin: 5px; }
        .btn:hover { background: #007832; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test del Controlador de Programas</h1>
            <p>Verificación completa del módulo de gestión de programas</p>
            <p style="font-size: 0.9em; margin-top: 10px;">Fecha: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>

        <?php
        $errores = 0;
        $advertencias = 0;
        $exitos = 0;
        
        // TEST 1: Verificar archivos del controlador
        echo '<div class="section">';
        echo '<h2>📁 Test 1: Archivos del Módulo</h2>';
        
        $archivos = [
            'Controlador' => __DIR__ . '/../controller/ProgramaController.php',
            'Modelo' => __DIR__ . '/../model/ProgramaModel.php',
            'Modelo Título' => __DIR__ . '/../model/TituloProgramaModel.php',
            'Vista Index' => __DIR__ . '/../views/programa/index.php',
            'Vista Crear' => __DIR__ . '/../views/programa/crear.php',
            'Vista Editar' => __DIR__ . '/../views/programa/editar.php',
            'Vista Ver' => __DIR__ . '/../views/programa/ver.php'
        ];
        
        foreach ($archivos as $nombre => $ruta) {
            if (file_exists($ruta)) {
                echo "<div class='test-item success'><span class='badge success'>✓</span> <strong>{$nombre}:</strong> Existe</div>";
                $exitos++;
            } else {
                echo "<div class='test-item error'><span class='badge error'>✗</span> <strong>{$nombre}:</strong> No encontrado</div>";
                $errores++;
            }
        }
        echo '</div>';
        
        // TEST 2: Verificar conexión a base de datos
        echo '<div class="section">';
        echo '<h2>🗄️ Test 2: Conexión a Base de Datos</h2>';
        
        try {
            $db = Database::getInstance()->getConnection();
            echo "<div class='test-item success'><span class='badge success'>✓</span> Conexión a base de datos establecida</div>";
            $exitos++;
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='badge error'>✗</span> Error de conexión: " . $e->getMessage() . "</div>";
            $errores++;
        }
        echo '</div>';
        
        // TEST 3: Verificar tabla PROGRAMA
        echo '<div class="section">';
        echo '<h2>📊 Test 3: Tabla PROGRAMA</h2>';
        
        try {
            $stmt = $db->query("DESCRIBE PROGRAMA");
            $columnas = $stmt->fetchAll();
            
            echo "<div class='test-item success'><span class='badge success'>✓</span> Tabla PROGRAMA existe</div>";
            $exitos++;
            
            echo "<table>";
            echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
            foreach ($columnas as $col) {
                echo "<tr>";
                echo "<td>{$col['Field']}</td>";
                echo "<td>{$col['Type']}</td>";
                echo "<td>{$col['Null']}</td>";
                echo "<td>{$col['Key']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='badge error'>✗</span> Error al verificar tabla: " . $e->getMessage() . "</div>";
            $errores++;
        }
        echo '</div>';
        
        // TEST 4: Verificar modelo ProgramaModel
        echo '<div class="section">';
        echo '<h2>🔧 Test 4: Modelo ProgramaModel</h2>';
        
        try {
            $programaModel = new ProgramaModel();
            echo "<div class='test-item success'><span class='badge success'>✓</span> Modelo ProgramaModel instanciado correctamente</div>";
            $exitos++;
            
            // Verificar métodos
            $metodos = ['getAll', 'getById', 'create', 'update', 'delete', 'count'];
            foreach ($metodos as $metodo) {
                if (method_exists($programaModel, $metodo)) {
                    echo "<div class='test-item success'><span class='badge success'>✓</span> Método <code>{$metodo}()</code> existe</div>";
                    $exitos++;
                } else {
                    echo "<div class='test-item error'><span class='badge error'>✗</span> Método <code>{$metodo}()</code> no encontrado</div>";
                    $errores++;
                }
            }
            
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='badge error'>✗</span> Error al instanciar modelo: " . $e->getMessage() . "</div>";
            $errores++;
        }
        echo '</div>';
        
        // TEST 5: Obtener programas
        echo '<div class="section">';
        echo '<h2>📚 Test 5: Obtener Programas</h2>';
        
        try {
            $programas = $programaModel->getAll();
            $total = count($programas);
            
            echo "<div class='test-item success'><span class='badge success'>✓</span> Método getAll() ejecutado correctamente</div>";
            echo "<div class='test-item info'><span class='badge info'>ℹ</span> Total de programas: <strong>{$total}</strong></div>";
            $exitos++;
            
            if ($total > 0) {
                echo "<table>";
                echo "<tr><th>Código</th><th>Denominación</th><th>Tipo</th><th>Título</th></tr>";
                foreach (array_slice($programas, 0, 5) as $prog) {
                    echo "<tr>";
                    echo "<td>{$prog['prog_codigo']}</td>";
                    echo "<td>" . htmlspecialchars($prog['prog_denominacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($prog['prog_tipo'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($prog['titpro_nombre'] ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                if ($total > 5) {
                    echo "<tr><td colspan='4' style='text-align: center; color: #666;'>... y " . ($total - 5) . " más</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='test-item warning'><span class='badge warning'>⚠</span> No hay programas registrados en la base de datos</div>";
                $advertencias++;
            }
            
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='badge error'>✗</span> Error al obtener programas: " . $e->getMessage() . "</div>";
            $errores++;
        }
        echo '</div>';
        
        // TEST 6: Verificar tabla TITULO_PROGRAMA
        echo '<div class="section">';
        echo '<h2>🎓 Test 6: Tabla TITULO_PROGRAMA</h2>';
        
        try {
            $tituloModel = new TituloProgramaModel();
            $titulos = $tituloModel->getAll();
            $totalTitulos = count($titulos);
            
            echo "<div class='test-item success'><span class='badge success'>✓</span> Modelo TituloProgramaModel funcional</div>";
            echo "<div class='test-item info'><span class='badge info'>ℹ</span> Total de títulos: <strong>{$totalTitulos}</strong></div>";
            $exitos++;
            
            if ($totalTitulos > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Nombre del Título</th></tr>";
                foreach ($titulos as $titulo) {
                    echo "<tr>";
                    echo "<td>{$titulo['titpro_id']}</td>";
                    echo "<td>" . htmlspecialchars($titulo['titpro_nombre']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='test-item warning'><span class='badge warning'>⚠</span> No hay títulos registrados. Los formularios de creación necesitan títulos.</div>";
                $advertencias++;
            }
            
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='badge error'>✗</span> Error al verificar títulos: " . $e->getMessage() . "</div>";
            $errores++;
        }
        echo '</div>';
        
        // TEST 7: Verificar método count()
        echo '<div class="section">';
        echo '<h2>🔢 Test 7: Método count()</h2>';
        
        try {
            $totalCount = $programaModel->count();
            echo "<div class='test-item success'><span class='badge success'>✓</span> Método count() ejecutado correctamente</div>";
            echo "<div class='test-item info'><span class='badge info'>ℹ</span> Total según count(): <strong>{$totalCount}</strong></div>";
            $exitos++;
            
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='badge error'>✗</span> Error en count(): " . $e->getMessage() . "</div>";
            $errores++;
        }
        echo '</div>';
        
        // TEST 8: Verificar routing
        echo '<div class="section">';
        echo '<h2>🔗 Test 8: URLs del Módulo</h2>';
        
        $urls = [
            'Listar Programas' => '/Gestion-sena/dashboard_sena/views/programa/index.php',
            'Crear Programa' => '/Gestion-sena/dashboard_sena/views/programa/crear.php',
            'Editar Programa' => '/Gestion-sena/dashboard_sena/views/programa/editar.php',
            'Ver Programa' => '/Gestion-sena/dashboard_sena/views/programa/ver.php'
        ];
        
        echo "<div class='test-item info'><span class='badge info'>ℹ</span> URLs disponibles:</div>";
        echo "<table>";
        echo "<tr><th>Acción</th><th>Archivo</th><th>Estado</th></tr>";
        foreach ($urls as $accion => $url) {
            $archivo = __DIR__ . '/..' . str_replace('/Gestion-sena/dashboard_sena', '', $url);
            $existe = file_exists($archivo) ? '✓ Existe' : '✗ No existe';
            $clase = file_exists($archivo) ? 'success' : 'error';
            echo "<tr>";
            echo "<td>{$accion}</td>";
            echo "<td><code>{$url}</code></td>";
            echo "<td><span class='badge {$clase}'>{$existe}</span></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo '</div>';
        
        // TEST 9: Verificar funciones helper
        echo '<div class="section">';
        echo '<h2>🛠️ Test 9: Funciones Helper</h2>';
        
        $helpers = ['safe', 'safeHtml', 'e', 'registroValido'];
        foreach ($helpers as $helper) {
            if (function_exists($helper)) {
                echo "<div class='test-item success'><span class='badge success'>✓</span> Función <code>{$helper}()</code> disponible</div>";
                $exitos++;
            } else {
                echo "<div class='test-item warning'><span class='badge warning'>⚠</span> Función <code>{$helper}()</code> no encontrada</div>";
                $advertencias++;
            }
        }
        echo '</div>';
        
        // RESUMEN FINAL
        echo '<div class="section" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">';
        echo '<h2>📊 Resumen de Pruebas</h2>';
        
        $total_tests = $exitos + $errores + $advertencias;
        
        echo "<div style='display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px;'>";
        
        echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; text-align: center;'>";
        echo "<div style='font-size: 3em; color: #28a745;'>{$exitos}</div>";
        echo "<div style='color: #155724; font-weight: 600;'>Pruebas Exitosas</div>";
        echo "</div>";
        
        echo "<div style='background: #fff3cd; padding: 20px; border-radius: 8px; text-align: center;'>";
        echo "<div style='font-size: 3em; color: #ffc107;'>{$advertencias}</div>";
        echo "<div style='color: #856404; font-weight: 600;'>Advertencias</div>";
        echo "</div>";
        
        echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; text-align: center;'>";
        echo "<div style='font-size: 3em; color: #dc3545;'>{$errores}</div>";
        echo "<div style='color: #721c24; font-weight: 600;'>Errores</div>";
        echo "</div>";
        
        echo "</div>";
        
        // Conclusión
        echo "<div style='margin-top: 30px; padding: 20px; background: white; border-radius: 8px; border-left: 4px solid ";
        if ($errores == 0) {
            echo "#28a745;'>";
            echo "<h3 style='color: #28a745; margin-bottom: 10px;'>✓ Módulo de Programas Funcional</h3>";
            echo "<p>El controlador de programas está funcionando correctamente. Todos los componentes necesarios están presentes.</p>";
        } else {
            echo "#dc3545;'>";
            echo "<h3 style='color: #dc3545; margin-bottom: 10px;'>✗ Se Encontraron Errores</h3>";
            echo "<p>Hay {$errores} error(es) que deben ser corregidos para que el módulo funcione correctamente.</p>";
        }
        echo "</div>";
        
        echo '</div>';
        
        // Enlaces de acceso
        echo '<div class="section">';
        echo '<h2>🔗 Acceso Rápido</h2>';
        echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
        echo '<a href="/Gestion-sena/dashboard_sena/views/programa/index.php" class="btn">Ver Programas</a>';
        echo '<a href="/Gestion-sena/dashboard_sena/views/programa/crear.php" class="btn">Crear Programa</a>';
        echo '<a href="/Gestion-sena/dashboard_sena/" class="btn" style="background: #6c757d;">Volver al Dashboard</a>';
        echo '</div>';
        echo '</div>';
        ?>
    </div>
</body>
</html>
