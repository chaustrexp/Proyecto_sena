# Changelog - Dashboard SENA

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
