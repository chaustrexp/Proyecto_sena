<?php
require_once __DIR__ . '/conexion.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<h2>Diagnóstico de Tablas</h2>";
    
    // Verificar estructura de INSTRU_COMPETENCIA
    echo "<h3>Estructura de INSTRU_COMPETENCIA:</h3>";
    $stmt = $db->query("DESCRIBE INSTRU_COMPETENCIA");
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
    
    // Verificar estructura de COMPETxPROGRAMA
    echo "<h3>Estructura de COMPETxPROGRAMA:</h3>";
    $stmt = $db->query("DESCRIBE COMPETxPROGRAMA");
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
    
    // Verificar si hay vistas
    echo "<h3>Vistas en la base de datos:</h3>";
    $stmt = $db->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
