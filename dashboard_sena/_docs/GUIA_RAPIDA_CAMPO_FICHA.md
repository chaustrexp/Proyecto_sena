# Guía Rápida: Implementación del Campo Número de Ficha

## 📋 Resumen
Esta guía te ayudará a implementar el campo `fich_numero` en tu base de datos para almacenar números completos de ficha (ej: 3115418).

---

## 🚀 Pasos de Implementación

### Paso 1: Ejecutar el Script SQL

**Opción A: Desde phpMyAdmin**
1. Abre phpMyAdmin en tu navegador: `http://localhost/phpmyadmin`
2. Selecciona la base de datos `progsena` en el panel izquierdo
3. Haz clic en la pestaña "SQL" en la parte superior
4. Copia el siguiente código y pégalo en el editor:

```sql
-- Agregar el campo fich_numero
ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NOT NULL AFTER `fich_id`,
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);

-- Actualizar registros existentes con valores temporales
UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` = 0 OR `fich_numero` IS NULL;
```

5. Haz clic en el botón "Continuar" para ejecutar
6. Deberías ver el mensaje: "1 fila afectada"

**Opción B: Desde la línea de comandos**
```bash
cd C:\xampp\htdocs\Gestion-sena\dashboard_sena
mysql -u root -p progsena < _database/agregar_campo_fich_numero.sql
```

---

### Paso 2: Verificar la Instalación

Ejecuta el script de verificación:

```bash
cd C:\xampp\htdocs\Gestion-sena\dashboard_sena
php _tests/test_campo_fich_numero.php
```

Deberías ver:
```
✓ El campo fich_numero existe correctamente
✓ Consulta de asignaciones funciona
✓ Sintaxis INSERT correcta
🎉 ¡TODO ESTÁ FUNCIONANDO CORRECTAMENTE!
```

---

### Paso 3: Actualizar Fichas Existentes (Si las tienes)

Si ya tienes fichas en la base de datos, actualiza sus números:

```sql
-- Ejemplo: Actualizar con números reales
UPDATE FICHA SET fich_numero = 3115418 WHERE fich_id = 2;
UPDATE FICHA SET fich_numero = 3115419 WHERE fich_id = 3;
UPDATE FICHA SET fich_numero = 2895647 WHERE fich_id = 4;
```

**Consulta para ver tus fichas actuales:**
```sql
SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM FICHA;
```

---

### Paso 4: Probar el Sistema

1. **Crear una nueva ficha:**
   - Ve a: `http://localhost/Gestion-sena/dashboard_sena/views/ficha/crear.php`
   - Ingresa un número de ficha (ej: 3115418)
   - Completa los demás campos
   - Guarda

2. **Verificar en asignaciones:**
   - Ve a: `http://localhost/Gestion-sena/dashboard_sena/views/asignacion/index.php`
   - Deberías ver el número completo en la columna "ID (Ficha)"
   - Formato: `03115418` (8 dígitos)

---

## ✅ Checklist de Verificación

- [ ] Script SQL ejecutado sin errores
- [ ] Campo `fich_numero` existe en la tabla FICHA
- [ ] Índice UNIQUE configurado
- [ ] Script de verificación pasa todas las pruebas
- [ ] Fichas existentes actualizadas (si aplica)
- [ ] Formulario de crear ficha muestra el campo
- [ ] Formulario de editar ficha muestra el campo
- [ ] Tabla de asignaciones muestra números completos
- [ ] No hay errores en el log de PHP

---

## 🔍 Verificación Manual en la Base de Datos

```sql
-- Ver estructura de la tabla
DESCRIBE FICHA;

-- Verificar que fich_numero existe y es UNIQUE
SHOW INDEX FROM FICHA WHERE Column_name = 'fich_numero';

-- Ver datos de fichas
SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM FICHA;

-- Verificar que no hay duplicados
SELECT fich_numero, COUNT(*) as total 
FROM FICHA 
GROUP BY fich_numero 
HAVING COUNT(*) > 1;
```

---

## ⚠️ Solución de Problemas

### Error: "Column 'fich_numero' cannot be null"
**Causa:** Intentas crear una ficha sin proporcionar el número.
**Solución:** Asegúrate de llenar el campo "Número de Ficha" en el formulario.

### Error: "Duplicate entry '3115418' for key 'fich_numero_UNIQUE'"
**Causa:** Ya existe una ficha con ese número.
**Solución:** Usa un número diferente o edita la ficha existente.

### Error: "Unknown column 'fich_numero' in 'field list'"
**Causa:** El script SQL no se ejecutó correctamente.
**Solución:** Ejecuta nuevamente el script SQL del Paso 1.

### La tabla de asignaciones muestra números pequeños (2, 3, etc.)
**Causa:** Las fichas existentes no tienen el campo `fich_numero` actualizado.
**Solución:** Ejecuta el UPDATE del Paso 3 para actualizar los números.

---

## 📊 Estructura Final de la Tabla FICHA

```
+---------------------------+-------------+------+-----+---------+----------------+
| Field                     | Type        | Null | Key | Default | Extra          |
+---------------------------+-------------+------+-----+---------+----------------+
| fich_id                   | int         | NO   | PRI | NULL    | auto_increment |
| fich_numero               | int         | NO   | UNI | NULL    |                |
| PROGRAMA_prog_id          | int         | NO   | MUL | NULL    |                |
| INSTRUCTOR_inst_id_lider  | int         | NO   | MUL | NULL    |                |
| fich_jornada              | varchar(20) | NO   |     | NULL    |                |
| COORDINACION_coord_id     | int         | NO   | MUL | NULL    |                |
| fich_fecha_ini_lectiva    | date        | NO   |     | NULL    |                |
| fich_fecha_fin_lectiva    | date        | NO   |     | NULL    |                |
+---------------------------+-------------+------+-----+---------+----------------+
```

---

## 📝 Notas Importantes

1. **Backup:** Siempre haz un backup de tu base de datos antes de ejecutar scripts SQL.
   ```bash
   mysqldump -u root -p progsena > backup_progsena.sql
   ```

2. **Números de Ficha:** El campo soporta números hasta 2,147,483,647 (INT). Si necesitas números más grandes, cambia a BIGINT.

3. **Validación:** El formulario valida que el número tenga máximo 7 dígitos. Ajusta si necesitas más.

4. **Formato:** Los números se muestran con 8 dígitos (ej: 03115418) pero se almacenan sin ceros a la izquierda.

---

## 🎯 Resultado Esperado

Después de completar todos los pasos:

- ✅ Puedes crear fichas con números reales (3115418, 2895647, etc.)
- ✅ Los números se almacenan correctamente en la BD
- ✅ La tabla de asignaciones muestra números completos
- ✅ No se permiten números duplicados
- ✅ El formato es consistente en todo el sistema

---

## 📞 Soporte

Si encuentras problemas:
1. Revisa el log de errores: `dashboard_sena/logs/php_errors.log`
2. Ejecuta el script de verificación: `php _tests/test_campo_fich_numero.php`
3. Verifica la estructura de la BD con las consultas SQL de arriba
4. Consulta la documentación completa: `_docs/CAMPO_NUMERO_FICHA.md`
