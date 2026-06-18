# Tarea 3B.1 — Tests unitarios: OpenAiProviderServiceTest

**Dependencias:** fase-0

**Descripción:** Escribir 3 tests unitarios para el adaptador de OpenAI, mockeando las llamadas HTTP.

**Criterio de aceptación:**
- [ ] Test `OpenAiProviderServiceTest` en `tests/Unit/Services/OpenAiProviderServiceTest.php`
- [ ] `testSendsMinimizedPayload`: verifica que no envía IPs, emails ni nombres reales al LLM
- [ ] `testValidatesResponseJson`: respuesta malformada (no JSON) del LLM lanza `AnalysisException`
- [ ] `testHandlesTimeout`: timeout de conexión lanza excepción controlada
- [ ] Usa mock de cliente HTTP de CI4 (`Services::curlrequest()`)
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/Services/OpenAiProviderServiceTest.php`
