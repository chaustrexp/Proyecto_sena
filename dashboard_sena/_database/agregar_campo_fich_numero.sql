-- Script para agregar el campo fich_numero a la tabla FICHA
-- Este campo almacenará el número real de la ficha (ej: 3115418)

-- Agregar el campo fich_numero después de fich_id
ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NOT NULL AFTER `fich_id`,
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);

-- Comentario: El campo fich_numero es UNIQUE para evitar duplicados
-- Tipo INT permite números hasta 2,147,483,647 (suficiente para números de ficha)

-- Si necesitas números más grandes, usa BIGINT:
-- ALTER TABLE `FICHA` MODIFY COLUMN `fich_numero` BIGINT NOT NULL;

-- Actualizar registros existentes (si los hay) con valores temporales
-- IMPORTANTE: Debes actualizar manualmente con los números reales de ficha
UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` = 0 OR `fich_numero` IS NULL;

-- Verificar la estructura actualizada
DESCRIBE `FICHA`;

-- Verificar los datos
SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM `FICHA` LIMIT 10;
