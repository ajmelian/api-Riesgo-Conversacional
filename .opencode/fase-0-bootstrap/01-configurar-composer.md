# Tarea 0.1 — Configurar composer.json

**Dependencias:** tarea 0.0

**Descripción:** Añadir las dependencias requeridas al `composer.json`: CodeIgniter Shield, PHPUnit, Faker, league/openapi-psr7-validator, nyholm/psr7, Mockery.

**Criterio de aceptación:**
- [ ] `composer.json` incluye `codeigniter4/shield:^1.3` en `require`
- [ ] `composer.json` incluye `phpunit/phpunit:^11.0 || ^12.0` en `require-dev`
- [ ] `composer.json` incluye `fakerphp/faker:^1.23` en `require-dev`
- [ ] `composer.json` incluye `league/openapi-psr7-validator:^0.22` en `require-dev`
- [ ] `composer.json` incluye `nyholm/psr7:^1.8` en `require-dev`
- [ ] `composer.json` incluye `mockery/mockery:^1.6` en `require-dev`
- [ ] Namespace `App\\` mapeado a `app/` en `autoload.psr-4`
- [ ] `composer install` ejecuta sin errores

**Ficheros implicados:**
- `composer.json`

**Referencia:** `context/config/composer.json.example`
