# Tarea 3B.2 — Tests unitarios: AnthropicProviderServiceTest

**Dependencias:** fase-0

**Descripción:** Escribir 3 tests unitarios para el adaptador de Anthropic Claude, mockeando las llamadas HTTP.

**Criterio de aceptación:**
- [ ] Test `AnthropicProviderServiceTest` en `tests/Unit/Services/AnthropicProviderServiceTest.php`
- [ ] `testSendsMinimizedPayload`: verifica minimización de datos
- [ ] `testValidatesResponseJson`: respuesta malformada lanza `AnalysisException`
- [ ] `testHandlesTimeout`: timeout lanza excepción controlada
- [ ] Usa mock de cliente HTTP de CI4
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/Services/AnthropicProviderServiceTest.php`
