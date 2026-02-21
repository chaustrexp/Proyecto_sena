# Calendario con Vistas Múltiples

## Descripción

Se ha implementado un sistema de calendario con tres vistas diferentes: Mes, Semana y Día.

## Características

### Vista Mensual (Por defecto)
- Muestra el mes completo en formato de cuadrícula 7x5/6
- Resalta el día actual con borde verde
- Muestra hasta 2 asignaciones por día
- Indica si hay más asignaciones con "+X más"

### Vista Semanal
- Muestra 7 días de la semana actual
- Columnas más anchas para ver más detalles
- Muestra todas las asignaciones del día con hora de inicio
- Navegación por semanas (anterior/siguiente)

### Vista Diaria
- Muestra un solo día con todas sus asignaciones
- Asignaciones agrupadas por hora
- Vista detallada con toda la información
- Navegación día por día

## Navegación

### Botones de Vista
- **Mes**: Vista mensual completa
- **Semana**: Vista de 7 días
- **Día**: Vista de un solo día

### Controles de Navegación
- **← (Anterior)**: 
  - Vista Mes: Mes anterior
  - Vista Semana: Semana anterior
  - Vista Día: Día anterior
- **→ (Siguiente)**:
  - Vista Mes: Mes siguiente
  - Vista Semana: Semana siguiente
  - Vista Día: Día siguiente
- **Hoy**: Vuelve a la fecha actual en cualquier vista

## Interacciones

### Click en Día
- Abre modal con todas las asignaciones del día
- Muestra información resumida de cada asignación
- Permite crear nueva asignación si no hay ninguna

### Click en Asignación
- Abre modal con detalles completos
- Muestra estado (Activa/Pendiente/Finalizada)
- Botones para ver completo o editar

## Colores SENA

- **Verde Principal**: #39A900
- **Verde Secundario**: #007832
- **Fondo Activo**: #E8F5E8
- **Borde Activo**: #39A900

## Archivos Modificados

1. `dashboard_sena/views/dashboard/calendar.php`
   - Agregados botones de vista (Mes/Semana/Día)
   - Actualizado header con selector de vista

2. `dashboard_sena/views/dashboard/scripts.php`
   - Función `cambiarVista(vista)`: Cambia entre vistas
   - Función `renderWeekView()`: Renderiza vista semanal
   - Función `renderDayView()`: Renderiza vista diaria
   - Actualizada navegación para soportar las 3 vistas

## Uso

1. Por defecto se muestra la vista mensual
2. Click en "Semana" para ver la semana actual
3. Click en "Día" para ver el día actual
4. Usa las flechas para navegar
5. Click en "Hoy" para volver a la fecha actual

## Responsive

- Las vistas se adaptan al tamaño de pantalla
- En móviles, la vista diaria es la más recomendada
- La vista semanal puede requerir scroll horizontal en pantallas pequeñas

---

**Fecha de Implementación**: 20 de Febrero de 2026
**Versión**: 1.3.1
