# 📋 Instrucciones: Campo Número de Ficha

## 🎯 ¿Qué se implementó?

Se agregó un campo `fich_numero` a la tabla FICHA para almacenar números completos de ficha (ej: 3115418) en lugar de usar solo el ID autoincremental.

---

## ⚡ Pasos para Implementar (5 minutos)

### 1️⃣ Ejecutar Script SQL

Abre phpMyAdmin: `http://localhost/phpmyadmin`

```sql
USE progsena;

ALTER TABLE `FICHA` 
ADD COLUMN `fich_numero` INT NOT NULL DEFAULT 0 AFTER `fich_id`;

ALTER TABLE `FICHA` 
ADD UNIQUE INDEX `fich_numero_UNIQUE` (`fich_numero` ASC);

UPDATE `FICHA` SET `fich_numero` = `fich_id` WHERE `fich_numero` = 0;
```

### 2️⃣ Verificar

Ejecuta desde la terminal:

```bash
cd C:\xampp\htdocs\Gestion-sena\dashboard_sena
php _tests/test_campo_fich_numero.php
```

Deberías ver: `🎉 ¡TODO ESTÁ FUNCIONANDO CORRECTAMENTE!`

### 3️⃣ Actualizar Fichas Existentes (Si las tienes)

```sql
-- Ver fichas actuales
SELECT fich_id, fich_numero FROM FICHA;

-- Actualizar con números reales
UPDATE FICHA SET fich_numero = 3115418 WHERE fich_id = 2;
UPDATE FICHA SET fich_numero = 3115419 WHERE fich_id = 3;
```

### 4️⃣ Probar

1. Crea una nueva ficha: `http://localhost/Gestion-sena/dashboard_sena/views/ficha/crear.php`
2. Ingresa un número de ficha: `3115418`
3. Verifica en asignaciones que se muestre correctamente

---

## 📚 Documentación Disponible

| Archivo | Descripción |
|---------|-------------|
| `_docs/GUIA_RAPIDA_CAMPO_FICHA.md` | Guía paso a paso completa |
| `_docs/CAMPO_NUMERO_FICHA.md` | Documentación técnica detallada |
| `_database/README_MIGRACION.md` | Instrucciones de migración |
| `_database/agregar_campo_fich_numero.sql` | Script SQL completo |
| `_tests/test_campo_fich_numero.php` | Script de verificación |

---

## ✅ Checklist Rápido

- [ ] Script SQL ejecutado
- [ ] Verificación pasada (test_campo_fich_numero.php)
- [ ] Fichas existentes actualizadas
- [ ] Probado crear nueva ficha
- [ ] Verificado en tabla de asignaciones

---

## 🆘 ¿Problemas?

### Error: "Unknown column 'fich_numero'"
→ Ejecuta el script SQL del Paso 1

### La tabla muestra números pequeños (2, 3)
→ Actualiza las fichas existentes (Paso 3)

### Error: "Duplicate entry"
→ Ya existe una ficha con ese número, usa otro

---

## 📞 Ayuda Adicional

1. Revisa el log: `logs/php_errors.log`
2. Ejecuta el script de verificación
3. Consulta la documentación completa

---

## 🎉 Resultado Final

Después de completar:
- ✅ Números de ficha completos (3115418)
- ✅ Sin duplicados (validación UNIQUE)
- ✅ Formato consistente (03115418)
- ✅ Integración completa en el sistema
