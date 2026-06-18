# Tarea 4.9 — Tests unitarios: LogRedactionTest

**Dependencias:** fase-2D (auth funcional)

**Descripción:** Escribir 2 tests que verifiquen que los logs nunca contienen secretos ni cabeceras sensibles.

**Criterio de aceptación:**
- [ ] Test `LogRedactionTest` en `tests/Unit/LogRedactionTest.php`
- [ ] `testLogsDoNotContainAuthHeaders`: tras peticiones con Authorization, X-Client-Secret, X-LLM-Token → los logs no contienen estos valores
- [ ] `testLogsDoNotContainDecryptedTokens`: el token LLM descifrado nunca aparece en logs
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/LogRedactionTest.php`
