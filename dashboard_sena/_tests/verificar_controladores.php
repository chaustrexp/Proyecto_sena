<?php
/**
 * Script de Verificación de Controladores
 * Verifica que todos los módulos tengan controlador y estén en el routing
 */

echo "<h1>Verificación de Controladores y Routing</h1>";
echo "<hr>";

// Módulos que deberían tener controlador
$modulos = [
    'dashboard' => 'DashboardController',
    'asignacion' => 'AsignacionController',
    'ficha' => 'FichaController',
    'instructor' => 'InstructorController',
    'ambiente' => 'AmbienteController',
    'programa' => 'ProgramaController',
    'competencia' => 'CompetenciaController',
    'competencia_programa' => 'CompetenciaProgramaController',
    'titulo_programa' => 'TituloProgramaController',
    'instru_competencia' => 'InstruCompetenciaController',
    'detalle_asignacion' => 'DetalleAsignacionController',
    'centro_formacion' => 'CentroFormacionController',
    'sede' => 'SedeController',
    'coordinacion' => 'CoordinacionController'
];

echo "<h2>1. Verificación de Archivos de Controladores</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>Módulo</th><th>Controlador</th><th>Estado</th></tr>";

foreach ($modulos as $modulo => $controlador) {
    $archivo = __DIR__ . "/../controller/{$controlador}.php";
    $existe = file_exists($archivo);
    $estado = $existe ? "✅ Existe" : "❌ NO EXISTE";
    $color = $existe ? "#d4edda" : "#f8d7da";
    
    echo "<tr style='background: {$color};'>";
    echo "<td><strong>{$modulo}</strong></td>";
    echo "<td>{$controlador}.php</td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<hr>";

// Verificar routing.php
echo "<h2>2. Verificación de Configuración en Routing</h2>";

$routingFile = __DIR__ . '/../routing.php';
$routingContent = file_get_contents($routingFile);

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>Módulo</th><th>En Routing</th><th>Action Map</th></tr>";

foreach ($modulos as $modulo => $controlador) {
    $enRouting = strpos($routingContent, "'{$modulo}'") !== false;
    $tieneActionMap = strpos($routingContent, "'action_map'") !== false && 
                      preg_match("/'$modulo'.*?'action_map'/s", $routingContent);
    
    $estadoRouting = $enRouting ? "✅ Sí" : "❌ No";
    $estadoMap = $tieneActionMap ? "✅ Sí" : "⚠️ No";
    $color = $enRouting ? "#d4edda" : "#f8d7da";
    
    echo "<tr style='background: {$color};'>";
    echo "<td><strong>{$modulo}</strong></td>";
    echo "<td>{$estadoRouting}</td>";
    echo "<td>{$estadoMap}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<hr>";

// Verificar sidebar
echo "<h2>3. Verificación de Enlaces en Sidebar</h2>";

$sidebarFile = __DIR__ . '/../views/layout/sidebar.php';
$sidebarContent = file_get_contents($sidebarFile);

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>Módulo</th><th>Tipo de Enlace</th><th>Estado</th></tr>";

foreach ($modulos as $modulo => $controlador) {
    // Buscar enlace con routing
    $tieneRoutingLink = strpos($sidebarContent, "/dashboard_sena/{$modulo}\"") !== false;
    
    // Buscar enlace directo a vista
    $tieneVistaDirecta = strpos($sidebarContent, "/views/{$modulo}/") !== false;
    
    if ($tieneRoutingLink) {
        $tipo = "✅ Routing";
        $estado = "Correcto";
        $color = "#d4edda";
    } elseif ($tieneVistaDirecta) {
        $tipo = "❌ Vista Directa";
        $estado = "Necesita corrección";
        $color = "#f8d7da";
    } else {
        $tipo = "⚠️ No encontrado";
        $estado = "No está en sidebar";
        $color = "#fff3cd";
    }
    
    echo "<tr style='background: {$color};'>";
    echo "<td><strong>{$modulo}</strong></td>";
    echo "<td>{$tipo}</td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<hr>";

// Resumen
echo "<h2>4. Resumen y Acciones Necesarias</h2>";

$controladoresFaltantes = [];
$routingFaltante = [];
$sidebarIncorrecto = [];

foreach ($modulos as $modulo => $controlador) {
    $archivo = __DIR__ . "/../controller/{$controlador}.php";
    if (!file_exists($archivo)) {
        $controladoresFaltantes[] = $modulo;
    }
    
    if (strpos($routingContent, "'{$modulo}'") === false) {
        $routingFaltante[] = $modulo;
    }
    
    if (strpos($sidebarContent, "/views/{$modulo}/") !== false) {
        $sidebarIncorrecto[] = $modulo;
    }
}

if (empty($controladoresFaltantes) && empty($routingFaltante) && empty($sidebarIncorrecto)) {
    echo "<div style='padding: 20px; background: #d4edda; border: 2px solid #28a745; border-radius: 8px;'>";
    echo "<h3 style='color: #155724; margin: 0;'>✅ ¡Todo está correcto!</h3>";
    echo "<p style='margin: 10px 0 0;'>Todos los módulos tienen controlador, están en el routing y usan enlaces correctos.</p>";
    echo "</div>";
} else {
    echo "<div style='padding: 20px; background: #f8d7da; border: 2px solid #dc3545; border-radius: 8px;'>";
    echo "<h3 style='color: #721c24; margin: 0;'>⚠️ Se encontraron problemas</h3>";
    
    if (!empty($controladoresFaltantes)) {
        echo "<p><strong>Controladores faltantes:</strong> " . implode(', ', $controladoresFaltantes) . "</p>";
    }
    
    if (!empty($routingFaltante)) {
        echo "<p><strong>No están en routing:</strong> " . implode(', ', $routingFaltante) . "</p>";
    }
    
    if (!empty($sidebarIncorrecto)) {
        echo "<p><strong>Sidebar con enlaces directos:</strong> " . implode(', ', $sidebarIncorrecto) . "</p>";
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<h3>URLs de Prueba</h3>";
echo "<ul>";
foreach ($modulos as $modulo => $controlador) {
    $archivo = __DIR__ . "/../controller/{$controlador}.php";
    if (file_exists($archivo)) {
        echo "<li><a href='/Gestion-sena/dashboard_sena/{$modulo}' target='_blank'>{$modulo}</a> - ";
        echo "<a href='/Gestion-sena/dashboard_sena/{$modulo}/crear' target='_blank'>crear</a></li>";
    }
}
echo "</ul>";
?>
