# Tarea 2C.2 — Implementar LlmSessionService

**Dependencias:** tarea 2C.1 (tests RED)

**Descripción:** Implementar el servicio de gestión de sesiones LLM efímeras: crear, validar, extender, revocar.

**Criterio de aceptación:**
- [ ] Clase `LlmSessionService` en `app/Services/Security/LlmSessionService.php`
- [ ] `declare(strict_types=1)`
- [ ] `createSession(ApiClient $client, string $llmProvider, string $llmToken, string $ipAddress, string $userAgent): LlmSessionResult`
  - Genera token opaco con alta entropía (≥32 bytes aleatorios, prefijo `riskapi_sess_`)
  - Almacena `session_token_hash = hash('sha256', $sessionToken)`
  - Cifra token LLM con `LlmTokenCipherService::encryptToken()`
  - Genera fingerprint del token LLM: `hash('sha256', $llmToken)`
  - `expires_at = NOW() + SESSION_TTL_SECONDS`
  - Hashea user agent: `hash('sha256', $userAgent)`
- [ ] `validateSession(string $sessionToken): array` — busca por hash, verifica expiración y revocación, extiende `last_activity_at` y `expires_at`
- [ ] `revokeSession(string $sessionToken): void` — marca `revoked_at = NOW()`
- [ ] Usa Query Builder de CI4 o consultas preparadas
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 7 tests de `LlmSessionServiceTest` pasan a verde

**Ficheros implicados:**
- `app/Services/Security/LlmSessionService.php`
- `app/Models/LlmSessionModel.php`
- `app/DTO/LlmSessionResult.php` (DTO para devolver token de sesión, proveedor, expiración)
