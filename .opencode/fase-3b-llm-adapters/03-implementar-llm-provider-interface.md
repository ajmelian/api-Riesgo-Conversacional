# Tarea 3B.3 — Implementar LlmProviderInterface

**Dependencias:** fase-0

**Descripción:** Definir la interfaz PHP que todos los adaptadores de proveedores LLM deben implementar.

**Criterio de aceptación:**
- [ ] Interfaz `LlmProviderInterface` en `app/Services/Llm/LlmProviderInterface.php`
- [ ] `declare(strict_types=1)`
- [ ] `public function validateToken(string $token): bool` — valida token contra el proveedor con llamada mínima
- [ ] `public function analyze(string $prompt, array $messages): array` — envía conversación al LLM y devuelve resultado estructurado
- [ ] PHPDoc en español

**Ficheros implicados:**
- `app/Services/Llm/LlmProviderInterface.php`
