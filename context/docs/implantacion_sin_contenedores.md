# Guía de Implantación sin Contenedores

**Proyecto:** API de Análisis de Riesgo Conversacional  
**Autor:** Aythami Melián Perdomo  
**Fecha:** 18/06/2026  
**Stack:** PHP 8.5.x, CodeIgniter 4.7.x, CodeIgniter Shield 1.3.x, MySQL/MariaDB.

## 1. Criterio de implantación

La implantación se realizará directamente sobre servidor Linux, sin Docker, Podman, LXC, Kubernetes ni ningún otro sistema de contenedores.

## 2. Componentes mínimos

- Sistema operativo Linux LTS.
- Nginx o Apache.
- PHP-FPM 8.5.x.
- Composer.
- MySQL o MariaDB.
- Certificado TLS válido.
- Cron o systemd timers.

## 3. Directorios

```text
/var/www/risk-api/current
/var/www/risk-api/releases
/var/www/risk-api/shared/.env
/var/log/risk-api
/var/backups/risk-api
```

## 4. Despliegue recomendado

```bash
cd /var/www/risk-api/releases
mkdir 20260618-001
cd 20260618-001
# Copiar codigo fuente
composer install --no-dev --optimize-autoloader
php spark migrate --all
ln -sfn /var/www/risk-api/releases/20260618-001 /var/www/risk-api/current
sudo systemctl reload php8.5-fpm
sudo systemctl reload nginx
```

## 5. Limpieza de sesiones

La limpieza de sesiones LLM expiradas debe ejecutarse cada minuto.

Ejemplo con cron:

```cron
* * * * * /usr/bin/php /var/www/risk-api/current/spark riskapi:cleanup-llm-sessions >> /var/log/risk-api/cleanup.log 2>&1
```

## 6. Verificación

```bash
curl -fsS https://api.example.com/v1/health
```

Debe devolver estado `ok`.
