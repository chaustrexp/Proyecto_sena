# Migración: Campo Número de Ficha

## 🎯 Objetivo
Agregar el campo `fich_numero` a la tabla FICHA para almacenar números completos de ficha (ej: 3115418).

---

## ⚡ Ejecución Rápida

### Desde phpMyAdmin (Recomendado)

1. Abre: `http://localhost/phpmyadmin`
2. Selecciona la base de datos: `progsena`
3. Clic en pestaña "SQL"
4. Copia y pega este código:

```sql
USE progsena;

ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NOT NULL DEFAULT 0 AFTER `fich_id`;

ALTER TABLE `FICHA` 
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);

UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` = 0;

SELECT '✓ Campo agregado correctamente' AS Resultado;
```

5. Clic en "Continuar"
6. ✅ ¡Listo!

---

## 🔍 Verificación

Ejecuta esta consulta para verificar:

```sql
DESCRIBE FICHA;
```

Deberías ver el campo `fich_numero` con estas características:
- **Type:** int
- **Null:** NO
- **Key:** UNI (UNIQUE)

---

## 📝 Actualizar Fichas Existentes

Si ya tienes fichas, actualiza sus números reales:

```sql
-- Ver fichas actuales
SELECT fich_id, fich_numero FROM FICHA;

-- Actualizar con números reales (ejemplo)
UPDATE FICHA SET fich_numero = 3115418 WHERE fich_id = 2;
UPDATE FICHA SET fich_numero = 3115419 WHERE fich_id = 3;
UPDATE FICHA SET fich_numero = 2895647 WHERE fich_id = 4;
```

---

## ✅ Verificación Final

Ejecuta el script de prueba:

```bash
cd C:\xampp\htdocs\Gestion-sena\dashboard_sena
php _tests/test_campo_fich_numero.php
```

---

## 🔄 Rollback (Si necesitas revertir)

```sql
ALTER TABLE `FICHA` DROP COLUMN `fich_numero`;
```

⚠️ **ADVERTENCIA:** Esto eliminará todos los números de ficha almacenados.

---

## 📊 Antes y Después

### ANTES
```
fich_id | PROGRAMA_prog_id | ...
--------|------------------|----
   2    |        5         | ...
   3    |        8         | ...
```

### DESPUÉS
```
fich_id | fich_numero | PROGRAMA_prog_id | ...
--------|-------------|------------------|----
   2    |  3115418    |        5         | ...
   3    |  3115419    |        8         | ...
```

---

## 💾 Backup Recomendado

Antes de ejecutar, haz un backup:

```bash
mysqldump -u root -p progsena > backup_antes_migracion.sql
```

Para restaurar (si algo sale mal):

```bash
mysql -u root -p progsena < backup_antes_migracion.sql
```
