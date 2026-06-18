# Fase 3C — ConversationAnalysisService + Controller + Rutas

**Objetivo:** Servicio que orquesta el análisis completo: normalización, reglas deterministas, LLM, scoring, evidencia, auditoría.

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Tests unitarios: ConversationAnalysisServiceTest | ⬜ pendiente | fase-3A, fase-3B |
| 02 | Tests de integración: AnalysisTest | ⬜ pendiente | fase-2D |
| 03 | Tests de contrato: AnalysisContractTest | ⬜ pendiente | fase-2D |
| 04 | Implementar ConversationAnalysisService | ⬜ pendiente | 01 |
| 05 | Implementar Analysis controller | ⬜ pendiente | 04, fase-2D |
| 06 | Configurar ruta /v1/conversations/analyze | ⬜ pendiente | fase-1 |

**Verificación final de fase:** 7 tests unitarios + 4 tests de integración + 1 test de contrato verdes. POST 200, errores 400/401/413/422/429.
