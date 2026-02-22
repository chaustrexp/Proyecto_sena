# 📤 Guía Paso a Paso para Subir a GitHub

## 🎯 Repositorio Destino
```
https://github.com/chaustrexp/Proyecto_sena.git
```

---

## 📋 Pasos a Seguir

### Paso 1: Verificar el repositorio actual

Abre tu terminal (PowerShell o CMD) en la carpeta del proyecto y ejecuta:

```bash
git remote -v
```

**Resultado esperado:**
```
origin  https://github.com/chaustrexp/mvc_proyecto_definitivo.git (fetch)
origin  https://github.com/chaustrexp/mvc_proyecto_definitivo.git (push)
```

---

### Paso 2: Cambiar el repositorio remoto

Ejecuta este comando para cambiar la URL del repositorio:

```bash
git remote set-url origin https://github.com/chaustrexp/Proyecto_sena.git
```

---

### Paso 3: Verificar que el cambio se hizo correctamente

```bash
git remote -v
```

**Resultado esperado:**
```
origin  https://github.com/chaustrexp/Proyecto_sena.git (fetch)
origin  https://github.com/chaustrexp/Proyecto_sena.git (push)
```

---

### Paso 4: Ver el estado de los archivos

```bash
git status
```

**Resultado esperado:**
- Verás que ya tienes un commit hecho (el que hicimos antes)
- Debería decir "Your branch is ahead of 'origin/main' by 1 commit"

---

### Paso 5: Subir los cambios al nuevo repositorio

```bash
git push origin main
```

**Si te pide credenciales:**
- Usuario: tu usuario de GitHub
- Contraseña: tu token de acceso personal (no tu contraseña normal)

**Si no tienes token, créalo aquí:**
https://github.com/settings/tokens

---

### Paso 6: Verificar que se subió correctamente

Abre tu navegador y ve a:
```
https://github.com/chaustrexp/Proyecto_sena
```

Deberías ver:
- ✅ Todos los archivos actualizados
- ✅ El commit con el mensaje "feat: Sistema completo de URLs limpias..."
- ✅ La fecha y hora del commit

---

## 🔄 Comandos Completos (Copia y Pega)

Si quieres hacerlo todo de una vez, copia estos comandos:

```bash
# 1. Ver repositorio actual
git remote -v

# 2. Cambiar a nuevo repositorio
git remote set-url origin https://github.com/chaustrexp/Proyecto_sena.git

# 3. Verificar el cambio
git remote -v

# 4. Ver estado
git status

# 5. Subir cambios
git push origin main
```

---

## ⚠️ Posibles Problemas y Soluciones

### Problema 1: "Repository not found"
**Solución:** Verifica que el repositorio existe en GitHub y que tienes acceso.

### Problema 2: "Authentication failed"
**Solución:** 
1. Ve a https://github.com/settings/tokens
2. Crea un nuevo token (classic)
3. Dale permisos de "repo"
4. Copia el token
5. Úsalo como contraseña cuando Git te lo pida

### Problema 3: "Updates were rejected"
**Solución:**
```bash
# Primero descarga los cambios del repositorio
git pull origin main --allow-unrelated-histories

# Luego sube tus cambios
git push origin main
```

### Problema 4: "Branch 'main' doesn't exist"
**Solución:**
```bash
# Crea la rama main
git branch -M main

# Sube los cambios
git push -u origin main
```

---

## 📝 Notas Importantes

1. **Token de GitHub:** Si no tienes un token, créalo antes de hacer push
2. **Permisos:** Asegúrate de tener permisos de escritura en el repositorio
3. **Rama:** El repositorio debe tener una rama "main" o cambiar a "master"
4. **Internet:** Necesitas conexión a internet para hacer push

---

## ✅ Verificación Final

Después de hacer push, verifica:

1. **En GitHub:**
   - [ ] Los archivos están actualizados
   - [ ] El commit aparece en el historial
   - [ ] La fecha es correcta

2. **En tu terminal:**
   ```bash
   git log --oneline -5
   ```
   Deberías ver tu commit más reciente

3. **Estado limpio:**
   ```bash
   git status
   ```
   Debería decir "nothing to commit, working tree clean"

---

## 🎉 ¡Listo!

Si todos los pasos funcionaron, tus cambios están en:
```
https://github.com/chaustrexp/Proyecto_sena
```

---

## 📞 Ayuda Adicional

Si tienes problemas, revisa:
- Estado de Git: `git status`
- Historial: `git log --oneline`
- Repositorio remoto: `git remote -v`
- Rama actual: `git branch`

---

**Creado:** Febrero 2024  
**Versión:** 2.1.0  
**Para:** Sistema de Gestión SENA
