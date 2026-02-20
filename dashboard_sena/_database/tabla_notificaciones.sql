-- ============================================
-- TABLA DE NOTIFICACIONES (OPCIONAL)
-- ============================================
-- Esta tabla es opcional. Si no existe, el sistema usará notificaciones de ejemplo.
-- Ejecuta este script si deseas implementar notificaciones reales en la base de datos.

CREATE TABLE IF NOT EXISTS `notificaciones` (
  `IdNotificacion` int(11) NOT NULL AUTO_INCREMENT,
  `IdUsuario` int(11) NOT NULL,
  `Titulo` varchar(255) NOT NULL,
  `Mensaje` text NOT NULL,
  `Tipo` enum('info','success','warning','error') DEFAULT 'info',
  `Leida` tinyint(1) DEFAULT 0,
  `Url` varchar(500) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdNotificacion`),
  KEY `idx_usuario` (`IdUsuario`),
  KEY `idx_leida` (`Leida`),
  KEY `idx_fecha` (`FechaCreacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- FUNCIÓN PARA CREAR NOTIFICACIONES
-- ============================================
DELIMITER $$

CREATE PROCEDURE IF NOT EXISTS crear_notificacion(
    IN p_usuario_id INT,
    IN p_titulo VARCHAR(255),
    IN p_mensaje TEXT,
    IN p_tipo VARCHAR(20),
    IN p_url VARCHAR(500)
)
BEGIN
    INSERT INTO notificaciones (IdUsuario, Titulo, Mensaje, Tipo, Url)
    VALUES (p_usuario_id, p_titulo, p_mensaje, p_tipo, p_url);
END$$

DELIMITER ;

-- ============================================
-- TRIGGERS PARA CREAR NOTIFICACIONES AUTOMÁTICAS
-- ============================================

-- Notificación al crear una asignación
DELIMITER $$

CREATE TRIGGER IF NOT EXISTS notif_nueva_asignacion
AFTER INSERT ON asignacion
FOR EACH ROW
BEGIN
    DECLARE v_ficha VARCHAR(50);
    DECLARE v_instructor VARCHAR(100);
    
    -- Obtener datos de la ficha
    SELECT NumeroFicha INTO v_ficha
    FROM ficha
    WHERE IdFicha = NEW.IdFicha;
    
    -- Obtener nombre del instructor
    SELECT CONCAT(Nombre, ' ', Apellido) INTO v_instructor
    FROM instructor
    WHERE IdInstructor = NEW.IdInstructor;
    
    -- Crear notificación para todos los administradores
    INSERT INTO notificaciones (IdUsuario, Titulo, Mensaje, Tipo, Url)
    SELECT 
        IdAdministrador,
        'Nueva asignación creada',
        CONCAT('Se ha creado una asignación para la ficha ', v_ficha, ' con el instructor ', v_instructor),
        'info',
        CONCAT('/Gestion-sena/dashboard_sena/views/asignacion/ver.php?id=', NEW.IdAsignacion)
    FROM administrador;
END$$

DELIMITER ;

-- Notificación al crear un instructor
DELIMITER $$

CREATE TRIGGER IF NOT EXISTS notif_nuevo_instructor
AFTER INSERT ON instructor
FOR EACH ROW
BEGIN
    INSERT INTO notificaciones (IdUsuario, Titulo, Mensaje, Tipo, Url)
    SELECT 
        IdAdministrador,
        'Nuevo instructor registrado',
        CONCAT('Se ha registrado el instructor ', NEW.Nombre, ' ', NEW.Apellido),
        'success',
        CONCAT('/Gestion-sena/dashboard_sena/views/instructor/ver.php?id=', NEW.IdInstructor)
    FROM administrador;
END$$

DELIMITER ;

-- Notificación al crear una ficha
DELIMITER $$

CREATE TRIGGER IF NOT EXISTS notif_nueva_ficha
AFTER INSERT ON ficha
FOR EACH ROW
BEGIN
    DECLARE v_programa VARCHAR(255);
    
    SELECT NombrePrograma INTO v_programa
    FROM programa
    WHERE IdPrograma = NEW.IdPrograma;
    
    INSERT INTO notificaciones (IdUsuario, Titulo, Mensaje, Tipo, Url)
    SELECT 
        IdAdministrador,
        'Nueva ficha creada',
        CONCAT('Se ha creado la ficha ', NEW.NumeroFicha, ' para el programa ', v_programa),
        'info',
        CONCAT('/Gestion-sena/dashboard_sena/views/ficha/ver.php?id=', NEW.IdFicha)
    FROM administrador;
END$$

DELIMITER ;

-- ============================================
-- DATOS DE EJEMPLO (OPCIONAL)
-- ============================================
-- Inserta algunas notificaciones de ejemplo
-- Reemplaza el IdUsuario con el ID real de tu usuario administrador

/*
INSERT INTO notificaciones (IdUsuario, Titulo, Mensaje, Tipo, Leida) VALUES
(1, 'Bienvenido al sistema', 'Bienvenido al Dashboard SENA. Aquí podrás gestionar todas las asignaciones.', 'success', 1),
(1, 'Nueva asignación pendiente', 'Tienes una nueva asignación que requiere tu atención.', 'warning', 0),
(1, 'Actualización del sistema', 'El sistema ha sido actualizado con nuevas funcionalidades.', 'info', 0),
(1, 'Recordatorio', 'Recuerda revisar las asignaciones de esta semana.', 'info', 0);
*/

-- ============================================
-- CONSULTAS ÚTILES
-- ============================================

-- Ver todas las notificaciones de un usuario
-- SELECT * FROM notificaciones WHERE IdUsuario = 1 ORDER BY FechaCreacion DESC;

-- Contar notificaciones no leídas
-- SELECT COUNT(*) as no_leidas FROM notificaciones WHERE IdUsuario = 1 AND Leida = 0;

-- Marcar todas como leídas
-- UPDATE notificaciones SET Leida = 1 WHERE IdUsuario = 1;

-- Eliminar notificaciones antiguas (más de 30 días)
-- DELETE FROM notificaciones WHERE FechaCreacion < DATE_SUB(NOW(), INTERVAL 30 DAY);
