<?php
require_once 'conexion.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Crear tabla ADMINISTRADOR
    $sql = "CREATE TABLE IF NOT EXISTS `ADMINISTRADOR` (
      `admin_id` INT NOT NULL AUTO_INCREMENT,
      `admin_nombre` VARCHAR(100) NOT NULL,
      `admin_correo` VARCHAR(100) NOT NULL UNIQUE,
      `admin_password` VARCHAR(255) NOT NULL,
      `admin_estado` ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
      `admin_ultimo_acceso` DATETIME NULL,
      PRIMARY KEY (`admin_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->exec($sql);
    echo "✓ Tabla ADMINISTRADOR creada<br>";
    
    // Insertar administrador por defecto
    $stmt = $db->prepare("INSERT INTO ADMINISTRADOR (admin_nombre, admin_correo, admin_password, admin_estado) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        'Administrador SENA',
        'admin@sena.edu.co',
        password_hash('password', PASSWORD_DEFAULT),
        'Activo'
    ]);
    
    echo "✓ Administrador por defecto creado<br>";
    echo "<br><strong>Credenciales:</strong><br>";
    echo "Email: admin@sena.edu.co<br>";
    echo "Password: password<br>";
    echo "<br><a href='auth/login.php'>Ir al Login</a>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
