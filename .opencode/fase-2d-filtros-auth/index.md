# Fase 2D — Filtros de autenticación + Auth Controller + Rutas

**Objetivo:** Implementar la pipeline completa de autenticación API: filtros, controlador y rutas para los endpoints `/v1/auth/llm-session`.

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Tests de integración: AuthTest | ⬜ pendiente | fase-2C |
| 02 | Tests de contrato: AuthContractTest | ⬜ pendiente | fase-2C |
| 03 | Implementar ApiClientAuthFilter | ⬜ pendiente | fase-2B |
| 04 | Implementar BearerSessionFilter | ⬜ pendiente | fase-2C |
| 05 | Implementar Auth controller (POST + DELETE) | ⬜ pendiente | 03, 04 |
| 06 | Configurar rutas /v1/auth/llm-session | ⬜ pendiente | fase-1 |

**Verificación final de fase:** 7 tests de integración + 2 tests de contrato verdes. POST 201, DELETE 204, errores 401/422.
