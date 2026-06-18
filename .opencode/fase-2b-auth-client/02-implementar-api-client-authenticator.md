# Tarea 2B.2 — Implementar ApiClientAuthenticatorService

**Dependencias:** tarea 2B.1 (tests RED)

**Descripción:** Implementar el servicio que valida credenciales de cliente integrador contra `api_clients`.

**Criterio de aceptación:**
- [ ] Clase `ApiClientAuthenticatorService` en `app/Services/Security/ApiClientAuthenticatorService.php`
- [ ] `declare(strict_types=1)`
- [ ] `authenticate(string $clientPublicId, string $clientSecret): ApiClient` — busca por public_id, verifica secreto con `password_verify()`, comprueba status=active
- [ ] Lanza `App\Exceptions\UnauthorizedException` con mensaje descriptivo en cada caso de fallo
- [ ] Usa Query Builder de CI4 o consultas preparadas (nunca concatenación SQL)
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 5 tests de `ApiClientAuthenticatorServiceTest` pasan a verde

**Ficheros implicados:**
- `app/Services/Security/ApiClientAuthenticatorService.php`
- `app/Models/ApiClientModel.php` (si no existe, crear modelo para tabla `api_clients`)
- `app/Entities/ApiClient.php` o usar stdClass/DTO
