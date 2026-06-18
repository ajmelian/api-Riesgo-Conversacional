# Tarea 3B.4 — Implementar OpenAiProviderService

**Dependencias:** tarea 3B.1 (tests RED), tarea 3B.3 (interfaz)

**Descripción:** Implementar adaptador para OpenAI que minimiza datos, envía al endpoint `/chat/completions`, valida respuesta JSON.

**Criterio de aceptación:**
- [ ] Clase `OpenAiProviderService` en `app/Services/Llm/OpenAiProviderService.php`
- [ ] `declare(strict_types=1)`, implementa `LlmProviderInterface`
- [ ] `validateToken(string $token): bool` — llama a OpenAI con un prompt mínimo para verificar token
- [ ] `analyze(string $prompt, array $messages): array`:
  - Minimiza datos: descarta IPs, emails, nombres (pseudonimiza participantes)
  - Construye payload para la API de chat completions
  - Timeout configurable (por defecto 4s)
  - Valida respuesta JSON contra schema esperado
  - Lanza `AnalysisException` si la respuesta no es válida
- [ ] URL base configurable desde `.env` (`OPENAI_BASE_URL`)
- [ ] Solo llama a dominio en allowlist (no SSRF)
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 3 tests de `OpenAiProviderServiceTest` pasan a verde

**Ficheros implicados:**
- `app/Services/Llm/OpenAiProviderService.php`
- `app/Exceptions/AnalysisException.php` (crear si no existe)
