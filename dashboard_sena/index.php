<?php
/**
 * Punto de entrada principal del Dashboard SENA
 * Utiliza el DashboardController para manejar la lógica
 */

// Proteger página con autenticación
require_once __DIR__ . '/auth/check_auth.php';

// Cargar el controlador del dashboard
require_once __DIR__ . '/controller/DashboardController.php';

// Instanciar y ejecutar el controlador
$controller = new DashboardController();
$controller->index();
?>
