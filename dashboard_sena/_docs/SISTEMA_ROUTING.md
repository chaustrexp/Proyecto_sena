# Sistema de Routing Centralizado

## 📋 Descripción

El sistema de routing permite usar URLs amigables en lugar de rutas directas a archivos PHP. Esto mejora la seguridad, mantenibilidad y experiencia del usuario.

## 🔧 Componentes

### 1. `.htaccess`
Configuración de Apache que redirige todas las peticiones al `routing.php`.

**Ubicación:** `dashboard_sena/.htaccess`

**Funciones:**
- Habilita mod_rewrite
- Redirige URLs amigables a routing.php
- Excluye archivos estáticos (CSS, JS, imágenes)
- Protege archivos sensibles (.md, .sql, .log)
- Habilita compresión y caché

### 2. `routing.php`
Archivo central que procesa las rutas y ejecuta los controladores correspondientes.

**Ubicación:** `dashboard_sena/routing.php`

**Funciones:**
- Parsea la URL solicitada
- Identifica módulo, acción e ID
- Carga el controlador correspondiente
- Ejecuta el método solicitado
- Maneja errores 404 y 500

## 🌐 Estructura de URLs

### Formato General
```
/Gestion-sena/dashboard_sena/{modulo}/{accion}/{id}
```

### Ejemplos de URLs

#### Dashboard
```
/Gestion-sena/dashboard_sena/
/Gestion-sena/dashboard_sena/dashboard
```

#### Asignaciones
```
/Gestion-sena/dashboard_sena/asignacion              → index (listar)
/Gestion-sena/dashboard_sena/asignacion/create       → crear (formulario)
/Gestion-sena/dashboard_sena/asignacion/store        → guardar (POST)
/Gestion-sena/dashboard_sena/asignacion/show/1       → ver detalle
/Gestion-sena/dashboard_sena/asignacion/edit/1       → editar (formulario)
/Gestion-sena/dashboard_sena/asignacion/update/1     → actualizar (POST)
/Gestion-sena/dashboard_sena/asignacion/delete/1     → eliminar
```

#### Fichas
```
/Gestion-sena/dashboard_sena/ficha                   → index
/Gestion-sena/dashboard_sena/ficha/create            → crear
/Gestion-sena/dashboard_sena/ficha/show/123          → ver ficha 123
/Gestion-sena/dashboard_sena/ficha/edit/123          → editar ficha 123
```

#### Instructores
```
/Gestion-sena/dashboard_sena/instructor              → index
/Gestion-sena/dashboard_sena/instructor/create       → crear
/Gestion-sena/dashboard_sena/instructor/show/5       → ver instructor 5
/Gestion-sena/dashboard_sena/instructor/edit/5       → editar instructor 5
```

#### Ambientes
```
/Gestion-sena/dashboard_sena/ambiente                → index
/Gestion-sena/dashboard_sena/ambiente/create         → crear
/Gestion-sena/dashboard_sena/ambiente/show/10        → ver ambiente 10
```

#### Programas
```
/Gestion-sena/dashboard_sena/programa                → index
/Gestion-sena/dashboard_sena/programa/create         → crear
/Gestion-sena/dashboard_sena/programa/show/2         → ver programa 2
```

#### Competencias
```
/Gestion-sena/dashboard_sena/competencia             → index
/Gestion-sena/dashboard_sena/competencia/create      → crear
/Gestion-sena/dashboard_sena/competencia/show/8      → ver competencia 8
```

## 🎯 Módulos Disponibles

| Módulo | Controlador | Acciones |
|--------|-------------|----------|
| dashboard | DashboardController | index |
| asignacion | AsignacionController | index, create, store, show, edit, update, delete |
| ficha | FichaController | index, create, store, show, edit, update, delete |
| instructor | InstructorController | index, create, store, show, edit, update, delete |
| ambiente | AmbienteController | index, create, store, show, edit, update, delete |
| programa | ProgramaController | index, create, store, show, edit, update, delete |
| competencia | CompetenciaController | index, create, store, show, edit, update, delete |

## 🔐 Seguridad

### Autenticación
Todas las rutas están protegidas con `check_auth.php`. Los usuarios deben estar autenticados para acceder.

### Validación
- Verifica que el módulo exista
- Verifica que la acción sea válida
- Verifica que el controlador y método existan
- Maneja errores con páginas personalizadas

### Archivos Protegidos
El `.htaccess` bloquea el acceso directo a:
- Archivos .md (documentación)
- Archivos .sql (scripts de base de datos)
- Archivos .log (logs del sistema)
- Archivos .json (configuración)

## ⚙️ Configuración

### Requisitos
1. Apache con mod_rewrite habilitado
2. PHP 7.4 o superior
3. Permisos de escritura en directorio de logs

### Verificar mod_rewrite
```bash
# En Linux/Mac
apache2ctl -M | grep rewrite

# En Windows (XAMPP)
# Abrir httpd.conf y verificar que esté descomentado:
LoadModule rewrite_module modules/mod_rewrite.so
```

### Ajustar RewriteBase
Si tu instalación está en una ruta diferente, edita `.htaccess`:

```apache
# Para instalación en raíz
RewriteBase /dashboard_sena/

# Para instalación en subcarpeta
RewriteBase /mi-carpeta/dashboard_sena/
```

## 🧪 Pruebas

### 1. Verificar que mod_rewrite funciona
Accede a: `http://localhost/Gestion-sena/dashboard_sena/dashboard`

Si ves el dashboard, el routing funciona correctamente.

### 2. Probar URLs amigables
```
✓ http://localhost/Gestion-sena/dashboard_sena/asignacion
✓ http://localhost/Gestion-sena/dashboard_sena/instructor/show/1
✓ http://localhost/Gestion-sena/dashboard_sena/ficha/edit/123
```

### 3. Verificar errores 404
Accede a una ruta inexistente:
```
http://localhost/Gestion-sena/dashboard_sena/modulo-inexistente
```

Deberías ver un mensaje de error.

## 🐛 Solución de Problemas

### Error 500 - Internal Server Error
**Causa:** mod_rewrite no está habilitado

**Solución:**
1. Habilitar mod_rewrite en Apache
2. Reiniciar Apache
3. Verificar que `.htaccess` esté en la carpeta correcta

### Página en blanco
**Causa:** Error en PHP

**Solución:**
1. Revisar logs de PHP: `dashboard_sena/logs/php_errors.log`
2. Verificar que todos los controladores existan
3. Verificar conexión a base de datos

### URLs no funcionan
**Causa:** RewriteBase incorrecto

**Solución:**
Ajustar `RewriteBase` en `.htaccess` según tu instalación.

### Archivos CSS/JS no cargan
**Causa:** Rutas incorrectas en las vistas

**Solución:**
Usar rutas absolutas en las vistas:
```php
<link rel="stylesheet" href="/Gestion-sena/dashboard_sena/assets/css/styles.css">
```

## 📝 Notas Importantes

1. **Compatibilidad con sistema antiguo:** Las rutas antiguas (`views/asignacion/index.php`) siguen funcionando para mantener compatibilidad.

2. **AJAX y APIs:** Los endpoints AJAX (como `get_asignacion.php`) NO pasan por el routing para mantener compatibilidad.

3. **Archivos estáticos:** CSS, JS, imágenes y otros archivos estáticos se sirven directamente sin pasar por routing.

4. **Logs:** Todos los errores se registran en `dashboard_sena/logs/php_errors.log`.

## 🚀 Próximos Pasos

1. Migrar todas las vistas para usar URLs amigables
2. Actualizar enlaces en el sidebar
3. Implementar middleware para permisos
4. Agregar caché de rutas para mejor rendimiento
5. Implementar versionado de API

## 📚 Referencias

- [Apache mod_rewrite](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)
- [PHP Routing](https://www.php.net/manual/es/features.http-auth.php)
- [MVC Pattern](https://www.php.net/manual/es/intro.pdo.php)
