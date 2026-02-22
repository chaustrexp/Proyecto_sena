<?php
/**
 * Test del Controlador CompetenciaProgramaController
 * Verifica que el controlador y modelo funcionen correctamente
 */

// Simular sesión
session_start();
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nombre'] = 'Test User';
$_SESSION['usuario_rol'] = 'Administrador';

require_once __DIR__ . '/../model/CompetenciaProgramaModel.php';
require_once __DIR__ . '/../model/ProgramaModel.php';
require_once __DIR__ . '/../model/CompetenciaModel.php';

echo "<h1>Test: CompetenciaProgramaController</h1>";
echo "<hr>";

// Test 1: Verificar que los modelos se instancian correctamente
echo "<h2>Test 1: Instanciar Modelos</h2>";
try {
    $cpModel = new CompetenciaProgramaModel();
    echo "✅ CompetenciaProgramaModel instanciado correctamente<br>";
    
    $programaModel = new ProgramaModel();
    echo "✅ ProgramaModel instanciado correctamente<br>";
    
    $competenciaModel = new CompetenciaModel();
    echo "✅ CompetenciaModel instanciado correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error al instanciar modelos: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 2: Verificar que getAll() funciona
echo "<h2>Test 2: Obtener Datos</h2>";
try {
    $relaciones = $cpModel->getAll();
    echo "✅ getAll() ejecutado correctamente<br>";
    echo "📊 Total de relaciones: " . count($relaciones) . "<br>";
    
    if (!empty($relaciones)) {
        echo "<h3>Primera relación:</h3>";
        echo "<pre>";
        print_r($relaciones[0]);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "❌ Error en getAll(): " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 3: Verificar que se obtienen programas
echo "<h2>Test 3: Obtener Programas</h2>";
try {
    $programas = $programaModel->getAll();
    echo "✅ Programas obtenidos correctamente<br>";
    echo "📊 Total de programas: " . count($programas) . "<br>";
    
    if (!empty($programas)) {
        echo "<h3>Primer programa:</h3>";
        echo "<pre>";
        print_r($programas[0]);
        echo "</pre>";
        
        // Verificar que tiene prog_codigo
        if (isset($programas[0]['prog_codigo'])) {
            echo "✅ Campo 'prog_codigo' existe<br>";
        } else {
            echo "❌ Campo 'prog_codigo' NO existe<br>";
        }
    } else {
        echo "⚠️ No hay programas en la base de datos<br>";
    }
} catch (Exception $e) {
    echo "❌ Error al obtener programas: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 4: Verificar que se obtienen competencias
echo "<h2>Test 4: Obtener Competencias</h2>";
try {
    $competencias = $competenciaModel->getAll();
    echo "✅ Competencias obtenidas correctamente<br>";
    echo "📊 Total de competencias: " . count($competencias) . "<br>";
    
    if (!empty($competencias)) {
        echo "<h3>Primera competencia:</h3>";
        echo "<pre>";
        print_r($competencias[0]);
        echo "</pre>";
        
        // Verificar que tiene comp_id
        if (isset($competencias[0]['comp_id'])) {
            echo "✅ Campo 'comp_id' existe<br>";
        } else {
            echo "❌ Campo 'comp_id' NO existe<br>";
        }
    } else {
        echo "⚠️ No hay competencias en la base de datos<br>";
    }
} catch (Exception $e) {
    echo "❌ Error al obtener competencias: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 5: Verificar estructura de tabla COMPETxPROGRAMA
echo "<h2>Test 5: Estructura de Tabla COMPETxPROGRAMA</h2>";
try {
    require_once __DIR__ . '/../conexion.php';
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->query("DESCRIBE COMPETxPROGRAMA");
    $estructura = $stmt->fetchAll();
    
    echo "✅ Tabla COMPETxPROGRAMA existe<br>";
    echo "<h3>Estructura:</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
    foreach ($estructura as $campo) {
        echo "<tr>";
        echo "<td>" . $campo['Field'] . "</td>";
        echo "<td>" . $campo['Type'] . "</td>";
        echo "<td>" . $campo['Null'] . "</td>";
        echo "<td>" . $campo['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "❌ Error al verificar tabla: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 6: Verificar routing
echo "<h2>Test 6: Verificar Configuración de Routing</h2>";
$routingFile = __DIR__ . '/../routing.php';
if (file_exists($routingFile)) {
    $content = file_get_contents($routingFile);
    if (strpos($content, "'competencia_programa'") !== false) {
        echo "✅ Módulo 'competencia_programa' está configurado en routing.php<br>";
        
        // Verificar action_map
        if (strpos($content, "'action_map'") !== false) {
            echo "✅ action_map está configurado<br>";
        } else {
            echo "⚠️ action_map no encontrado<br>";
        }
    } else {
        echo "❌ Módulo 'competencia_programa' NO está en routing.php<br>";
    }
} else {
    echo "❌ Archivo routing.php no encontrado<br>";
}

echo "<hr>";

// Test 7: Verificar controlador
echo "<h2>Test 7: Verificar Controlador</h2>";
$controllerFile = __DIR__ . '/../controller/CompetenciaProgramaController.php';
if (file_exists($controllerFile)) {
    echo "✅ CompetenciaProgramaController.php existe<br>";
    
    require_once __DIR__ . '/../controller/BaseController.php';
    require_once $controllerFile;
    
    if (class_exists('CompetenciaProgramaController')) {
        echo "✅ Clase CompetenciaProgramaController existe<br>";
        
        $methods = get_class_methods('CompetenciaProgramaController');
        echo "<h3>Métodos disponibles:</h3>";
        echo "<ul>";
        foreach ($methods as $method) {
            if (!in_array($method, ['__construct', 'render', 'redirect', 'json', 'validate', 'getFlashMessage', 'isMethod', 'post', 'get'])) {
                echo "<li>✅ $method()</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "❌ Clase CompetenciaProgramaController no existe<br>";
    }
} else {
    echo "❌ CompetenciaProgramaController.php no encontrado<br>";
}

echo "<hr>";
echo "<h2>✅ Tests Completados</h2>";
echo "<p><a href='/Gestion-sena/dashboard_sena/competencia_programa'>Ir al módulo Competencia-Programa</a></p>";
?>
