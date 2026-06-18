# Tarea 3A.2 — Implementar RiskScoringService

**Dependencias:** tarea 3A.1 (tests RED)

**Descripción:** Implementar el motor de scoring que combina reglas deterministas, contexto de menores, scoring LLM y reincidencia.

**Criterio de aceptación:**
- [ ] Clase `RiskScoringService` en `app/Services/Risk/RiskScoringService.php`
- [ ] `declare(strict_types=1)`
- [ ] `score(array $llmResult, array $deterministicFlags, bool $containsMinors, array $context): RiskScore`
- [ ] Mapa de niveles DEFCON invertido: 5=normal, 4=tensión leve, 3=riesgo moderado, 2=riesgo alto, 1=riesgo crítico
- [ ] Reglas deterministas mínimas implementadas:
  - Palabras clave de amenaza → nivel ≤2
  - Palabras clave de autolesión/suicidio → nivel 1
  - Insultos repetidos + humillación → nivel 3
- [ ] `containsMinors=true` incrementa el riesgo en 1 nivel (min=max)
- [ ] `requiresHumanReview=true` si `riskLevel <= 2`
- [ ] `recommendedAction` mapeado según nivel: noAction, softWarning, formalWarning, strikeAndModeratorReview, temporaryLockAndModeratorReview, urgentHumanReview
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 7 tests de `RiskScoringServiceTest` pasan a verde

**Ficheros implicados:**
- `app/Services/Risk/RiskScoringService.php`
- `app/DTO/RiskScore.php`
