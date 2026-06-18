# Tarea 1.4 — Implementar Health controller

**Dependencias:** tarea 1.2 (test contrato RED), tarea 1.3 (test unitario RED)

**Descripción:** Implementar el controlador `Health` en `app/Controllers/Api/V1/Health.php`.

**Criterio de aceptación:**
- [ ] Controlador en `app/Controllers/Api/V1/Health.php`
- [ ] Namespace `App\Controllers\Api\V1`
- [ ] Método `index()` devuelve `{status: "ok", timestamp: "2026-06-18T12:00:00Z"}` (ISO8601, UTC)
- [ ] Sin PHPDoc (se añade después), pero con `declare(strict_types=1)`
- [ ] `HealthContractTest` pasa a verde
- [ ] `HealthControllerTest` pasa a verde
- [ ] `php vendor/bin/phpunit --filter=Health` → 2 tests verdes

**Ficheros implicados:**
- `app/Controllers/Api/V1/Health.php`
