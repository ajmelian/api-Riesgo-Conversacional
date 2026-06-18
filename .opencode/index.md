# Plan de Tareas — API de Análisis de Riesgo Conversacional

Metodología: SDD (Spec-Driven Development) + TDD (Test-Driven Development).
Flujo por cada endpoint: Test de contrato → Test de integración → Tests unitarios → Implementación → Refactor.

## Fases

| # | Fase | Tareas | Estado |
|---|------|--------|--------|
| 0 | [Bootstrap del proyecto](fase-0-bootstrap/index.md) | 6 | ✅ completada |
| 1 | [Contrato + Health](fase-1-contrato-health/index.md) | 4 | ⬜ pendiente |
| 2A | [LlmTokenCipherService](fase-2a-cifrado/index.md) | 2 | ⬜ pendiente |
| 2B | [ApiClientAuthenticatorService](fase-2b-auth-client/index.md) | 2 | ⬜ pendiente |
| 2C | [LlmSessionService](fase-2c-sesiones/index.md) | 2 | ⬜ pendiente |
| 2D | [Filtros + Auth Controller](fase-2d-filtros-auth/index.md) | 6 | ⬜ pendiente |
| 3A | [RiskScoringService](fase-3a-risk-scoring/index.md) | 2 | ⬜ pendiente |
| 3B | [Adaptadores LLM](fase-3b-llm-adapters/index.md) | 5 | ⬜ pendiente |
| 3C | [ConversationAnalysisService](fase-3c-analysis/index.md) | 6 | ⬜ pendiente |
| 4 | [Hardening](fase-4-hardening/index.md) | 11 | ⬜ pendiente |
| 5 | [End-to-End](fase-5-e2e/index.md) | 1 | ⬜ pendiente |

**Total: 47 tareas atómicas**

## Orden de ejecución

```
Fase 0 → Fase 1 → Fase 2A → 2B → 2C → 2D → Fase 3A → 3B → 3C → Fase 4 → Fase 5
```

Las fases 2A/2B pueden ejecutarse en paralelo. Las fases 3A/3B pueden ejecutarse en paralelo.

## Resumen de tests por fase

| Fase | Tests de contrato | Tests de integración | Tests unitarios |
|------|-------------------|---------------------|-----------------|
| 1 | 1 | — | 1 |
| 2A | — | — | 6 |
| 2B | — | — | 5 |
| 2C | — | — | 7 |
| 2D | 2 | 7 | — |
| 3A | — | — | 7 |
| 3B | — | — | 6 |
| 3C | 3 | 4 | 7 |
| 4 | — | 4 | 7 |
| 5 | — | 1 | — |
| **Total** | **6** | **16** | **46** |

## Convenciones

- Cada tarea se marca `⬜ pendiente`, `🔄 en progreso` o `✅ completada` en el índice de su fase
- Las dependencias indican qué tareas deben estar completadas antes de empezar
- Todos los tests deben estar en ROJO antes de implementar
- Todos los ficheros PHP llevan `declare(strict_types=1)` y PHPDoc en español
