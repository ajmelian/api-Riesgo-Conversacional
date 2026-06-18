# Tarea 0.4 — Crear migraciones (5 tablas)

**Dependencias:** tarea 0.1

**Descripción:** Convertir el esquema SQL de `context/database/mysql/schema.sql` en 5 migraciones nativas de CodeIgniter 4, una por tabla.

**Criterio de aceptación:**
- [ ] Migración `CreateApiClientsTable` con campos: id, public_id, name, client_secret_hash, status, allowed_llm_providers, rate_limit_per_minute, monthly_quota, created_at, updated_at. UK en public_id.
- [ ] Migración `CreateApiLlmSessionsTable` con campos: id, client_id (FK→api_clients CASCADE), session_token_hash, llm_provider, llm_token_ciphertext, llm_token_nonce, llm_token_fingerprint, ip_address, user_agent_hash, created_at, last_activity_at, expires_at, revoked_at. UK en session_token_hash.
- [ ] Migración `CreateConversationAnalysesTable` con campos: id, public_id, client_id (FK→api_clients CASCADE), conversation_external_id, risk_level, risk_label, sentiment, confidence, affected_participant_external_id, risk_actor_participant_external_id, requires_human_review, recommended_action, automatic_external_notification, legal_escalation_candidate, llm_provider, model_name, prompt_version, analysis_version, input_hash, output_hash, created_at. UK en public_id.
- [ ] Migración `CreateConversationAnalysisEvidenceTable` con campos: id, analysis_id (FK→conversation_analyses CASCADE), message_external_id, category, excerpt, reason, created_at.
- [ ] Migración `CreateApiAuditLogsTable` con campos: id, client_id (FK→api_clients SET NULL), actor_type, actor_id, event_type, trace_id, ip_address, user_agent_hash, metadata_json, created_at.
- [ ] `php spark migrate --all` ejecuta sin errores
- [ ] `php spark migrate:rollback --all` revierte sin errores
- [ ] `php spark migrate:status` muestra las 5 migraciones aplicadas
- [ ] Charset `utf8mb4` y collation `utf8mb4_unicode_ci` en todas las tablas
- [ ] Timestamps usan `CURRENT_TIMESTAMP` y zona UTC

**Ficheros implicados:**
- `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateApiClientsTable.php`
- `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateApiLlmSessionsTable.php`
- `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateConversationAnalysesTable.php`
- `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateConversationAnalysisEvidenceTable.php`
- `app/Database/Migrations/YYYY-MM-DD-HHMMSS_CreateApiAuditLogsTable.php`

**Referencia:** `context/database/mysql/schema.sql`
