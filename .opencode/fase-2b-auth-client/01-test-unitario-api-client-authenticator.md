# Tarea 2B.1 — Tests unitarios: ApiClientAuthenticatorServiceTest

**Dependencias:** fase-0 (seeder api_clients con cliente de prueba)

**Descripción:** Escribir 5 tests unitarios para el servicio de autenticación de clientes integradores.

**Criterio de aceptación:**
- [ ] Test `ApiClientAuthenticatorServiceTest` en `tests/Unit/Services/ApiClientAuthenticatorServiceTest.php`
- [ ] `testValidCredentialsReturnClient`: `X-Client-Id` + secreto correcto → devuelve datos del cliente
- [ ] `testInvalidSecretThrows`: secreto incorrecto lanza `UnauthorizedException`
- [ ] `testNonexistentClientThrows`: public_id no existe lanza `UnauthorizedException`
- [ ] `testDisabledClientThrows`: cliente con status `disabled` lanza `UnauthorizedException`
- [ ] `testSuspendedClientThrows`: cliente con status `suspended` lanza `UnauthorizedException`
- [ ] Usa mock del modelo `ApiClientModel` o base de datos SQLite en memoria con datos de prueba
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/Services/ApiClientAuthenticatorServiceTest.php`
- `app/Exceptions/UnauthorizedException.php` (crear si no existe)
