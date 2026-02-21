# Actualización del Módulo de Fichas con Sistema de Routing

## Fecha
20 de febrero de 2026

## Objetivo
Adaptar el módulo de Fichas para que funcione con el sistema de routing centralizado, siguiendo el mismo patrón implementado en el módulo de Programas.

## Cambios Realizados

### 1. Vista `crear.php`
**Archivo**: `dashboard_sena/views/ficha/crear.php`

**Cambios**:
- ✅ Eliminada toda la lógica de controlador (validación y guardado)
- ✅ Actualizado el action del formulario a `/ficha/create`
- ✅ Corregidos los nombres de campos para coincidir con la base de datos:
  - `programa_id` → `PROGRAMA_prog_id`
  - `instructor_id` → `INSTRUCTOR_inst_id_lider`
  - `jornada` → `fich_jornada`
  - `coordinacion_id` → `COORDINACION_coord_id`
  - `fecha_inicio` → `fich_fecha_ini_lectiva`
  - `fecha_fin` → `fich_fecha_fin_lectiva`
- ✅ Actualizados los enlaces de navegación a `/ficha`
- ✅ Manejo de errores y old_input desde `$_SESSION`

### 2. Vista `editar.php`
**Archivo**: `dashboard_sena/views/ficha/editar.php`

**Cambios**:
- ✅ Eliminada toda la lógica de controlador
- ✅ El registro ahora viene del controlador como variable `$registro`
- ✅ Actualizado el action del formulario a `/ficha/edit/{id}`
- ✅ Actualizados los enlaces de navegación a `/ficha`
- ✅ Agregado `require_once` para `functions.php`

### 3. Vista `ver.php`
**Archivo**: `dashboard_sena/views/ficha/ver.php`

**Cambios**:
- ✅ Eliminada la lógica de consulta del modelo
- ✅ El registro ahora viene del controlador
- ✅ Corregidos los nombres de campos para mostrar:
  - `id` → `fich_id`
  - `numero` → `fich_numero`
  - `programa_nombre` → `prog_denominacion`
  - Agregado: `instructor_lider`, `fich_jornada`, `coord_descripcion`
  - Agregado: `fich_fecha_ini_lectiva`, `fich_fecha_fin_lectiva`
- ✅ Actualizados los enlaces a `/ficha/edit/{id}` y `/ficha`

### 4. Controlador `FichaController.php`
**Archivo**: `dashboard_sena/controller/FichaController.php`

**Estado**: ✅ Ya estaba correctamente implementado con:
- Método `index()` - Listado de fichas
- Método `create()` - Formulario de creación
- Método `store()` - Guardar nueva ficha
- Método `show($id)` - Ver detalle
- Método `edit($id)` - Formulario de edición
- Método `update($id)` - Actualizar ficha
- Método `delete($id)` - Eliminar ficha

**Validaciones en `store()` y `update()`**:
- Campos requeridos: `fich_numero`, `PROGRAMA_prog_id`, `fich_jornada`, `fich_fecha_ini_lectiva`, `fich_fecha_fin_lectiva`
- Validación numérica para `fich_numero`
- Validación de fechas (fecha fin debe ser posterior a fecha inicio)

### 5. Modelo `FichaModel.php`
**Archivo**: `dashboard_sena/model/FichaModel.php`

**Estado**: ✅ Ya estaba correctamente implementado
- Método `create()` acepta tanto nombres de campos de base de datos como alias amigables
- Método `update()` con la misma flexibilidad
- Método `getById()` incluye JOINs con PROGRAMA, INSTRUCTOR y COORDINACION

## Rutas Disponibles

### Módulo Fichas
- `GET /ficha` → Lista todas las fichas
- `GET /ficha/create` → Formulario de creación
- `POST /ficha/create` → Guarda nueva ficha (acción: store)
- `GET /ficha/show/{id}` → Ver detalle de ficha
- `GET /ficha/edit/{id}` → Formulario de edición
- `POST /ficha/edit/{id}` → Actualiza ficha (acción: update)
- `POST /ficha/delete/{id}` → Elimina ficha

## Estructura de Datos

### Tabla FICHA
```sql
- fich_id (PK)
- fich_numero (VARCHAR)
- PROGRAMA_prog_id (FK)
- INSTRUCTOR_inst_id_lider (FK)
- fich_jornada (ENUM: Diurna, Nocturna, Mixta, Fin de Semana)
- COORDINACION_coord_id (FK)
- fich_fecha_ini_lectiva (DATE)
- fich_fecha_fin_lectiva (DATE)
```

## Pruebas Recomendadas

1. **Crear Ficha**:
   - Ir a `/ficha/create`
   - Llenar el formulario con datos válidos
   - Verificar que se guarde correctamente
   - Verificar que redirija a `/ficha` con mensaje de éxito

2. **Validaciones**:
   - Intentar crear sin campos requeridos
   - Intentar con número de ficha no numérico
   - Intentar con fecha fin anterior a fecha inicio
   - Verificar que muestre errores apropiados

3. **Ver Detalle**:
   - Hacer clic en "Ver" desde el listado
   - Verificar que muestre todos los datos correctamente

4. **Editar Ficha**:
   - Hacer clic en "Editar" desde el listado o detalle
   - Modificar datos
   - Verificar que se actualice correctamente

5. **Eliminar Ficha**:
   - Hacer clic en "Eliminar" desde el listado
   - Verificar que se elimine correctamente

## Notas Importantes

- El sistema de routing maneja automáticamente la conversión de acciones POST:
  - `POST /ficha/create` → ejecuta `store()`
  - `POST /ficha/edit/{id}` → ejecuta `update($id)`

- Los mensajes de éxito/error se manejan mediante `$_SESSION`:
  - `$_SESSION['success']` para mensajes de éxito
  - `$_SESSION['error']` para mensajes de error
  - `$_SESSION['errors']` para errores de validación (array)
  - `$_SESSION['old_input']` para mantener datos del formulario

- El modelo acepta nombres de campos flexibles para facilitar la integración

## Próximos Pasos

Continuar con la actualización de los siguientes módulos:
1. ✅ Dashboard (completado)
2. ✅ Programa (completado)
3. ✅ Ficha (completado)
4. ⏳ Instructor (pendiente)
5. ⏳ Ambiente (pendiente)
6. ⏳ Asignación (pendiente)
7. ⏳ Competencia (pendiente)
