# 📋 GUÍA COMPLETA: BACKUPS AUTOMÁTICOS DE BASE DE DATOS

## ✅ ESTADO ACTUAL
- ✔️ Comando Artisan creado: `php artisan db:backup`
- ✔️ Scheduler de Laravel configurado
- ✔️ Backups se guardan en: `storage/backups/`
- ✔️ Se mantienen los últimos 7 días automáticamente

---

## 📦 REQUISITOS PREVIOS PARA EL HOSTING

Asegúrate de que tu hosting tenga:
1. **mysqldump** instalado (generalmente viene por defecto)
2. **Acceso a SSH o panel de control cPanel**
3. **PHP CLI** habilitado
4. **Permisos de escritura** en la carpeta `storage/`

---

## 🚀 PASOS PARA ACTIVAR EN TU HOSTING

### **OPCIÓN 1: cPanel (La más común/fácil)**

1. **Accede a tu panel cPanel**
2. **Busca "Cron Jobs"** (usualmente en sección "Advanced")
3. **Haz clic en "Add New Cron Job"**
4. **Llena los campos así:**

   - **Common Settings:** Selecciona **"Every day"**
   - **Specific time:** Establece **"00:00"** (media noche)
   - **Command:** Copia y pega exactamente esto:

   ```bash
   /usr/bin/php /home/tu_usuario/public_html/felcc/artisan schedule:run >> /dev/null 2>&1
   ```

   ⚠️ **IMPORTANTE:** Reemplaza:
   - `tu_usuario` → tu usuario de hosting
   - `public_html` → la carpeta raíz de tu hosting (algunos usan `www` o `htdocs`)
   - `/felcc` → la carpeta de tu proyecto (ajusta si es diferente)

5. **Haz clic en "Add New Cron Job"**

---

### **OPCIÓN 2: Línea de comando (SSH)**

Si tienes acceso SSH:

1. **Conéctate vía SSH a tu servidor**
2. **Abre el editor de crontab:**
   ```bash
   crontab -e
   ```

3. **Agrega esta línea al final:**
   ```bash
   0 0 * * * /usr/bin/php /home/tu_usuario/public_html/felcc/artisan schedule:run >> /home/tu_usuario/logs/laravel-schedule.log 2>&1
   ```

   ⚠️ Recuerda reemplazar la ruta según tu hosting

4. **Guarda con:** `Ctrl+O`, `Enter`, `Ctrl+X`

---

### **OPCIÓN 3: Si tu hosting NO tiene cPanel (VPS/Servidor dedicado)**

```bash
# Accede vía SSH
ssh tu_usuario@tu_dominio.com

# Abre crontab
crontab -e

# Agrega (reemplazando las rutas):
0 0 * * * cd /var/www/tu_proyecto && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔍 CÓMO VERIFICAR QUE FUNCIONA

### **Opción A: Verificación Manual**

Ejecuta en terminal:
```bash
cd /tu/ruta/del/proyecto
php artisan db:backup
```

Deberías ver un mensaje de éxito y un archivo en `storage/backups/`

### **Opción B: Ver Logs del Sistema**

En cPanel o SSH, revisa el archivo de logs:
```bash
/home/tu_usuario/logs/laravel-schedule.log
```

O en tu proyecto Laravel:
```bash
tail -f storage/logs/laravel.log
```

### **Opción C: Verificar Archivos Creados**

```bash
# Accede a tu hosting vía FTP o SSH
ls /tu/ruta/del/proyecto/storage/backups/

# Deberías ver archivos como:
# backup_felcc_2026-04-01_00-00-00.sql
# backup_felcc_2026-04-02_00-00-00.sql
```

---

## 📊 CONFIGURACIÓN AVANZADA

### **Cambiar zona horaria**

Si tus backups se ejecutan a la hora incorrecta, edita:
```
app/Console/Kernel.php
```

Busca esta línea:
```php
->timezone('America/La_Paz')
```

Cambia `America/La_Paz` por tu zona horaria. [Ver lista aquí](https://www.php.net/manual/es/timezones.php)

### **Cambiar hora de backup**

En `app/Console/Kernel.php`, cambia:
```php
->at('00:00')  // Cambia esto a tu hora preferida
```

Ejemplos:
- `->at('02:00')` → 2 AM
- `->at('14:30')` → 2:30 PM

### **Cambiar días de retención**

En `app/Console/Commands/DatabaseBackup.php`, busca:
```php
->subDays(7)  // Cambiar 7 por el número de días que quieras guardar
```

---

## ⚙️ TROUBLESHOOTING

### **Problema: El cron job no se ejecuta**

1. ✅ Verifica la ruta exacta de PHP:
   ```bash
   which php
   # Output: /usr/bin/php (copia esto en tu cron job)
   ```

2. ✅ Verifica la ruta del proyecto:
   ```bash
   pwd  # Muestra tu directorio actual
   ```

3. ✅ Prueba el comando manualmente:
   ```bash
   /usr/bin/php /home/usuario/public_html/felcc/artisan db:backup
   ```

### **Problema: "mysqldump not found"**

Tu hosting no tiene mysqldump instalado. Solicita al soporte que lo instale.

### **Problema: Error de permisos**

```bash
# Dale permisos de escritura a storage
chmod -R 755 storage/
```

### **Problema: No hay espacio en disco**

Los backups se acumulan. Opción:
- Cambiar `->subDays(7)` a `->subDays(3)` (guardar solo 3 días)
- Solicitar más espacio al hosting

---

## 📝 REGISTRO DE CAMBIOS

El sistema registrará en `storage/logs/laravel.log`:
- ✅ Backups exitosos con timestamp
- ❌ Errores si algo falla

Puedes revisar:
```bash
tail -100 storage/logs/laravel.log
```

---

## 🔐 SEGURIDAD

⚠️ **IMPORTANTE:**
- Los backups contienen datos sensibles 
- Considera descargarlos periódicamente a un lugar seguro
- No compartas las rutas o credenciales
- En producción, considera un script que suba los backups a cloud (AWS S3, Dropbox, etc.)

---

## 💡 PRÓXIMOS PASOS OPCIONALES

Si quieres mejorar el sistema:

1. **Enviar backups a la nube:**
   ```bash
   composer require league/flysystem-aws-s3-v3
   ```

2. **Enviar notificaciones por email:**
   Editar `app/Console/Kernel.php` para usar `->sendOutputTo()`

3. **Comprimir backups:**
   Añadir en el comando: `gzip {$backupFile}`

---

## 📞 RESUMEN RÁPIDO

| Elemento | Valor |
|----------|-------|
| **Comando ejecutable** | `php artisan db:backup` |
| **Scheduler** | `php artisan schedule:run` |
| **Ubicación de backups** | `storage/backups/` |
| **Retención** | Últimos 7 días |
| **Comprimido** | No (puedes cambiar) |

