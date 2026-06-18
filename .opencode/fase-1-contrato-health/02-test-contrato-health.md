# Tarea 1.2 — Test de contrato: HealthContractTest

**Dependencias:** tarea 1.1 (ruta configurada)

**Descripción:** Escribir test que cargue el OpenAPI YAML y valide que `GET /v1/health` responde con JSON conforme al schema `HealthResponse`.

**Criterio de aceptación:**
- [ ] Test `HealthContractTest` en `tests/Contract/HealthContractTest.php`
- [ ] Usa `league/openapi-psr7-validator` con `ValidatorBuilder::fromYamlFile()`
- [ ] Construye mensaje PSR-7 a partir de la respuesta de CI4 (usando `nyholm/psr7`)
- [ ] Valida contra `OperationAddress('/v1/health', 'GET')` y schema `HealthResponse`
- [ ] Espera código 200 y JSON con campos `status` y `timestamp`
- [ ] El test falla inicialmente (RED — no hay controlador aún)

**Ficheros implicados:**
- `tests/Contract/HealthContractTest.php`

**Nota:** Este test debe fallar en este paso. Se pondrá verde en la tarea 1.4.
