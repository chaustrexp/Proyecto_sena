# ⚠️ IMPORTANTE: LEER ANTES DE USAR EL SISTEMA

## Error 500 en Asignaciones

Si ves un error 500 al acceder a la página de asignaciones, es porque falta agregar el campo `fich_numero` a la base de datos.

## Solución Rápida

### Opción 1: Ejecutar desde phpMyAdmin (RECOMENDADO)

1. Abre **phpMyAdmin** en tu navegador: `http://localhost/phpmyadmin`
2. Selecciona la base de datos **progsena**
3. Ve a la pestaña **SQL**
4. Copia y pega el siguiente código:

```sql
-- Agregar el campo fich_numero
ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NULL AFTER `fich_id`;

-- Actualizar registros existentes
UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` IS NULL;

-- Hacer el campo NOT NULL
ALTER TABLE `FICHA` 
MODIFY COLUMN `fich_numero` INT NOT NULL;

-- Agregar índice UNIQUE
ALTER TABLE `FICHA` 
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);
```

5. Haz clic en **Continuar**
6. Recarga la página de asignaciones

### Opción 2: Ejecutar desde línea de comandos

```bash
mysql -u root -p progsena < dashboard_sena/_database/EJECUTAR_PRIMERO.sql
```

## Después de Ejecutar el Script

El sistema ahora funcionará correctamente. Los números de ficha actuales mostrarán valores temporales (2, 3, etc.).

Para actualizar con los números reales de ficha:

1. Ve a phpMyAdmin
2. Selecciona la base de datos **progsena**
3. Ve a la pestaña **SQL**
4. Ejecuta:

```sql
-- Reemplaza los números con los valores reales
UPDATE FICHA SET fich_numero = 3115418 WHERE fich_id = 2;
UPDATE FICHA SET fich_numero = 3115419 WHERE fich_id = 3;
-- Agrega más líneas según sea necesario
```

## Crear Nuevas Fichas

Cuando crees nuevas fichas, ahora verás un campo "Número de Ficha" donde puedes ingresar el número completo (ej: 3115418).

## Verificar que Funciona

1. Ejecuta el script SQL
2. Recarga la página de asignaciones
3. Deberías ver los números de ficha en la columna "ID (Ficha)"
4. Si ves números pequeños (2, 3), actualízalos con los valores reales usando el UPDATE

## Archivos de Referencia

- Script SQL: `_database/EJECUTAR_PRIMERO.sql`
- Documentación completa: `_docs/CAMPO_NUMERO_FICHA.md`

## Soporte

Si tienes problemas, revisa el log de errores en:
`dashboard_sena/logs/php_errors.log`
