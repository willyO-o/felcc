#!/bin/bash

# =====================================================
# SCRIPT DE PRUEBA: ACTIVAR CRON JOB EN HOSTING
# =====================================================
# Este script te ayuda a configurar el cron job en tu hosting
# Uso: Lee las instrucciones según el tipo de hosting

# OPCIÓN 1: cPanel - Comando para agregar vía SSH
# =====================================================
echo "OPCIÓN 1: Si tienes cPanel, ejecuta esto en SSH:"
echo ""
echo "  (crontab -l; echo '0 0 * * * /usr/bin/php /home/TU_USUARIO/public_html/felcc/artisan schedule:run >> /dev/null 2>&1') | crontab -"
echo ""

# OPCIÓN 2: Verificar si ya existe
# =====================================================
echo "Para verificar si el cron job ya está activo:"
echo "  crontab -l"
echo ""

# OPCIÓN 3: Manual - Editar directamente
# =====================================================
echo "O ingresa a crontab manualmente:"
echo "  crontab -e"
echo "  # Luego pega esta línea:"
echo "  0 0 * * * /usr/bin/php /home/TU_USUARIO/public_html/felcc/artisan schedule:run >> /dev/null 2>&1"
echo ""

# OPCIÓN 4: Verificación rápida
# =====================================================
echo "Para probar que funciona:"
echo "  php artisan db:backup"
echo ""

echo "✅ Todas las instrucciones han sido mostradas"
