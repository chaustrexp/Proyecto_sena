# Solución: Error Tabla COORDINACION

## Problema
```
SQLSTATE[42S02]: Base table or view not found: 1932 
Table 'progsena.coordinacion' doesn't exist in engine
```

## Causa
La tabla `COORDINACION` no existe en la base de datos `progsena`.

## Solución

### Paso 1: Ejecutar el Script de Reparación
Abre en tu navegador:
```
http://localhost/Gestion-sena/dashboard_sena/_scripts/fix_coordinacion_table.php
```

### Paso 2: Verificar la Ejecución
El script realizará automáticamente:
1. ✓ Conexión a la base de datos
2. ✓ Verificación de la base de datos `progsena`
3. ✓ Verificación de la tabla `COORDINACION`
4. ✓ Creación de la tabla si no existe
5. ✓ Inserción de datos de prueba
6. ✓ Verificación final

### Paso 3: Volver al Dashboard
Una vez completado exitosamente, haz clic en el botón "Ir al Dashboard" o accede directamente a:
```
http://localhost/Gestion-sena/dashboard_sena/
```

## Estructura de la Tabla Creada
```sql
CREATE TABLE COORDINACION (
    coord_id INT AUTO_INCREMENT PRIMARY KEY,
    coord_descripcion TEXT NOT NULL,
    coord_nombre_coordinador VARCHAR(100),
    coord_correo VARCHAR(100),
    coord_password VARCHAR(255),
    CENTRO_FORMACION_cent_id INT,
    coord_fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    coord_activo TINYINT(1) DEFAULT 1
)
```

## Datos de Prueba
El script insertará 3 coordinaciones de ejemplo:
- Coordinación Académica
- Coordinación de Formación
- Coordinación de Proyectos

**Password por defecto:** `123456`

## Notas Importantes
- El script verifica que exista la tabla `CENTRO_FORMACION` antes de crear `COORDINACION`
- Si la tabla existe pero está dañada, el script la eliminará y recreará
- Todos los nombres de tabla en el sistema usan MAYÚSCULAS
