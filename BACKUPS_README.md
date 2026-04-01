# 🔐 SISTEMA DE BACKUPS AUTOMÁTICOS - REFERENCIA RÁPIDA

## ✅ ESTADO DEL SISTEMA

```
✔️ Comando de backup: php artisan db:backup
✔️ Scheduler:        php artisan schedule:run
✔️ Verificación:     php artisan backup:verify
✔️ Directorio:       storage/backups/
✔️ Retención:        7 días (automático)
✔️ Hora:             00:00 (media noche)
✔️ Zona horaria:     America/La_Paz
```

---

## 🚀 EN TU HOSTING (INSTRUCCIONES RÁPIDAS)

### **1️⃣ Acceder a cPanel**
- Entra a tu panel de control
- Busca **"Cron Jobs"**
- Haz clic en **"Add New Cron Job"**

### **2️⃣ Configuración del Cron**
- **Common Settings:** Every day
- **Specific time:** 00:00 (media noche)
- **Command:** (⬇️ copia exactamente)

```bash
/usr/bin/php /home/tu_usuario/public_html/felcc/artisan schedule:run >> /dev/null 2>&1
```

**REEMPLAZA:**
- `tu_usuario` → tu usuario del hosting
- `public_html` → carpeta raíz (a veces `www` o `htdocs`)
- `/felcc` → nombre de tu proyecto (si es diferente)

### **3️⃣ Guardar**
Click en "Add New Cron Job"

---

## ✅ VERIFICAR QUE FUNCIONA

### **Prueba manual** (sin esperar a media noche):
```bash
php artisan db:backup
```

### **Ver backups creados**:
```bash
ls storage/backups/
# Deberías ver: backup_felcc_2026-04-01_00-00-00.sql
```

### **Ver logs de errores**:
```bash
tail -50 storage/logs/laravel.log
```

### **Diagnóstico completo**:
```bash
php artisan backup:verify
```

---

## 📊 ARCHIVOS CREADOS

| Archivo | Ubicación | Descripción |
|---------|-----------|-------------|
| **DatabaseBackup.php** | `app/Console/Commands/` | Comando para hacer backup |
| **VerifyBackupSystem.php** | `app/Console/Commands/` | Verificación del sistema |
| **Kernel.php** | `app/Console/` | Scheduler (cron job) |
| **BACKUP_SETUP_GUIDE.md** | Raíz del proyecto | Guía completa |

---

## 🔧 CAMBIOS RÁPIDOS

### **Cambiar hora de backup**
Edita `app/Console/Kernel.php`:
```php
->at('02:00')  // Cambia a tu hora deseada
```

### **Cambiar retención (cuántos días guardar)**
Edita `app/Console/Commands/DatabaseBackup.php`:
```php
->subDays(7)  // Cambia 7 por otro número
```

### **Cambiar zona horaria**
Edita `app/Console/Kernel.php`:
```php
->timezone('America/La_Paz')  // Cambia a tu zona
```

---

## ⚡ COMANDOS ÚTILES

```bash
# Hacer backup manual
php artisan db:backup

# Verificar sistema
php artisan backup:verify

# Ver logs (últimas 50 líneas)
tail -50 storage/logs/laravel.log

# Limpiar logs antiguos
php artisan log:clear

# Ver cron jobs activos (en hosting SSH)
crontab -l

# Editar cron jobs (en hosting SSH)
crontab -e
```

---

## ❌ SI NO FUNCIONA

### **1. El cron job no se ejecuta**
- Verifica la ruta de PHP: `which php`
- Verifica la ruta del proyecto manualmente
- En cPanel: revisa email para errores de cron

### **2. Permisos insuficientes**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### **3. mysqldump no encontrado**
- Contacta al soporte del hosting
- Algunos hosting lo instalan bajo otra ruta

### **4. Error: "Command not found"**
- Usa la ruta completa: `/usr/bin/php`
- No solo `php`

---

## 📝 NOTAS IMPORTANTES

⚠️ **Seguridad:**
- Los backups contienen datos sensibles
- Descargalos periódicamente a un lugar seguro
- No compartas las rutas

⚠️ **Espacio en disco:**
- Cada backup ocupa ~0.04 MB (ajustará según tu BD)
- Con 7 días de retención: ~0.3 MB promedio
- Revisa el espacio disponible en tu hosting

⚠️ **Funcionamiento continuado:**
- Una vez activado en el hosting, funcionará automáticamente
- **No requiere intervención manual**
- Los logs registran cada ejecución

---

## 🎯 PRÓXIMOS PASOS

Después de activar:
1. Espera a la próxima media noche
2. Verifica que se creó un nuevo backup
3. Descarga periódicamente a tu PC
4. Considera backups en la nube (AWS S3, Dropbox, etc.)

---

**Creado:** 1 de abril de 2026
**Versión:** 1.0
**Estado:** ✅ Probado y funcionando
