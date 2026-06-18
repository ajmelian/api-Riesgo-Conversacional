# Fase 2B — ApiClientAuthenticatorService

**Objetivo:** Servicio que valida `X-Client-Id` + `X-Client-Secret` contra la tabla `api_clients`.

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Tests unitarios: ApiClientAuthenticatorServiceTest | ⬜ pendiente | fase-0, seeder api_clients |
| 02 | Implementar ApiClientAuthenticatorService | ⬜ pendiente | 01 |

**Verificación final de fase:** 5 tests verdes (credenciales válidas, secreto inválido, cliente inexistente, disabled, suspended).
