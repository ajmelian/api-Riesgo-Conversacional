# Fase 1 — Contrato + Health (GET /v1/health)

**Objetivo:** Validar el pipeline SDD+TDD completo con el endpoint más simple. Primero los tests, luego la implementación.

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Configurar ruta /v1/health | ⬜ pendiente | fase-0 (completa) |
| 02 | Test de contrato: HealthContractTest | ⬜ pendiente | 01 |
| 03 | Test unitario: HealthControllerTest | ⬜ pendiente | fase-0 |
| 04 | Implementar Health controller | ⬜ pendiente | 02, 03 |

**Verificación final de fase:** `tests/Contract/HealthContractTest.php` verde + `/v1/health` devuelve `{status:"ok", timestamp:ISO8601}`.
