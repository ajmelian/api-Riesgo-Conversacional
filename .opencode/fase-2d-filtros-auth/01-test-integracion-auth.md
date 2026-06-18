# Tarea 2D.1 — Tests de integración: AuthTest

**Dependencias:** fase-2C (LlmSessionService funcional)

**Descripción:** Escribir 7 tests de integración para los endpoints de autenticación con BD real (SQLite en memoria migrada).

**Criterio de aceptación:**
- [ ] Test `AuthTest` en `tests/Integration/AuthTest.php`, hereda de `CIDatabaseTestCase`
- [ ] `testCreateLlmSessionSuccess`: POST /v1/auth/llm-session con cabeceras válidas → 201 + `sessionToken`, `tokenType: Bearer`, `expiresIn: 300`, `llmProvider`
- [ ] `testCreateLlmSessionInvalidClientId`: `X-Client-Id` incorrecto → 401 + `ErrorResponse`
- [ ] `testCreateLlmSessionInvalidSecret`: `X-Client-Secret` incorrecto → 401
- [ ] `testCreateLlmSessionMissingHeaders`: sin cabeceras obligatorias → 401/422
- [ ] `testRevokeLlmSessionSuccess`: DELETE con Bearer válido → 204 sin body
- [ ] `testRevokeLlmSessionWithoutToken`: DELETE sin Authorization → 401
- [ ] `testRevokeLlmSessionExpired`: DELETE con sesión expirada → 401
- [ ] El test falla inicialmente (RED — no hay filtros ni controlador)

**Ficheros implicados:**
- `tests/Integration/AuthTest.php`
