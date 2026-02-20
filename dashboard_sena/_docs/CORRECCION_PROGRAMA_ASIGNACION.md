# Corrección: Campo Programa en Tabla de Asignaciones

## Problema Identificado

La tabla de asignaciones en `views/asignacion/index.php` mostraba una columna "Programa" pero el campo `programa_nombre` no estaba disponible en los datos devueltos por el modelo.

### Síntomas
- La columna "Programa" mostraba "N/A" en todas las filas
- El campo `programa_nombre` no existía en el array de resultados
- La consulta SQL solo incluía `competencia_nombre` pero no `programa_nombre`

## Solución Implementada

### 1. Actualización del Modelo (AsignacionModel.php)

Se agregó el JOIN con la tabla PROGRAMA a través de FICHA en todos los métodos relevantes:

#### Métodos actualizados:
- `getAll()` - Lista todas las asignaciones
- `getById($id)` - Obtiene una asignación específica
- `getRecent($limit)` - Obtiene asignaciones recientes
- `getForCalendar($month, $year)` - Obtiene asignaciones para el calendario

#### Cambios en la consulta SQL:

**ANTES:**
```sql
SELECT a.*,
       f.fich_id as ficha_numero,
       CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
       amb.amb_nombre as ambiente_nombre,
       c.comp_nombre_corto as competencia_nombre
FROM ASIGNACION a
LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
```

**DESPUÉS:**
```sql
SELECT a.*,
       f.fich_id as ficha_numero,
       CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
       amb.amb_nombre as ambiente_nombre,
       c.comp_nombre_corto as competencia_nombre,
       p.prog_nombre as programa_nombre  -- ← NUEVO CAMPO
FROM ASIGNACION a
LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_id  -- ← NUEVO JOIN
LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
```

### 2. Estructura de la Vista (index.php)

La vista ya estaba correctamente configurada para mostrar el programa:

```php
<th>Programa</th>
...
<td>
    <div style="font-weight: 600; color: #1f2937;">
        <?php echo htmlspecialchars($registro['programa_nombre'] ?? 'N/A'); ?>
    </div>
</td>
```

## Relación entre Tablas

```
ASIGNACION
    ↓ (FICHA_fich_id)
FICHA
    ↓ (PROGRAMA_prog_id)
PROGRAMA
```

Cada ficha pertenece a un programa, por lo que para obtener el nombre del programa de una asignación, necesitamos hacer JOIN desde ASIGNACION → FICHA → PROGRAMA.

## Estructura de la Tabla de Asignaciones

| Columna | Contenido | Formato |
|---------|-----------|---------|
| ID (Ficha) | Número de ficha | 8 dígitos con ceros a la izquierda |
| Programa | Nombre del programa | Texto completo |
| Instructor | Nombre completo del instructor | Nombres + Apellidos |
| Ambiente | Nombre del ambiente | Texto |
| Fecha Inicio | Fecha de inicio de la asignación | dd/mm/yyyy |
| Estado | Estado actual | Badge (Activa/Pendiente/Finalizada) |
| Acciones | Botones de acción | Ver/Editar/Eliminar |

## Verificación

Para verificar que los cambios funcionan correctamente, ejecutar:

```bash
php dashboard_sena/_tests/test_programa_asignacion.php
```

Este script:
1. Obtiene todas las asignaciones
2. Verifica que cada una tenga el campo `programa_nombre`
3. Muestra los datos de cada asignación
4. Prueba el método `getById()`

## Archivos Modificados

- `dashboard_sena/model/AsignacionModel.php` - Actualización de consultas SQL
- `dashboard_sena/_tests/test_programa_asignacion.php` - Script de verificación (nuevo)
- `dashboard_sena/_docs/CORRECCION_PROGRAMA_ASIGNACION.md` - Esta documentación (nuevo)

## Resultado Esperado

Ahora la tabla de asignaciones debe mostrar:
- ✓ Número de ficha con 8 dígitos en la columna "ID (Ficha)"
- ✓ Nombre completo del programa en la columna "Programa"
- ✓ Todos los demás campos funcionando correctamente

## Notas Adicionales

- El campo `competencia_nombre` se mantiene disponible para uso futuro
- Todos los métodos del modelo ahora incluyen `programa_nombre`
- La relación FICHA → PROGRAMA es obligatoria en la base de datos
- Si una ficha no tiene programa asignado, se mostrará "N/A"
