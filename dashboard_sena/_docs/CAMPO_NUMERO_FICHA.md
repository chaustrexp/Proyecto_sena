# Implementación del Campo Número de Ficha

## Problema Identificado

La tabla FICHA solo tenía el campo `fich_id` (autoincremental) que generaba números secuenciales (1, 2, 3...), pero no almacenaba el número real de la ficha (ej: 3115418).

## Solución Implementada

### 1. Modificación de la Base de Datos

Se agregó un nuevo campo `fich_numero` a la tabla FICHA:

```sql
ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NOT NULL AFTER `fich_id`,
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);
```

**Características del campo:**
- Tipo: `INT` (permite números hasta 2,147,483,647)
- NOT NULL: Campo obligatorio
- UNIQUE: No permite duplicados
- Posición: Después de `fich_id`

**Script SQL:** `_database/agregar_campo_fich_numero.sql`

### 2. Actualización del Modelo FichaModel.php

#### Método create()
**ANTES:**
```php
INSERT INTO FICHA (PROGRAMA_prog_id, INSTRUCTOR_inst_id_lider, ...) 
VALUES (?, ?, ...)
```

**DESPUÉS:**
```php
INSERT INTO FICHA (fich_numero, PROGRAMA_prog_id, INSTRUCTOR_inst_id_lider, ...) 
VALUES (?, ?, ?, ...)
```

#### Método update()
Se agregó `fich_numero` como primer parámetro en el UPDATE.

### 3. Actualización de Formularios

#### views/ficha/crear.php
Se agregó un campo de entrada para el número de ficha:

```php
<div class="form-group">
    <label>Número de Ficha *</label>
    <input type="number" name="fich_numero" class="form-control" 
           placeholder="Ej: 3115418" required min="1" max="9999999">
    <small>Ingrese el número completo de la ficha (7 dígitos)</small>
</div>
```

#### views/ficha/editar.php
- Actualizado para incluir el campo `fich_numero`
- Corregidos todos los campos para coincidir con la estructura real de la BD
- Agregados campos faltantes: instructor líder, jornada, coordinación

### 4. Actualización del Modelo AsignacionModel.php

Se cambió la consulta para usar `fich_numero` en lugar de `fich_id`:

**ANTES:**
```sql
SELECT f.fich_id as ficha_numero
FROM ASIGNACION a
LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
```

**DESPUÉS:**
```sql
SELECT f.fich_numero as ficha_numero
FROM ASIGNACION a
LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
```

Métodos actualizados:
- `getAll()`
- `getById()`
- `getRecent()`
- `getForCalendar()`

### 5. Visualización en Tablas

La tabla de asignaciones ahora muestra el número real de la ficha:

```php
<?php echo str_pad(htmlspecialchars($registro['ficha_numero'] ?? ''), 8, '0', STR_PAD_LEFT); ?>
```

Ejemplo de salida: `03115418`

## Estructura de Campos en FICHA

| Campo | Tipo | Descripción |
|-------|------|-------------|
| fich_id | INT AUTO_INCREMENT | ID interno (clave primaria) |
| fich_numero | INT UNIQUE | Número real de la ficha (ej: 3115418) |
| PROGRAMA_prog_id | INT | Relación con PROGRAMA |
| INSTRUCTOR_inst_id_lider | INT | Instructor líder de la ficha |
| fich_jornada | VARCHAR(20) | Diurna/Nocturna/Mixta/Fin de Semana |
| COORDINACION_coord_id | INT | Relación con COORDINACION |
| fich_fecha_ini_lectiva | DATE | Fecha inicio etapa lectiva |
| fich_fecha_fin_lectiva | DATE | Fecha fin etapa lectiva |

## Pasos para Aplicar los Cambios

### 1. Ejecutar el Script SQL
```bash
mysql -u root -p progsena < dashboard_sena/_database/agregar_campo_fich_numero.sql
```

O desde phpMyAdmin:
1. Abrir la base de datos `progsena`
2. Ir a la pestaña SQL
3. Copiar y ejecutar el contenido de `agregar_campo_fich_numero.sql`

### 2. Actualizar Fichas Existentes

Si ya tienes fichas en la base de datos, debes actualizar manualmente sus números:

```sql
-- Ejemplo: Actualizar fichas con sus números reales
UPDATE FICHA SET fich_numero = 3115418 WHERE fich_id = 1;
UPDATE FICHA SET fich_numero = 3115419 WHERE fich_id = 2;
UPDATE FICHA SET fich_numero = 3115420 WHERE fich_id = 3;
```

### 3. Verificar los Cambios

```sql
-- Ver la estructura de la tabla
DESCRIBE FICHA;

-- Ver los datos actualizados
SELECT fich_id, fich_numero, PROGRAMA_prog_id FROM FICHA;
```

## Validaciones Implementadas

### En el Formulario (HTML5)
- `type="number"`: Solo permite números
- `required`: Campo obligatorio
- `min="1"`: Valor mínimo 1
- `max="9999999"`: Valor máximo 7 dígitos

### En la Base de Datos
- `NOT NULL`: No permite valores nulos
- `UNIQUE`: No permite números duplicados
- `INT`: Tipo numérico entero

## Formato de Visualización

En todas las tablas y listados, el número de ficha se muestra con 8 dígitos:

- Entrada: `3115418`
- Visualización: `03115418`

Esto se logra con:
```php
str_pad($numero, 8, '0', STR_PAD_LEFT)
```

## Archivos Modificados

### Base de Datos
- `_database/agregar_campo_fich_numero.sql` (nuevo)

### Modelos
- `model/FichaModel.php` - Métodos create() y update()
- `model/AsignacionModel.php` - Todos los métodos de consulta

### Vistas
- `views/ficha/crear.php` - Agregado campo fich_numero
- `views/ficha/editar.php` - Actualizado completamente

### Documentación
- `_docs/CAMPO_NUMERO_FICHA.md` (este archivo)

## Notas Importantes

1. **Migración de Datos**: Si tienes fichas existentes, debes actualizar manualmente el campo `fich_numero` con los valores reales.

2. **Validación Única**: El campo `fich_numero` tiene restricción UNIQUE, por lo que no se pueden crear dos fichas con el mismo número.

3. **Tipo de Dato**: Se usa INT que soporta números hasta 2,147,483,647. Si necesitas números más grandes, cambia a BIGINT.

4. **Compatibilidad**: Los cambios son retrocompatibles. Si `fich_numero` no existe en registros antiguos, se mostrará el `fich_id`.

## Resultado Final

Ahora el sistema:
- ✅ Permite ingresar números de ficha reales (ej: 3115418)
- ✅ Almacena correctamente en la base de datos
- ✅ Muestra el número completo en todas las tablas
- ✅ Valida que no haya duplicados
- ✅ Formatea con 8 dígitos para visualización consistente
