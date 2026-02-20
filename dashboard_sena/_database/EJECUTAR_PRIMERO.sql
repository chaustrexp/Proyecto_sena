-- ============================================
-- SCRIPT PARA AGREGAR CAMPO fich_numero
-- EJECUTAR ESTE SCRIPT ANTES DE USAR EL SISTEMA
-- ============================================

USE progsena;

-- Verificar si el campo ya existe
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'progsena' 
  AND TABLE_NAME = 'FICHA' 
  AND COLUMN_NAME = 'fich_numero';

-- Si el resultado está vacío, ejecutar lo siguiente:

-- Agregar el campo fich_numero
ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NULL AFTER `fich_id`;

-- Actualizar registros existentes con valores temporales basados en fich_id
UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` IS NULL;

-- Ahora hacer el campo NOT NULL
ALTER TABLE `FICHA` 
MODIFY COLUMN `fich_numero` INT NOT NULL;

-- Agregar índice UNIQUE
ALTER TABLE `FICHA` 
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);

-- Verificar que se agregó correctamente
DESCRIBE `FICHA`;

-- Ver los datos actuales
SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM `FICHA`;

-- ============================================
-- IMPORTANTE: Después de ejecutar este script,
-- debes actualizar manualmente los números de ficha
-- con los valores reales. Ejemplo:
-- 
-- UPDATE FICHA SET fich_numero = 3115418 WHERE fich_id = 2;
-- UPDATE FICHA SET fich_numero = 3115419 WHERE fich_id = 3;
-- ============================================
