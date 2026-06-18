# Tarea 3A.1 — Tests unitarios: RiskScoringServiceTest

**Dependencias:** fase-0 (estructura de directorios)

**Descripción:** Escribir 7 tests unitarios para el servicio de scoring de riesgo conversacional.

**Criterio de aceptación:**
- [ ] Test `RiskScoringServiceTest` en `tests/Unit/Services/RiskScoringServiceTest.php`
- [ ] `testNormalConversationScoresLevel5`: sin hostilidad → riskLevel 5, riskLabel normalConversation, sentiment neutral
- [ ] `testMildHostilityScoresLevel4`: insultos aislados, lenguaje brusco → riskLevel 4, sentiment tense
- [ ] `testHarassmentScoresLevel3`: acoso repetido, vejación, humillación → riskLevel 3, sentiment hostile
- [ ] `testThreatRequiresHumanReview`: amenaza explícita → riskLevel 2, requiresHumanReview=true
- [ ] `testSelfHarmScoresLevel1`: incitación a autolesión → riskLevel 1, legalEscalationCandidate=true, sentiment critical
- [ ] `testDeterministicRulesHavePriority`: reglas deterministas disparan antes que scoring LLM
- [ ] `testContainsMinorsIncreasesRisk`: `containsMinors=true` sube la puntuación de riesgo
- [ ] El test falla inicialmente (RED)
- [ ] Cada test prepara un array de mensajes y contexto simulando entrada de API

**Ficheros implicados:**
- `tests/Unit/Services/RiskScoringServiceTest.php`
