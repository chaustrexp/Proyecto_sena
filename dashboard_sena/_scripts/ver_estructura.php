<?php
require_once __DIR__ . '/conexion.php';

header('Content-Type: text/plain');

try {
    $db = Database::getInstance()->getConnection();
    
    echo "=== ESTRUCTURA DE INSTRU_COMPETENCIA ===\n\n";
    $stmt = $db->query("SHOW COLUMNS FROM INSTRU_COMPETENCIA");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
    
    echo "\n\n=== ESTRUCTURA DE COMPETxPROGRAMA ===\n\n";
    $stmt = $db->query("SHOW COLUMNS FROM COMPETxPROGRAMA");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
