# Tarea 4.8 — Implementar CleanupLlmSessionsCommand

**Dependencias:** tarea 4.7 (tests RED)

**Descripción:** Implementar el comando spark basado en el snippet `context/src-snippets/CleanupLlmSessionsCommand.php`.

**Criterio de aceptación:**
- [ ] Clase `CleanupLlmSessionsCommand` en `app/Commands/CleanupLlmSessionsCommand.php`
- [ ] `declare(strict_types=1)`, extiende `BaseCommand`
- [ ] `$group = 'RiskAPI'`, `$name = 'riskapi:cleanup-llm-sessions'`
- [ ] `run()`: ejecuta `DELETE FROM api_llm_sessions WHERE expires_at < UTC_TIMESTAMP() OR revoked_at IS NOT NULL`
- [ ] Muestra conteo de filas eliminadas con `CLI::write()`
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 2 tests de `CleanupLlmSessionsCommandTest` pasan a verde

**Ficheros implicados:**
- `app/Commands/CleanupLlmSessionsCommand.php`

**Referencia:** `context/src-snippets/CleanupLlmSessionsCommand.php`
