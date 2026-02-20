# ✅ Checklist de Verificación del Sistema

## Fecha: 19 de Febrero de 2026

Este documento te ayuda a verificar manualmente que todo funciona correctamente.

---

## 🔍 Verificación Automática

### Herramienta de Diagnóstico
Abre en tu navegador:
```
http://localhost/Gestion-sena/dashboard_sena/_tests/diagnostico_sistema.php
```

Esta herramienta verifica automáticamente:
- ✅ Estructura de archivos
- ✅ Archivos críticos
- ✅ Conexión a base de datos
- ✅ Tablas de la BD
- ✅ Controladores
- ✅ Modelos
- ✅ Funciones helper
- ✅ Sistema de errores

**Resultado esperado:** 
- Salud del sistema: > 90%
- Errores: 0
- Mensaje: "Sistema Funcionando Correctamente"

---

## 🧪 Pruebas Manuales

### 1. Acceso al Dashboard

**URL:** `http://localhost/Gestion-sena/dashboard_sena/`

#### ✅ Verificar:
- [ ] La página carga sin errores
- [ ] No aparecen warnings en pantalla
- [ ] El diseño se ve correctamente
- [ ] Los colores SENA están presentes (verde #39A900)

#### 🎯 Qué deberías ver:
```
┌─────────────────────────────────────┐
│ Header: "Bienvenido al Sistema SENA"│
├─────────────────────────────────────┤
│ 6 Tarjetas con números:             │
│ - Programas                         │
│ - Fichas                            │
│ - Instructores                      │
│ - Ambientes                         │
│ - Asignaciones                      │
│ - Competencias                      │
├─────────────────────────────────────┤
│ Calendario mensual interactivo      │
├─────────────────────────────────────┤
│ Tabla "Últimas Asignaciones"        │
└─────────────────────────────────────┘
```

---

### 2. Tarjetas de Estadísticas

#### ✅ Verificar cada tarjeta:
- [ ] **Programas** - Muestra un número (puede ser 0)
- [ ] **Fichas** - Muestra un número
- [ ] **Instructores** - Muestra un número
- [ ] **Ambientes** - Muestra un número
- [ ] **Asignaciones** - Muestra un número
- [ ] **Competencias** - Muestra un número

#### 🎯 Interactividad:
- [ ] Al pasar el mouse, la tarjeta se eleva (hover effect)
- [ ] Los iconos se muestran correctamente
- [ ] Los colores son diferentes para cada tarjeta

---

### 3. Calendario de Asignaciones

#### ✅ Verificar funcionalidad:
- [ ] El calendario muestra el mes actual
- [ ] El día actual está resaltado en verde
- [ ] Los botones de navegación funcionan:
  - [ ] Botón "◀" (mes anterior)
  - [ ] Botón "▶" (mes siguiente)
  - [ ] Botón "Hoy" (volver al mes actual)

#### 🎯 Interactividad:
- [ ] Click en un día vacío → Modal "No hay asignaciones"
- [ ] Click en un día con asignaciones → Modal con lista
- [ ] Click en una asignación → Modal de detalles
- [ ] Los modales se cierran correctamente

#### 📋 Contenido del modal de día:
```
┌─────────────────────────────────────┐
│ Asignaciones del Día                │
│ [Fecha formateada]                  │
├─────────────────────────────────────┤
│ 📚 Ficha XXXXX                      │
│ Instructor: Nombre Apellido         │
│ Ambiente: Nombre Ambiente           │
├─────────────────────────────────────┤
│ [Cerrar] [Ver Todas]                │
└─────────────────────────────────────┘
```

---

### 4. Tabla de Asignaciones Recientes

#### ✅ Verificar columnas:
- [ ] Ficha
- [ ] Instructor
- [ ] Ambiente
- [ ] Fecha Inicio
- [ ] Fecha Fin
- [ ] Estado (con badge de color)

#### 🎯 Estados posibles:
- 🟢 **Activa** - Verde (#39A900)
- 🟡 **Pendiente** - Amarillo (#D97706)
- 🔴 **Finalizada** - Rojo (#DC2626)

#### 🎯 Interactividad:
- [ ] Hover en fila cambia el fondo a gris claro
- [ ] Botón "Ver todas" redirige a `/views/asignacion/index.php`

---

### 5. Modal de Detalle de Asignación

#### ✅ Verificar que muestra:
- [ ] ID de la asignación
- [ ] Estado con color
- [ ] Ficha (número grande en rosa)
- [ ] Instructor (nombre completo)
- [ ] Ambiente (si existe)
- [ ] Competencia (si existe)
- [ ] Hora inicio
- [ ] Hora fin
- [ ] Fechas formateadas

#### 🎯 Botones del modal:
- [ ] "Cerrar" - Cierra el modal
- [ ] "Ver Completo" - Va a `/views/asignacion/ver.php?id=X`
- [ ] "Editar" - Va a `/views/asignacion/editar.php?id=X`

---

### 6. Sistema de Navegación

#### ✅ Verificar sidebar:
- [ ] Logo SENA visible
- [ ] Menú de navegación funcional
- [ ] Links a todos los módulos:
  - [ ] Dashboard
  - [ ] Asignaciones
  - [ ] Fichas
  - [ ] Instructores
  - [ ] Ambientes
  - [ ] Programas
  - [ ] Competencias
  - [ ] Centros de Formación
  - [ ] Coordinaciones

#### 🎯 Cada link debe:
- [ ] Cambiar de color al hover
- [ ] Redirigir a la página correcta
- [ ] Mantener el estilo activo en la página actual

---

### 7. Formularios (Ejemplo: Crear Asignación)

**URL:** `http://localhost/Gestion-sena/dashboard_sena/views/asignacion/crear.php`

#### ✅ Verificar:
- [ ] El formulario carga sin errores
- [ ] No hay warnings "Undefined array key"
- [ ] Los selects se llenan con datos de la BD
- [ ] Los campos requeridos tienen asterisco (*)
- [ ] El botón "Guardar" funciona
- [ ] El botón "Cancelar" redirige correctamente

#### 🎯 Después de guardar:
- [ ] Redirige a la lista
- [ ] Muestra mensaje de éxito
- [ ] El nuevo registro aparece en la tabla

---

### 8. Formularios de Edición (Ejemplo: Editar Instructor)

**URL:** `http://localhost/Gestion-sena/dashboard_sena/views/instructor/editar.php?id=1`

#### ✅ Verificar:
- [ ] Los campos se llenan con datos existentes
- [ ] No hay warnings en los inputs
- [ ] Los valores se muestran correctamente escapados
- [ ] Los selects tienen la opción correcta seleccionada
- [ ] El botón "Actualizar" funciona
- [ ] Si el ID no existe, redirige con mensaje de error

---

### 9. Sistema de Errores

#### ✅ Probar manualmente:
1. **Acceder a un ID inexistente:**
   ```
   /views/instructor/editar.php?id=99999
   ```
   - [ ] Redirige a la lista
   - [ ] Muestra mensaje "Instructor no encontrado"
   - [ ] No muestra warnings

2. **Acceder sin ID:**
   ```
   /views/instructor/editar.php
   ```
   - [ ] Redirige a la lista
   - [ ] No muestra errores en pantalla

3. **Provocar error de BD (opcional):**
   - Detener MySQL
   - Recargar dashboard
   - [ ] Muestra valores en 0
   - [ ] No rompe la página
   - [ ] Registra error en `/logs/php_errors.log`

---

### 10. Seguridad

#### ✅ Verificar protección:
1. **Sin sesión activa:**
   - Cerrar sesión
   - Intentar acceder a `/index.php`
   - [ ] Redirige a `/auth/login.php`

2. **XSS Prevention:**
   - Los datos se muestran escapados
   - [ ] No se ejecuta HTML/JavaScript inyectado

3. **SQL Injection:**
   - Los modelos usan PDO con prepared statements
   - [ ] Las consultas son seguras

---

### 11. Logs del Sistema

#### ✅ Verificar logs:
**Ubicación:** `/dashboard_sena/logs/php_errors.log`

- [ ] El archivo existe
- [ ] Es escribible
- [ ] Está protegido con `.htaccess`
- [ ] Registra errores correctamente

#### 🎯 Probar:
```php
// Provocar un error intencional
trigger_error("Test de log", E_USER_WARNING);
```
- [ ] El error se registra en el log
- [ ] No se muestra en pantalla

---

### 12. Rendimiento

#### ✅ Tiempos de carga:
- [ ] Dashboard principal: < 2 segundos
- [ ] Listados: < 1 segundo
- [ ] Formularios: < 1 segundo
- [ ] Modales AJAX: < 500ms

#### 🎯 Herramientas:
- Abrir DevTools (F12)
- Pestaña "Network"
- Recargar página
- Verificar tiempos

---

### 13. Responsive Design

#### ✅ Probar en diferentes tamaños:
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

#### 🎯 Verificar:
- [ ] El layout se adapta
- [ ] Los textos son legibles
- [ ] Los botones son clickeables
- [ ] El menú funciona en móvil

---

### 14. Navegadores

#### ✅ Probar en:
- [ ] Chrome/Edge (Chromium)
- [ ] Firefox
- [ ] Safari (si disponible)

#### 🎯 Verificar:
- [ ] Diseño consistente
- [ ] JavaScript funciona
- [ ] Modales se abren
- [ ] Calendario funciona

---

## 📊 Resumen de Verificación

### Puntuación
Cuenta los checks completados:

- **Excelente:** 90-100% ✅
- **Bueno:** 75-89% ⚠️
- **Necesita mejoras:** < 75% ❌

### Checklist Rápido (Mínimo Viable)

Los siguientes puntos son CRÍTICOS:

- [ ] Dashboard carga sin errores
- [ ] Tarjetas muestran números
- [ ] Calendario es interactivo
- [ ] Tabla muestra asignaciones
- [ ] Formularios funcionan sin warnings
- [ ] Sistema de autenticación activo
- [ ] Base de datos conectada
- [ ] Logs funcionando

**Si todos estos están ✅, el sistema funciona correctamente.**

---

## 🐛 Problemas Comunes y Soluciones

### Problema 1: "Undefined array key"
**Solución:** Verificar que se use `safe()` o `safeHtml()` en lugar de acceso directo.

### Problema 2: Calendario no carga
**Solución:** Verificar que `asignacionesCalendario` tenga datos en formato JSON.

### Problema 3: Modales no se abren
**Solución:** Verificar que Lucide icons esté cargado y JavaScript no tenga errores.

### Problema 4: Estilos rotos
**Solución:** Verificar rutas de CSS y que los archivos existan.

### Problema 5: Error de conexión BD
**Solución:** Verificar credenciales en `conexion.php` y que MySQL esté corriendo.

---

## 📞 Soporte

Si encuentras problemas:

1. Ejecuta el diagnóstico automático
2. Revisa los logs en `/logs/php_errors.log`
3. Verifica la consola del navegador (F12)
4. Consulta la documentación en `/_docs/`

---

**Última actualización:** 19 de Febrero de 2026  
**Versión del sistema:** 2.0.0  
**Estado:** ✅ Producción
