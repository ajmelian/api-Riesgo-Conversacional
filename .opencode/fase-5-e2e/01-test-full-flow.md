# Tarea 5.1 — Test de integración: FullFlowTest

**Dependencias:** fase-3C (análisis completo), fase-4 (todos los filtros)

**Descripción:** Escribir test end-to-end que ejecuta el ciclo completo: crear sesión → analizar → revocar → verificar revocación.

**Criterio de aceptación:**
- [ ] Test `FullFlowTest` en `tests/Integration/FullFlowTest.php`, hereda de `CIDatabaseTestCase`
- [ ] Paso 1: POST `/v1/auth/llm-session` con credenciales válidas → 201 + `sessionToken`
- [ ] Paso 2: POST `/v1/conversations/analyze` con Bearer + payload válido → 200 + `riskLevel`, `analysisId`, `evidence`
- [ ] Paso 3: DELETE `/v1/auth/llm-session` con Bearer → 204
- [ ] Paso 4: POST `/v1/conversations/analyze` con el mismo Bearer (ya revocado) → 401
- [ ] Verifica que las evidencias devueltas en paso 2 tienen `messageId`, `category`, `excerpt`, `reason`
- [ ] Verifica `automaticExternalNotification = false`
- [ ] Verifica que `analysisId` es único y no vacío
- [ ] El test falla inicialmente (RED si algún componente no está completo)

**Ficheros implicados:**
- `tests/Integration/FullFlowTest.php`
