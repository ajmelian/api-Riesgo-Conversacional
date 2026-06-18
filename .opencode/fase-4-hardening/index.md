# Fase 4 — Hardening (seguridad, multi-tenancy, auditoría, limpieza)

**Objetivo:** Completar la pipeline de filtros, implementar auditoría segura, rate limiting, tenant isolation, y limpieza de sesiones.

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Test de integración: TenantIsolationTest | ⬜ pendiente | fase-3C |
| 02 | Implementar TenantIsolationFilter | ⬜ pendiente | 01, fase-2D |
| 03 | Test de integración: RateLimitTest | ⬜ pendiente | fase-2D |
| 04 | Implementar RateLimitFilter | ⬜ pendiente | 03 |
| 05 | Tests unitarios: AuditLogServiceTest | ⬜ pendiente | fase-0 |
| 06 | Implementar AuditLogService | ⬜ pendiente | 05 |
| 07 | Tests unitarios: CleanupLlmSessionsCommandTest | ⬜ pendiente | fase-0 |
| 08 | Implementar CleanupLlmSessionsCommand | ⬜ pendiente | 07 |
| 09 | Tests unitarios: LogRedactionTest | ⬜ pendiente | fase-2D |
| 10 | Implementar TraceIdFilter | ⬜ pendiente | fase-0 |
| 11 | Configurar logging con redacción de secretos | ⬜ pendiente | 09 |

**Verificación final de fase:** Todos los filtros registrados en orden correcto en `Filters.php`. Tests de tenant isolation, rate limit, log redaction, audit log y cleanup verdes.
