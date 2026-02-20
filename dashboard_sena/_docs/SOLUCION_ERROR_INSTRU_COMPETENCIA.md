# 🔧 Solución: Error 500 en Competencias de Instructores

## 📋 Problema Identificado

El Error 500 en `views/instru_competencia/index.php` ocurre por una **restricción de integridad referencial** en la base de datos.

### Causa Raíz

La tabla `INSTRU_COMPETENCIA` tiene una clave foránea compuesta que referencia a `COMPETxPROGRAMA`:

```sql
FOREIGN KEY (COMPETxPROGRAMA_PROGRAMA_prog_id, COMPETxPROGRAMA_COMPETENCIA_comp_id) 
REFERENCES COMPETxPROGRAMA (PROGRAMA_prog_id, COMPETENCIA_comp_id)
```

**El error ocurre cuando:**
- Intentas asignar una competencia a un instructor
- Pero esa combinación de Programa + Competencia NO EXISTE en la tabla `COMPETxPROGRAMA`

## ✅ Solución Implementada

### 1. Script de Diagnóstico Completo

Ejecuta este script para ver el estado de tu base de datos:

```
http://localhost/Gestion-sena/dashboard_sena/_tests/diagnostico_instru_competencia_completo.php
```

Este script te mostrará:
- ✓ Estructura de todas las tablas involucradas
- ✓ Cantidad de registros en cada tabla
- ✓ Datos en COMPETxPROGRAMA (tabla intermedia crítica)
- ✓ Verificación de integridad referencial
- ✓ Recomendaciones específicas

### 2. Mejoras en la Vista

Se actualizó `views/instru_competencia/index.php` con:

#### a) Validación Antes de Insertar
```php
// Verifica que la combinación programa+competencia existe
$stmt = $db->prepare("
    SELECT COUNT(*) as existe 
    FROM COMPETxPROGRAMA 
    WHERE PROGRAMA_prog_id = ? AND COMPETENCIA_comp_id = ?
");
```

#### b) Mensajes de Error Claros
- Muestra advertencia si no hay datos en COMPETxPROGRAMA
- Indica al usuario que debe ir a "Competencias por Programa" primero
- Muestra errores específicos al intentar crear asignaciones inválidas

#### c) Modal Mejorado
- Solo muestra combinaciones válidas de Programa + Competencia
- Usa un selector combinado en lugar de dos selectores separados
- Previene selecciones inválidas desde el inicio

### 3. Manejo de Errores

```php
try {
    $registros = $model->getAll();
    // ... código ...
} catch (Exception $e) {
    $errorMsg = 'Error al cargar datos: ' . $e->getMessage();
    $registros = [];
}
```

## 🚀 Pasos para Usar el Sistema

### Orden Correcto de Operaciones:

1. **Crear Programas** (si no existen)
   - Ve a: `views/programa/index.php`
   - Crea los programas de formación

2. **Crear Competencias** (si no existen)
   - Ve a: `views/competencia/index.php`
   - Crea las competencias

3. **Asociar Competencias con Programas** ⚠️ CRÍTICO
   - Ve a: `views/competencia_programa/index.php`
   - Asocia cada competencia con su(s) programa(s)
   - Esta tabla es REQUERIDA para el siguiente paso

4. **Crear Instructores** (si no existen)
   - Ve a: `views/instructor/index.php`
   - Registra los instructores

5. **Asignar Competencias a Instructores**
   - Ve a: `views/instru_competencia/index.php`
   - Ahora sí puedes asignar competencias a instructores
   - Solo verás las combinaciones válidas creadas en el paso 3

## 🔍 Verificación

### Comprobar que COMPETxPROGRAMA tiene datos:

```sql
SELECT cp.*, 
       p.prog_denominacion, 
       c.comp_nombre_corto
FROM COMPETxPROGRAMA cp
LEFT JOIN PROGRAMA p ON cp.PROGRAMA_prog_id = p.prog_codigo
LEFT JOIN COMPETENCIA c ON cp.COMPETENCIA_comp_id = c.comp_id;
```

Si esta consulta devuelve 0 registros, debes crear asociaciones primero.

## 📝 Notas Importantes

1. **NO está relacionado con `fich_numero`**: Este error es independiente del campo de número de ficha que agregamos anteriormente.

2. **Restricción de Base de Datos**: Es una característica de seguridad que garantiza integridad referencial.

3. **Solución Permanente**: Las mejoras implementadas previenen este error en el futuro mostrando solo opciones válidas.

## 🎯 Resultado

Después de estas correcciones:
- ✅ No más Error 500 en la página
- ✅ Mensajes claros si falta configuración
- ✅ Solo se pueden crear asignaciones válidas
- ✅ Mejor experiencia de usuario

## 🔗 Archivos Modificados

- `views/instru_competencia/index.php` - Vista principal con validaciones
- `_tests/diagnostico_instru_competencia_completo.php` - Script de diagnóstico
- `_docs/SOLUCION_ERROR_INSTRU_COMPETENCIA.md` - Esta guía

---

**Fecha:** 20 de febrero de 2026  
**Estado:** ✅ Resuelto
