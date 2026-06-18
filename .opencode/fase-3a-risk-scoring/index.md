# Fase 3A — RiskScoringService

**Objetivo:** Servicio que combina reglas deterministas + scoring LLM para clasificar el riesgo conversacional (niveles 1-5).

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Tests unitarios: RiskScoringServiceTest | ⬜ pendiente | fase-0 |
| 02 | Implementar RiskScoringService | ⬜ pendiente | 01 |

**Verificación final de fase:** 7 tests verdes (nivel 5 normal, nivel 4 hostilidad leve, nivel 3 acoso, nivel 2 amenaza, nivel 1 autolesión, reglas deterministas primero, containsMinors sube scoring).
