# Changelog - Dashboard SENA

## [1.3.0] - 2026-02-20

### 🆕 Nuevas Funcionalidades

#### Estadísticas de Asignaciones en Dashboard
- ✅ **Asignaciones Activas**: Contador de asignaciones en curso
- ✅ **Asignaciones Finalizadas**: Contador de asignaciones completadas
- ✅ **Asignaciones No Activas**: Contador de asignaciones pendientes
- ✅ Tarjetas visuales con iconos y colores distintivos
- ✅ Cálculo automático basado en fechas de inicio y fin

#### Controlador de Fichas Completamente Funcional
- ✅ **CRUD Completo**: Crear, leer, actualizar y eliminar fichas
- ✅ **Validación Robusta**: Validación de campos requeridos y formatos
- ✅ **Estadísticas**: Fichas totales, activas y finalizadas
- ✅ **Estado Automático**: Cálculo de estado basado en fechas (Activa/Finalizada/Pendiente)
- ✅ **Formularios Completos**: Todos los campos necesarios con selectores

#### Corrección Error 500 en Instru_Competencia
- ✅ **Validación de Integridad Referencial**: Verifica que combinaciones Programa+Competencia existan
- ✅ **Modal Inteligente**: Solo muestra combinaciones válidas de COMPETxPROGRAMA
- ✅ **Mensajes Claros**: Alertas informativas cuando falta configuración
- ✅ **Manejo de Errores**: Try-catch robusto en todas las operaciones

### 🔧 Mejoras

#### Modelo AsignacionModel.php
- ✅ Método `countActivas()`: Cuenta asignaciones en curso
- ✅ Método `countFinalizadas()`: Cuenta asignaciones completadas
- ✅ Método `countNoActivas()`: Cuenta asignaciones pendientes
- ✅ Eliminado método `count()` duplicado

#### Modelo FichaModel.php
- ✅ Soporte completo para campo `fich_numero`
- ✅ Consultas mejoradas con todos los JOINs necesarios
- ✅ Flexibilidad en parámetros (acepta múltiples formatos de nombres)
- ✅ Métodos `create()` y `update()` con validación

#### Controlador FichaController.php
- ✅ Agregados modelos de Instructor y Coordinación
- ✅ Validación completa de datos (campos requeridos, formatos, fechas)
- ✅ Cálculo de estadísticas (fichas activas vs finalizadas)
- ✅ Manejo robusto de errores con try-catch
- ✅ Mensajes de sesión para feedback al usuario

#### Controlador DashboardController.php
- ✅ Variables para asignaciones activas, finalizadas y no activas
- ✅ Manejo de errores mejorado con valores por defecto

#### Vista views/instru_competencia/index.php
- ✅ Validación antes de insertar en base de datos
- ✅ Carga de datos de COMPETxPROGRAMA
- ✅ Modal con selector combinado Programa+Competencia
- ✅ Alertas informativas y de error
- ✅ Prevención de selecciones inválidas

#### Vista views/ficha/index.php
- ✅ Columna "Número de Ficha" con formato de 8 dígitos
- ✅ Columna "Estado" con badges de colores
- ✅ Estadísticas: Total, Activas, Finalizadas
- ✅ Cálculo automático del estado basado en fechas

#### Vista views/ficha/crear.php (Nueva)
- ✅ Formulario completo con todos los campos
- ✅ Validación en tiempo real con mensajes de error
- ✅ Selectores para Programa, Instructor, Jornada, Coordinación
- ✅ Campos de fecha con validación
- ✅ Diseño moderno y consistente

#### Vista views/dashboard/stats_cards.php
- ✅ Tarjeta "Total Asignaciones" con badge de activas
- ✅ Tarjeta "Asignaciones Finalizadas" con icono check verde
- ✅ Tarjeta "Asignaciones No Activas" con icono reloj amarillo

### 📁 Archivos Nuevos

#### Tests
- `_tests/test_asignaciones_estadisticas.php` - Test completo de estadísticas de asignaciones
- `_tests/diagnostico_instru_competencia_completo.php` - Diagnóstico de integridad referencial

#### Documentación
- `_docs/SOLUCION_ERROR_INSTRU_COMPETENCIA.md` - Guía de solución del error 500
- `_docs/RESUMEN_CORRECCION_FINAL.md` - Resumen ejecutivo de correcciones

#### Vistas
- `views/ficha/crear.php` - Formulario de creación de fichas (reescrito)

### 📁 Archivos Modificados

#### Modelos
- `model/AsignacionModel.php` - Métodos de conteo de estadísticas
- `model/FichaModel.php` - Soporte completo para fich_numero

#### Controladores
- `controller/FichaController.php` - Completamente funcional con validación
- `controller/DashboardController.php` - Estadísticas de asignaciones

#### Vistas
- `views/ficha/index.php` - Número de ficha y estado
- `views/instru_competencia/index.php` - Validación y modal mejorado
- `views/dashboard/stats_cards.php` - Nuevas tarjetas de estadísticas

### 🐛 Correcciones

#### Error 500 en Instru_Competencia
- ✅ **Causa identificada**: Restricción de clave foránea compuesta en COMPETxPROGRAMA
- ✅ **Solución**: Validación antes de insertar + modal con opciones válidas
- ✅ **Prevención**: Solo se muestran combinaciones que existen en la BD
- ✅ **Mensajes**: Alertas claras cuando falta configuración

#### Método count() Duplicado
- ✅ Eliminado método `count()` duplicado en AsignacionModel.php
- ✅ Mantenida una sola versión funcional

### 📊 Estadísticas de esta Versión

- **Archivos nuevos**: 4
- **Archivos modificados**: 8
- **Líneas de código agregadas**: ~2,800
- **Funcionalidades nuevas**: 3 principales
- **Bugs corregidos**: 2

### 🚀 Próximas Mejoras

- [ ] Vista de edición de fichas completamente funcional
- [ ] Vista de detalle de fichas
- [ ] Filtros avanzados en listado de fichas
- [ ] Exportación de fichas a Excel/PDF
- [ ] Validación de números de ficha únicos en tiempo real

### 📝 Notas de Actualización

**Para actualizar:**
1. Hacer pull del repositorio
2. Ejecutar script `_scripts/agregar_campo_fich_numero.php` si aún no lo has hecho
3. Verificar que COMPETxPROGRAMA tenga datos (ir a "Competencias por Programa")
4. Limpiar caché del navegador (Ctrl + Shift + Delete)
5. Recargar con Ctrl + F5

**Importante:**
- El campo `fich_numero` debe agregarse a la base de datos antes de usar las fichas
- La tabla COMPETxPROGRAMA debe tener datos antes de asignar competencias a instructores

**Compatibilidad:**
- PHP 7.4+
- MySQL 5.7+
- Apache con mod_rewrite

---

## [1.2.2] - 2026-02-20

### 🆕 Nuevas Funcionalidades

#### Campo Número de Ficha
- ✅ **Nuevo campo `fich_numero`**: Almacena el número real de la ficha (ej: 3115418)
- ✅ **Validación UNIQUE**: No permite números de ficha duplicados
- ✅ **Formularios actualizados**: Campos para ingresar/editar número de ficha
- ✅ **Visualización mejorada**: Muestra números completos con formato de 8 dígitos

### 🔧 Mejoras

#### Modelo FichaModel.php
- ✅ Método `create()` actualizado para incluir `fich_numero`
- ✅ Método `update()` actualizado para incluir `fich_numero`

#### Modelo AsignacionModel.php
- ✅ Todas las consultas ahora usan `fich_numero` en lugar de `fich_id`
- ✅ Métodos actualizados: `getAll()`, `getById()`, `getRecent()`, `getForCalendar()`

#### Formularios
- ✅ `views/ficha/crear.php`: Campo número de ficha agregado
- ✅ `views/ficha/editar.php`: Completamente actualizado con todos los campos correctos

### 📁 Archivos Nuevos
- `_database/agregar_campo_fich_numero.sql` - Script SQL para agregar el campo
- `_docs/CAMPO_NUMERO_FICHA.md` - Documentación completa

### 📁 Archivos Modificados
- `model/FichaModel.php` - Métodos create() y update()
- `model/AsignacionModel.php` - Todas las consultas
- `views/ficha/crear.php` - Campo fich_numero
- `views/ficha/editar.php` - Formulario completo

---

## [1.2.1] - 2026-02-20

### 🐛 Correcciones

#### Tabla de Asignaciones
- ✅ **Campo Programa**: Agregado JOIN con tabla PROGRAMA para mostrar nombre del programa
- ✅ **Columna ID (Ficha)**: Muestra número de ficha con formato de 8 dígitos
- ✅ **Columna Programa**: Muestra nombre completo del programa asociado a la ficha
- ✅ Actualizado `AsignacionModel.php` con JOIN adicional: FICHA → PROGRAMA
- ✅ Métodos actualizados: `getAll()`, `getById()`, `getRecent()`, `getForCalendar()`

### 📁 Archivos Modificados
- `model/AsignacionModel.php` - Agregado JOIN con PROGRAMA en todas las consultas

### 📁 Archivos Nuevos
- `_tests/test_programa_asignacion.php` - Script de verificación
- `_docs/CORRECCION_PROGRAMA_ASIGNACION.md` - Documentación de la corrección

---

## [1.2.0] - 2026-02-19

### 🎉 Nuevas Funcionalidades

#### Header Funcional Completo
- ✅ **Búsqueda Global**: Búsqueda en tiempo real en instructores, fichas, programas, ambientes y asignaciones
- ✅ **Sistema de Notificaciones**: Dropdown con notificaciones, contador de no leídas, marcar como leídas
- ✅ **Menú de Acciones Rápidas**: Botón "+" con acceso directo a formularios de creación
- ✅ **Centro de Ayuda**: Modal con documentación, FAQs y soporte
- ✅ **Títulos Dinámicos**: El título del header cambia según la sección actual

#### APIs Implementadas
- ✅ `/api/search.php`: Búsqueda global en múltiples tablas
- ✅ `/api/notifications.php`: Gestión de notificaciones (GET/POST)
- ✅ Sistema de notificaciones con tabla SQL opcional

#### Sistema de Routing
- ✅ URLs amigables y SEO-friendly
- ✅ Sistema centralizado en `routing.php`
- ✅ Soporte para 7 módulos principales
- ✅ Manejo de errores mejorado

### 🎨 Mejoras de Diseño

#### Tarjetas de Estadísticas
- ✅ Diseño horizontal más balanceado
- ✅ Iconos más grandes con gradientes
- ✅ Números más ligeros y legibles
- ✅ Badge de "vigentes" rediseñado

#### Header
- ✅ Diseño compacto (50px altura)
- ✅ Elementos centrados con flexbox
- ✅ Responsive para móviles
- ✅ Colores SENA (#e8f5e9)

### 📁 Archivos Nuevos

#### JavaScript
- `assets/js/header-functions.js` - Funcionalidades del header

#### APIs
- `api/search.php` - Búsqueda global
- `api/notifications.php` - Gestión de notificaciones

#### Base de Datos
- `_database/tabla_notificaciones.sql` - Tabla opcional de notificaciones

#### Helpers
- `helpers/page_titles.php` - Sistema de títulos dinámicos

#### Tests
- `_tests/test_header_visual.html` - Test visual del header
- `_tests/test_page_titles.php` - Test de títulos dinámicos
- `_tests/test_routing_completo.php` - Test completo de routing

#### Documentación
- `_docs/HEADER_FUNCIONAL_COMPLETO.md` - Guía completa del header
- `_docs/GUIA_HEADER_CON_ACCIONES.md` - Guía de acciones del header
- `_docs/SOLUCION_TITULO_HEADER.md` - Solución de títulos
- `_docs/DIAGNOSTICO_TITULO_HEADER.md` - Diagnóstico de problemas
- `_docs/VERIFICACION_ROUTING.md` - Verificación del routing
- `_docs/RESUMEN_HEADER_MEJORADO.md` - Resumen ejecutivo

#### Demos
- `_html_demos/PREVIEW_HEADER_CON_ACCIONES.html` - Preview del header

### 🔧 Archivos Modificados

#### Vistas
- `views/layout/header.php` - Header con nuevas funcionalidades
- `views/layout/footer.php` - Carga de header-functions.js
- `views/dashboard/stats_cards.php` - Diseño mejorado de tarjetas

#### Controladores
- `controller/DashboardController.php` - Título definido antes del header

#### Estilos
- `assets/css/styles.css` - Estilos del header, dropdowns, modales
- `assets/css/theme-enhanced.css` - Estilos responsive

#### Configuración
- `.htaccess` - Incluye carpeta `/api/` en exclusiones

### 🐛 Correcciones

- ✅ Título del header ahora visible (conflictos CSS resueltos)
- ✅ Espaciado del main-content ajustado (margin-top: 50px)
- ✅ Consolidación de estilos CSS (eliminados duplicados)
- ✅ Sistema de routing funcional con URLs amigables

### 📊 Estadísticas

- **Archivos nuevos**: 15
- **Archivos modificados**: 8
- **Líneas de código agregadas**: ~3,500
- **Funcionalidades nuevas**: 4 principales
- **APIs implementadas**: 2

### 🚀 Próximas Mejoras

- [ ] Notificaciones en tiempo real (WebSockets)
- [ ] Búsqueda avanzada con filtros
- [ ] Chat de soporte en vivo
- [ ] Dashboard personalizable por usuario
- [ ] Exportación de reportes (PDF, Excel)

### 📝 Notas de Actualización

**Para actualizar:**
1. Hacer pull del repositorio
2. Limpiar caché del navegador (Ctrl + Shift + Delete)
3. Recargar con Ctrl + F5
4. (Opcional) Ejecutar `_database/tabla_notificaciones.sql` para notificaciones reales

**Compatibilidad:**
- PHP 7.4+
- MySQL 5.7+
- Apache con mod_rewrite

**Dependencias:**
- Lucide Icons (CDN)
- No requiere npm/composer

---

**Desarrollado por:** Equipo Dashboard SENA
**Fecha:** 19 de Febrero, 2026
**Versión:** 1.2.0
