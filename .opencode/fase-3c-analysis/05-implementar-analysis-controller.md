# Tarea 3C.5 — Implementar Analysis controller

**Dependencias:** tarea 3C.4 (ConversationAnalysisService), fase-2D (BearerSessionFilter)

**Descripción:** Implementar el controlador `Analysis` que expone `POST /v1/conversations/analyze`.

**Criterio de aceptación:**
- [ ] Controlador `Analysis` en `app/Controllers/Api/V1/Analysis.php`
- [ ] `declare(strict_types=1)`, namespace `App\Controllers\Api\V1`
- [ ] `analyze()`:
  - Obtiene `client_id` y `session_id` del request (inyectados por filtros)
  - Valida el JSON del body contra las reglas de CI4 Validation
  - Delega en `ConversationAnalysisService::analyze()`
  - Responde 200 con `ConversationAnalysisResponse`
  - Captura `ValidationException` → 422 con `ValidationErrorResponse` (incluye `violations`)
  - Captura `UnauthorizedException` → 401
  - Captura `AnalysisException` → 400
  - Captura `RateLimitException` → 429 con `Retry-After`
- [ ] Sin lógica de negocio en el controlador
- [ ] PHPDoc en español con plantilla completa

**Ficheros implicados:**
- `app/Controllers/Api/V1/Analysis.php`
- `app/Validation/ConversationAnalysisRules.php` (reglas de validación personalizadas si es necesario)
