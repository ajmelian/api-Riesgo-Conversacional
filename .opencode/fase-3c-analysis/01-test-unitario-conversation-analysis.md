# Tarea 3C.1 — Tests unitarios: ConversationAnalysisServiceTest

**Dependencias:** fase-3A (RiskScoringService), fase-3B (adaptadores LLM)

**Descripción:** Escribir 7 tests unitarios para el servicio orquestador del análisis de conversaciones.

**Criterio de aceptación:**
- [ ] Test `ConversationAnalysisServiceTest` en `tests/Unit/Services/ConversationAnalysisServiceTest.php`
- [ ] `testAnalysisReturnsCompleteResponse`: respuesta incluye todos los campos: `conversationId`, `analysisId`, `riskLevel`, `riskLabel`, `sentiment`, `confidence`, `evidence`, `audit`, etc.
- [ ] `testAnalysisStoresAuditLog`: se registra en `api_audit_logs` sin secretos (sin token LLM, sin secreto cliente)
- [ ] `testAnalysisStoresEvidence`: fragmentos de evidencia en `conversation_analysis_evidence` con límite de longitud
- [ ] `testInputHashIsStored`: calcula y persiste `input_hash` (SHA-256 del payload normalizado)
- [ ] `testPayloadExceedsMessageLimit`: más de 500 mensajes → lanza `ValidationException` (422)
- [ ] `testMessageExceedsLengthLimit`: mensaje con texto >5000 chars → lanza `ValidationException`
- [ ] `testParticipantCountOutOfRange`: <2 o >20 participantes → lanza `ValidationException`
- [ ] Usa mocks para `RiskScoringService`, `LlmProviderInterface`, `AuditLogService`
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/Services/ConversationAnalysisServiceTest.php`
- `app/Exceptions/ValidationException.php` (crear si no existe)
