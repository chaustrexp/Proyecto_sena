# Solución: Error al Acceder Directamente a las Vistas

## Problema Identificado

El error "Error del Servidor" ocurre porque estás accediendo directamente a las vistas PHP en lugar de usar el sistema de routing.

### ❌ Acceso Incorrecto (Causa el Error)
```
http://localhost/Gestion-sena/dashboard_sena/views/asignacion/crear.php
```

### ✅ Acceso Correcto (A través del Routing)
```
http://localhost/Gestion-sena/dashboard_sena/asignacion/create
```

## ¿Por Qué Ocurre el Error?

Cuando accedes directamente a `/views/asignacion/crear.php`:

1. **Falta el helper de funciones**: La vista usa funciones como `safeHtml()` que están en `helpers/functions.php`
2. **Rutas relativas incorrectas**: Los `require_once` en la vista usan rutas relativas que no funcionan cuando se accede directamente
3. **No pasa por el controlador**: El controlador prepara los datos necesarios antes de mostrar la vista

## Errores en el Log

Los errores que ves en `logs/php_errors.log` son causados por:

```
[2026-02-21 01:35:05] Error [2]: Undefined array key "id"
[2026-02-21 01:35:05] Error [2]: Undefined array key "ASIGNACION_ASIG_ID"
[2026-02-20 03:28:23] Error [2]: Undefined variable $conn
```

Estos ocurren porque:
- Las vistas esperan datos que el controlador debería proporcionar
- Las funciones helper no están cargadas
- La tabla `coordinacion` no existe en la base de datos

## Solución Inmediata

### 1. Usa las Rutas Correctas del Sistema

| Módulo | URL Correcta | Acción |
|--------|-------------|--------|
| Dashboard | `/dashboard_sena/` | Ver dashboard |
| Asignaciones | `/dashboard_sena/asignacion` | Listar asignaciones |
| Crear Asignación | `/dashboard_sena/asignacion/create` | Formulario de creación |
| Editar Asignación | `/dashboard_sena/asignacion/edit/1` | Editar asignación ID 1 |
| Ver Asignación | `/dashboard_sena/asignacion/show/1` | Ver detalles ID 1 |
| Fichas | `/dashboard_sena/ficha` | Listar fichas |
| Crear Ficha | `/dashboard_sena/ficha/create` | Formulario de creación |
| Instructores | `/dashboard_sena/instructor` | Listar instructores |
| Ambientes | `/dashboard_sena/ambiente` | Listar ambientes |
| Programas | `/dashboard_sena/programa` | Listar programas |
| Competencias | `/dashboard_sena/competencia` | Listar competencias |

### 2. Navega Desde el Menú Lateral

El sidebar tiene todos los enlaces correctos. Usa siempre el menú de navegación en lugar de escribir URLs manualmente.

### 3. Verifica la Tabla `coordinacion`

El sistema intenta acceder a una tabla `coordinacion` que no existe. Necesitas:

**Opción A: Crear la tabla**
```sql
CREATE TABLE IF NOT EXISTS coordinacion (
    coord_id INT PRIMARY KEY AUTO_INCREMENT,
    coord_nombre VARCHAR(100) NOT NULL,
    coord_descripcion TEXT,
    centro_formacion_id INT,
    FOREIGN KEY (centro_formacion_id) REFERENCES centro_formacion(centro_id)
);
```

**Opción B: Eliminar referencias** (si no la necesitas)
- Revisar los modelos que usan `coordinacion`
- Comentar o eliminar esas referencias

## Correcciones Aplicadas

### ✅ 1. Routing Mejorado
- Agregado `default_action` para el dashboard
- Redirige automáticamente acciones inválidas a `index`

### ✅ 2. Helper Functions Incluido
- Agregado `require_once` de `helpers/functions.php` en `views/asignacion/crear.php`
- Esto previene errores de funciones no definidas

### ⚠️ 3. Pendiente: Tabla coordinacion
- Necesitas decidir si crear la tabla o eliminar las referencias

## Cómo Probar la Solución

1. **Accede al dashboard principal:**
   ```
   http://localhost/Gestion-sena/dashboard_sena/
   ```

2. **Desde el menú lateral, haz clic en "Asignaciones"**

3. **Luego haz clic en el botón "Nueva Asignación"**

4. **La URL debe ser:**
   ```
   http://localhost/Gestion-sena/dashboard_sena/asignacion/create
   ```

## Estructura del Sistema de Routing

```
URL: /dashboard_sena/asignacion/create
     └─ basePath ─┘ └─ module ─┘ └action┘

1. routing.php recibe la petición
2. Identifica el módulo: "asignacion"
3. Identifica la acción: "create"
4. Carga: controller/AsignacionController.php
5. Ejecuta: AsignacionController->create()
6. El controlador carga la vista: views/asignacion/crear.php
```

## Recomendaciones

1. **Nunca accedas directamente a archivos en `/views/`**
2. **Usa siempre el sistema de routing**
3. **Navega desde el menú lateral**
4. **Si necesitas un enlace directo, usa el formato: `/dashboard_sena/modulo/accion`**
5. **Revisa el archivo `routing.php` para ver todas las rutas disponibles**

## Próximos Pasos

1. ✅ Probar acceso a través del routing
2. ⚠️ Decidir sobre la tabla `coordinacion`
3. ⚠️ Limpiar el log de errores: `logs/php_errors.log`
4. ✅ Verificar que todas las funcionalidades funcionan correctamente

---

**Fecha:** 21 de febrero de 2026  
**Estado:** Routing corregido, pendiente decisión sobre tabla coordinacion
