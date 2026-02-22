# Actualización de URLs para Incluir /index

## Cambios Realizados

### 1. routing.php
Agregada redirección automática cuando no se especifica acción:
```php
// Si solo viene el módulo sin acción, redirigir a módulo/index
if (empty($parts[1]) && !empty($module) && $module !== 'dashboard') {
    header("Location: {$basePath}{$module}/index");
    exit;
}
```

### 2. index.php
Actualizado para redirigir a `/dashboard/index`:
```php
header('Location: /Gestion-sena/dashboard_sena/dashboard/index');
```

### 3. sidebar.php
Todos los enlaces actualizados para incluir `/index`:
- `/dashboard` → `/dashboard/index`
- `/programa` → `/programa/index`
- `/ficha` → `/ficha/index`
- etc.

### 4. Vistas - Enlaces de Retorno
Necesitan actualizarse todos los enlaces que apuntan al listado:

**Patrón a buscar:**
```
href="/Gestion-sena/dashboard_sena/{modulo}"
```

**Reemplazar por:**
```
href="/Gestion-sena/dashboard_sena/{modulo}/index"
```

## Módulos Afectados

1. ambiente
2. asignacion
3. centro_formacion
4. competencia
5. competencia_programa
6. coordinacion
7. dashboard
8. detalle_asignacion
9. ficha
10. instru_competencia
11. instructor
12. programa
13. sede
14. titulo_programa

## Formato de URLs

### Antes
```
/dashboard_sena/ambiente
/dashboard_sena/ambiente/crear
/dashboard_sena/ambiente/editar/5
```

### Ahora
```
/dashboard_sena/ambiente/index
/dashboard_sena/ambiente/crear
/dashboard_sena/ambiente/editar/5
```

## Comportamiento

1. Si accedes a `/ambiente` → Redirige automáticamente a `/ambiente/index`
2. Si accedes a `/ambiente/index` → Muestra el listado
3. Si accedes a `/ambiente/crear` → Muestra el formulario de creación
4. Si accedes a `/ambiente/editar/5` → Muestra el formulario de edición del ID 5

## Ventajas

1. URLs más explícitas y claras
2. Consistencia en toda la aplicación
3. Fácil de entender qué acción se está ejecutando
4. Mejor para SEO y logs
5. Más fácil de debuggear
