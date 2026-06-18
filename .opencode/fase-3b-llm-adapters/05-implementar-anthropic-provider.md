# Tarea 3B.5 — Implementar AnthropicProviderService

**Dependencias:** tarea 3B.2 (tests RED), tarea 3B.3 (interfaz)

**Descripción:** Implementar adaptador para Anthropic Claude con el mismo contrato que OpenAI.

**Criterio de aceptación:**
- [ ] Clase `AnthropicProviderService` en `app/Services/Llm/AnthropicProviderService.php`
- [ ] `declare(strict_types=1)`, implementa `LlmProviderInterface`
- [ ] `validateToken(string $token): bool` — verifica token con llamada mínima a Anthropic
- [ ] `analyze(string $prompt, array $messages): array`:
  - Misma minimización de datos que OpenAI
  - Construye payload para la API de Anthropic Messages
  - Timeout configurable (por defecto 4s)
  - Valida respuesta JSON contra schema esperado
- [ ] URL base configurable desde `.env` (`ANTHROPIC_BASE_URL`)
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 3 tests de `AnthropicProviderServiceTest` pasan a verde

**Ficheros implicados:**
- `app/Services/Llm/AnthropicProviderService.php`
