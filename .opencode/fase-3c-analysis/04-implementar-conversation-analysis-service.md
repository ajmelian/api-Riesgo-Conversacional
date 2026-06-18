# Tarea 3C.4 — Implementar ConversationAnalysisService

**Dependencias:** tarea 3C.1 (tests RED), fase-3A, fase-3B

**Descripción:** Implementar el servicio orquestador del análisis: validación, normalización, minimización, reglas, LLM, scoring, evidencia, auditoría.

**Criterio de aceptación:**
- [ ] Clase `ConversationAnalysisService` en `app/Services/Risk/ConversationAnalysisService.php`
- [ ] `declare(strict_types=1)`
- [ ] `analyze(array $validatedPayload, int $clientId, string $llmProvider, string $sessionId): ConversationAnalysisResult`
- [ ] Validaciones de dominio:
  - participantes [2, 20]
  - mensajes [1, 500]
  - texto por mensaje ≤ 5000 caracteres
  - Lanza `App\Exceptions\ValidationException` con detalles por campo
- [ ] Capa de normalización: orden temporal, trim, unicode NFC
- [ ] Capa de minimización: pseudonimiza participantes, elimina IPs antes de enviar al LLM
- [ ] Capa de reglas deterministas: detecta patrones críticos (amenazas directas, autolesión, grooming, discriminación, datos personales expuestos)
- [ ] Capa LLM: resuelve adaptador vía factory según `X-LLM-Provider`, envía prompt de sistema + conversación minimizada
- [ ] Capa de validación de respuesta LLM: verifica que el JSON devuelto encaja en el schema esperado
- [ ] Scoring final con `RiskScoringService`: combina reglas + LLM + `containsMinors`
- [ ] Genera `analysisId` opaco, `inputHash` (SHA-256), `outputHash` (SHA-256)
- [ ] Extrae evidencias mínimas (≤1000 caracteres, ≤10 fragmentos)
- [ ] Persiste análisis en `conversation_analyses` y evidencias en `conversation_analysis_evidence`
- [ ] `automaticExternalNotification` siempre `false`
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 7 tests de `ConversationAnalysisServiceTest` pasan a verde

**Ficheros implicados:**
- `app/Services/Risk/ConversationAnalysisService.php`
- `app/Models/ConversationAnalysisModel.php`
- `app/Models/ConversationAnalysisEvidenceModel.php`
- `app/DTO/ConversationAnalysisResult.php`
- `app/Services/Llm/LlmProviderFactory.php` (factory que resuelve adaptador según provider)
