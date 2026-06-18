# Tarea 2D.2 — Tests de contrato: AuthContractTest

**Dependencias:** fase-2C (LlmSessionService funcional)

**Descripción:** Escribir tests de contrato para POST y DELETE `/v1/auth/llm-session` validando respuestas contra OpenAPI.

**Criterio de aceptación:**
- [ ] Test `AuthContractTest` en `tests/Contract/AuthContractTest.php`
- [ ] `testCreateLlmSessionContract`: POST → valida 201 contra schema `LlmSessionResponse`
- [ ] `testCreateLlmSessionErrorContract`: POST con credenciales inválidas → valida error contra `ErrorResponse`
- [ ] `testRevokeLlmSessionContract`: DELETE → valida 204 (sin body)
- [ ] `testRevokeLlmSessionErrorContract`: DELETE sin token → valida 401 contra `ErrorResponse`
- [ ] Usa `league/openapi-psr7-validator` con el mismo patrón que `HealthContractTest`
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Contract/AuthContractTest.php`
