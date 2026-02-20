-- Script para agregar el campo fich_numero a la tabla FICHA
-- Este campo almacenará el número real de la ficha (ej: 3115418)

USE progsena;

-- Agregar el campo fich_numero después de fich_id
ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NOT NULL DEFAULT 0 AFTER `fich_id`;

-- Agregar índice UNIQUE para evitar duplicados
ALTER TABLE `FICHA` 
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);

-- Actualizar registros existentes con valores temporales basados en fich_id
-- IMPORTANTE: Después debes actualizar manualmente con los números reales de ficha
UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` = 0;

-- Verificar la estructura actualizada
DESCRIBE `FICHA`;

-- Verificar los datos
SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM `FICHA`;

-- Mensaje de éxito
SELECT '✓ Campo fich_numero agregado correctamente' AS Resultado;
