# Tarea 3C.2 — Tests de integración: AnalysisTest

**Dependencias:** fase-2D (filtros y auth controller), fase-3A, fase-3B

**Descripción:** Escribir 4 tests de integración para el flujo completo de análisis con BD real.

**Criterio de aceptación:**
- [ ] Test `AnalysisTest` en `tests/Integration/AnalysisTest.php`, hereda de `CIDatabaseTestCase`
- [ ] `testAnalyzeConversationSuccess`: POST con sesión Bearer válida + payload correcto → 200 + respuesta completa
- [ ] `testAnalyzeWithoutBearerToken`: POST sin Authorization → 401
- [ ] `testAnalyzeWithExpiredSession`: POST con token expirado → 401
- [ ] `testAnalyzeWithInvalidPayload`: POST con payload inválido (participantes <2, mensajes vacíos) → 422
- [ ] Seeder para crear sesión válida antes de cada test
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Integration/AnalysisTest.php`
