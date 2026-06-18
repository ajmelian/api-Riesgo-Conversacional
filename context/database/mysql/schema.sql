-- Proyecto: API de Análisis de Riesgo Conversacional
-- Autor: Aythami Melián Perdomo
-- Fecha: 18/06/2026
-- Descripción: Esquema inicial MySQL/MariaDB para MVP sin contenedores.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS api_clients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    public_id VARCHAR(64) NOT NULL,
    name VARCHAR(190) NOT NULL,
    client_secret_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'disabled', 'suspended') NOT NULL DEFAULT 'active',
    allowed_llm_providers JSON NOT NULL,
    rate_limit_per_minute INT UNSIGNED NOT NULL DEFAULT 60,
    monthly_quota INT UNSIGNED NOT NULL DEFAULT 10000,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_api_clients_public_id (public_id),
    KEY idx_api_clients_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS api_llm_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    session_token_hash CHAR(64) NOT NULL,
    llm_provider VARCHAR(32) NOT NULL,
    llm_token_ciphertext TEXT NOT NULL,
    llm_token_nonce VARCHAR(128) NOT NULL,
    llm_token_fingerprint CHAR(64) NOT NULL,
    ip_address VARBINARY(16) NOT NULL,
    user_agent_hash CHAR(64) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_activity_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NOT NULL,
    revoked_at DATETIME NULL,
    UNIQUE KEY uq_api_llm_sessions_token_hash (session_token_hash),
    KEY idx_api_llm_sessions_client_id (client_id),
    KEY idx_api_llm_sessions_expires_at (expires_at),
    KEY idx_api_llm_sessions_revoked_at (revoked_at),
    CONSTRAINT fk_api_llm_sessions_client_id
        FOREIGN KEY (client_id) REFERENCES api_clients (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS conversation_analyses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    public_id VARCHAR(64) NOT NULL,
    client_id BIGINT UNSIGNED NOT NULL,
    conversation_external_id VARCHAR(128) NOT NULL,
    risk_level TINYINT UNSIGNED NOT NULL,
    risk_label VARCHAR(64) NOT NULL,
    sentiment VARCHAR(32) NOT NULL,
    confidence DECIMAL(5,4) NOT NULL,
    affected_participant_external_id VARCHAR(128) NULL,
    risk_actor_participant_external_id VARCHAR(128) NULL,
    requires_human_review TINYINT(1) NOT NULL DEFAULT 0,
    recommended_action VARCHAR(96) NOT NULL,
    automatic_external_notification TINYINT(1) NOT NULL DEFAULT 0,
    legal_escalation_candidate TINYINT(1) NOT NULL DEFAULT 0,
    llm_provider VARCHAR(32) NOT NULL,
    model_name VARCHAR(128) NULL,
    prompt_version VARCHAR(64) NOT NULL,
    analysis_version VARCHAR(64) NOT NULL,
    input_hash CHAR(64) NOT NULL,
    output_hash CHAR(64) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_conversation_analyses_public_id (public_id),
    KEY idx_conversation_analyses_client_id (client_id),
    KEY idx_conversation_analyses_conversation_external_id (conversation_external_id),
    KEY idx_conversation_analyses_risk_level (risk_level),
    KEY idx_conversation_analyses_created_at (created_at),
    CONSTRAINT fk_conversation_analyses_client_id
        FOREIGN KEY (client_id) REFERENCES api_clients (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS conversation_analysis_evidence (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    analysis_id BIGINT UNSIGNED NOT NULL,
    message_external_id VARCHAR(128) NOT NULL,
    category VARCHAR(64) NOT NULL,
    excerpt TEXT NOT NULL,
    reason TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_evidence_analysis_id (analysis_id),
    KEY idx_evidence_category (category),
    CONSTRAINT fk_evidence_analysis_id
        FOREIGN KEY (analysis_id) REFERENCES conversation_analyses (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS api_audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NULL,
    actor_type ENUM('api_client', 'admin_user', 'system') NOT NULL,
    actor_id VARCHAR(128) NULL,
    event_type VARCHAR(96) NOT NULL,
    trace_id VARCHAR(64) NOT NULL,
    ip_address VARBINARY(16) NULL,
    user_agent_hash CHAR(64) NULL,
    metadata_json JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_api_audit_logs_client_id (client_id),
    KEY idx_api_audit_logs_event_type (event_type),
    KEY idx_api_audit_logs_trace_id (trace_id),
    KEY idx_api_audit_logs_created_at (created_at),
    CONSTRAINT fk_api_audit_logs_client_id
        FOREIGN KEY (client_id) REFERENCES api_clients (id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
