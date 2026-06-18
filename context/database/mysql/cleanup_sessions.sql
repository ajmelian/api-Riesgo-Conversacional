-- Limpieza agresiva de sesiones LLM expiradas o revocadas.
-- Ejecutar cada minuto mediante cron, systemd timer o comando spark programado.

DELETE FROM api_llm_sessions
WHERE expires_at < UTC_TIMESTAMP()
   OR revoked_at IS NOT NULL;
