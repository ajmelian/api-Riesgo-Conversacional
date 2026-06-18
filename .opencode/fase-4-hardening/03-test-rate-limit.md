# Tarea 4.3 — Test de integración: RateLimitTest

**Dependencias:** fase-2D (auth funcional)

**Descripción:** Escribir 2 tests que verifiquen el rate limiting de 60 req/min.

**Criterio de aceptación:**
- [ ] Test `RateLimitTest` en `tests/Integration/RateLimitTest.php`
- [ ] `testRateLimitRejectsExcessiveRequests`: >60 peticiones en 1 minuto desde misma IP/cliente → 429 con cabecera `Retry-After`
- [ ] `testHealthyClientNotRateLimited`: <60 peticiones → sin errores 429
- [ ] El test falla inicialmente (RED — RateLimitFilter no implementado)

**Ficheros implicados:**
- `tests/Integration/RateLimitTest.php`
