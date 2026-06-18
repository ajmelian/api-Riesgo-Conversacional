# Tarea 2D.5 — Implementar Auth controller (POST + DELETE)

**Dependencias:** tarea 2D.3 (ApiClientAuthFilter), tarea 2D.4 (BearerSessionFilter)

**Descripción:** Implementar el controlador `Auth` con los métodos `createSession` y `revokeSession`.

**Criterio de aceptación:**
- [ ] Controlador `Auth` en `app/Controllers/Api/V1/Auth.php`
- [ ] `declare(strict_types=1)`, namespace `App\Controllers\Api\V1`
- [ ] `createSession()`:
  - Obtiene `client_id` del request (inyectado por `ApiClientAuthFilter`)
  - Lee `X-LLM-Provider` y `X-LLM-Token` de las cabeceras
  - Delega en `LlmSessionService::createSession()`
  - Responde 201 con JSON `{sessionToken, tokenType, expiresIn, llmProvider}`
  - Captura excepciones y responde 401/422/429 según corresponda
- [ ] `revokeSession()`:
  - Obtiene `session_id` del request (inyectado por `BearerSessionFilter`)
  - Delega en `LlmSessionService::revokeSession()`
  - Responde 204 sin body
  - Captura excepciones y responde 401
- [ ] Sin lógica de negocio en el controlador (todo delegado a servicios)
- [ ] PHPDoc en español con plantilla completa

**Ficheros implicados:**
- `app/Controllers/Api/V1/Auth.php`
