# Tarea 2C.1 — Tests unitarios: LlmSessionServiceTest

**Dependencias:** fase-2A (LlmTokenCipherService funcional), fase-2B (ApiClientAuthenticatorService funcional)

**Descripción:** Escribir 7 tests unitarios para el servicio de gestión de sesiones LLM efímeras.

**Criterio de aceptación:**
- [ ] Test `LlmSessionServiceTest` en `tests/Unit/Services/LlmSessionServiceTest.php`
- [ ] `testCreateSessionReturnsToken`: crea sesión con cliente válido + token LLM → devuelve token opaco `riskapi_sess_*`
- [ ] `testSessionTokenIsHashedNotStoredInPlaintext`: verifica que `session_token_hash` en BD es SHA-256, no el token en claro
- [ ] `testSessionExpiresIn300Seconds`: `expires_at = created_at + 300s`
- [ ] `testValidateSessionWithValidToken`: token Bearer válido → devuelve datos de sesión
- [ ] `testValidateSessionExtendsExpiry`: cada llamada válida actualiza `last_activity_at` y extiende `expires_at`
- [ ] `testValidateSessionWithExpiredTokenThrows`: sesión con `expires_at < NOW()` lanza `UnauthorizedException`
- [ ] `testValidateSessionWithRevokedTokenThrows`: sesión con `revoked_at IS NOT NULL` lanza `UnauthorizedException`
- [ ] `testRevokeSessionMarksRevokedAt`: revocar sesión válida rellena `revoked_at`
- [ ] `testCreateSessionEncryptsLlmToken`: el token LLM en BD está cifrado con libsodium (no en claro)
- [ ] Usa mock de `LlmTokenCipherService` y base de datos SQLite en memoria con esquema de sesiones
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/Services/LlmSessionServiceTest.php`
