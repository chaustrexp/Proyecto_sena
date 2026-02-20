<?php
/**
 * Sistema de Routing Centralizado
 * Maneja todas las rutas y redirige a los controladores correspondientes
 */

// Proteger con autenticación
require_once __DIR__ . '/auth/check_auth.php';

// Obtener la ruta solicitada
$request = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('routing.php', '', $scriptName);

// Limpiar la ruta
$route = str_replace($basePath, '', $request);
$route = strtok($route, '?'); // Remover query string
$route = trim($route, '/');

// Si la ruta está vacía, ir al dashboard
if (empty($route)) {
    $route = 'dashboard';
}

// Parsear la ruta
$parts = explode('/', $route);
$module = $parts[0] ?? 'dashboard';
$action = $parts[1] ?? 'index';
$id = $parts[2] ?? null;

// Mapeo de rutas a controladores
$routes = [
    'dashboard' => [
        'controller' => 'DashboardController',
        'file' => 'controller/DashboardController.php',
        'actions' => ['index']
    ],
    'asignacion' => [
        'controller' => 'AsignacionController',
        'file' => 'controller/AsignacionController.php',
        'actions' => ['index', 'create', 'store', 'show', 'edit', 'update', 'delete']
    ],
    'ficha' => [
        'controller' => 'FichaController',
        'file' => 'controller/FichaController.php',
        'actions' => ['index', 'create', 'store', 'show', 'edit', 'update', 'delete']
    ],
    'instructor' => [
        'controller' => 'InstructorController',
        'file' => 'controller/InstructorController.php',
        'actions' => ['index', 'create', 'store', 'show', 'edit', 'update', 'delete']
    ],
    'ambiente' => [
        'controller' => 'AmbienteController',
        'file' => 'controller/AmbienteController.php',
        'actions' => ['index', 'create', 'store', 'show', 'edit', 'update', 'delete']
    ],
    'programa' => [
        'controller' => 'ProgramaController',
        'file' => 'controller/ProgramaController.php',
        'actions' => ['index', 'create', 'store', 'show', 'edit', 'update', 'delete']
    ],
    'competencia' => [
        'controller' => 'CompetenciaController',
        'file' => 'controller/CompetenciaController.php',
        'actions' => ['index', 'create', 'store', 'show', 'edit', 'update', 'delete']
    ]
];

// Verificar si el módulo existe
if (!isset($routes[$module])) {
    http_response_code(404);
    die("Módulo no encontrado: $module");
}

$routeConfig = $routes[$module];

// Verificar si la acción es válida
if (!in_array($action, $routeConfig['actions'])) {
    http_response_code(404);
    die("Acción no encontrada: $action");
}

// Cargar el controlador
$controllerFile = __DIR__ . '/' . $routeConfig['file'];
if (!file_exists($controllerFile)) {
    http_response_code(500);
    die("Archivo del controlador no encontrado: {$routeConfig['file']}");
}

require_once $controllerFile;

$controllerClass = $routeConfig['controller'];
if (!class_exists($controllerClass)) {
    http_response_code(500);
    die("Clase del controlador no encontrada: $controllerClass");
}

// Instanciar el controlador
$controller = new $controllerClass();

// Verificar que el método existe
if (!method_exists($controller, $action)) {
    http_response_code(500);
    die("Método no encontrado en el controlador: $action");
}

// Ejecutar la acción
try {
    if ($id !== null) {
        // Acción con ID (show, edit, update, delete)
        $controller->$action($id);
    } else {
        // Acción sin ID (index, create, store)
        $controller->$action();
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error en routing: " . $e->getMessage());
    
    // Mostrar página de error
    $pageTitle = "Error del Sistema";
    include __DIR__ . '/views/layout/header.php';
    include __DIR__ . '/views/layout/sidebar.php';
    ?>
    <div class="main-content">
        <div style="max-width: 600px; margin: 100px auto; text-align: center;">
            <div style="font-size: 72px; margin-bottom: 24px;">⚠️</div>
            <h1 style="font-size: 32px; color: #1f2937; margin-bottom: 16px;">Error del Sistema</h1>
            <p style="font-size: 16px; color: #6b7280; margin-bottom: 32px;">
                Ha ocurrido un error al procesar tu solicitud.
            </p>
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; text-align: left; margin-bottom: 24px;">
                <strong style="color: #991b1b;">Detalles del error:</strong>
                <p style="color: #dc2626; margin: 8px 0 0; font-family: monospace; font-size: 14px;">
                    <?php echo htmlspecialchars($e->getMessage()); ?>
                </p>
            </div>
            <a href="/Gestion-sena/dashboard_sena/" class="btn btn-primary">Volver al Dashboard</a>
        </div>
    </div>
    <?php
    include __DIR__ . '/views/layout/footer.php';
}
?>
