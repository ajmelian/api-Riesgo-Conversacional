# Tarea 3C.3 — Tests de contrato: AnalysisContractTest

**Dependencias:** fase-2D (filtros y routes)

**Descripción:** Escribir tests de contrato para POST `/v1/conversations/analyze` validando contra OpenAPI.

**Criterio de aceptación:**
- [ ] Test `AnalysisContractTest` en `tests/Contract/AnalysisContractTest.php`
- [ ] `testAnalyzeConversationContract`: POST con sesión válida + payload → valida 200 contra `ConversationAnalysisResponse`
- [ ] `testAnalyzeValidationErrorContract`: payload inválido → valida 422 contra `ValidationErrorResponse`
- [ ] `testAnalyzeUnauthorizedContract`: sin token → valida 401 contra `ErrorResponse`
- [ ] Usa `league/openapi-psr7-validator`
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Contract/AnalysisContractTest.php`
