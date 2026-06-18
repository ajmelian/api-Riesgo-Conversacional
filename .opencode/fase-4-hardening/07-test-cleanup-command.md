# Tarea 4.7 — Tests unitarios: CleanupLlmSessionsCommandTest

**Dependencias:** fase-0 (tabla api_llm_sessions migrada)

**Descripción:** Escribir 2 tests para el comando spark de limpieza de sesiones.

**Criterio de aceptación:**
- [ ] Test `CleanupLlmSessionsCommandTest` en `tests/Unit/Commands/CleanupLlmSessionsCommandTest.php`
- [ ] `testDeletesExpiredSessions`: inserta sesiones con `expires_at < NOW()` → ejecuta comando → verifica que se eliminaron
- [ ] `testDeletesRevokedSessions`: inserta sesiones con `revoked_at IS NOT NULL` → ejecuta comando → verifica que se eliminaron
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/Commands/CleanupLlmSessionsCommandTest.php`
