<?php
require_once __DIR__ . '/conexion.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<h2>Reparando estructura de INSTRU_COMPETENCIA</h2>";
    
    // Verificar si la tabla existe
    $stmt = $db->query("SHOW TABLES LIKE 'INSTRU_COMPETENCIA'");
    if ($stmt->rowCount() == 0) {
        echo "<p style='color: red;'>La tabla INSTRU_COMPETENCIA no existe. Creándola...</p>";
        
        // Crear la tabla con la estructura correcta
        $sql = "CREATE TABLE IF NOT EXISTS `INSTRU_COMPETENCIA` (
          `inscomp_id` INT NOT NULL AUTO_INCREMENT,
          `INSTRUCTOR_inst_id` INT NOT NULL,
          `COMPETxPROGRAMA_PROGRAMA_prog_id` INT NOT NULL,
          `COMPETxPROGRAMA_COMPETENCIA_comp_id` INT NOT NULL,
          `inscomp_vigencia` DATE NOT NULL,
          PRIMARY KEY (`inscomp_id`),
          INDEX `fk_INSTRU_COMPETENCIA_INSTRUCTOR_idx` (`INSTRUCTOR_inst_id` ASC),
          INDEX `fk_INSTRU_COMPETENCIA_COMPETxPROGRAMA_idx` (`COMPETxPROGRAMA_PROGRAMA_prog_id` ASC, `COMPETxPROGRAMA_COMPETENCIA_comp_id` ASC)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->exec($sql);
        echo "<p style='color: green;'>✓ Tabla creada exitosamente</p>";
    } else {
        echo "<p style='color: green;'>✓ La tabla INSTRU_COMPETENCIA existe</p>";
        
        // Mostrar estructura actual
        echo "<h3>Estructura actual:</h3>";
        $stmt = $db->query("SHOW COLUMNS FROM INSTRU_COMPETENCIA");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<br><a href='index.php'>Volver al Dashboard</a>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Detalles: " . $e->getTraceAsString() . "</p>";
}
?>
