# Fase 2C — LlmSessionService (sesiones efímeras)

**Objetivo:** Servicio que crea, valida, extiende y revoca sesiones LLM de 300s TTL.

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Tests unitarios: LlmSessionServiceTest | ⬜ pendiente | fase-2A, fase-2B |
| 02 | Implementar LlmSessionService | ⬜ pendiente | 01 |

**Verificación final de fase:** 7 tests verdes (crear sesión, hash no en claro, TTL 300s, validar token, extender expiry, sesión expirada, sesión revocada, token LLM cifrado).
