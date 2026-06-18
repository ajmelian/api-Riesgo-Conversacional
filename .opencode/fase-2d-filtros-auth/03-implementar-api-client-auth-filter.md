# Tarea 2D.3 — Implementar ApiClientAuthFilter

**Dependencias:** fase-2B (ApiClientAuthenticatorService funcional)

**Descripción:** Implementar el filtro que valida `X-Client-Id` + `X-Client-Secret` en cada petición e inyecta `client_id` en el request.

**Criterio de aceptación:**
- [ ] Clase `ApiClientAuthFilter` en `app/Filters/ApiClientAuthFilter.php`
- [ ] `declare(strict_types=1)`
- [ ] Extrae `X-Client-Id` y `X-Client-Secret` de las cabeceras
- [ ] Delega validación en `ApiClientAuthenticatorService`
- [ ] Inyecta `client_id` (ID interno de la BD) en el request para filtros y controladores posteriores
- [ ] Responde 401 con `ErrorResponse` si las credenciales son inválidas
- [ ] Registrado en `app/Config/Filters.php` con alias `apiClientAuth`
- [ ] PHPDoc en español

**Ficheros implicados:**
- `app/Filters/ApiClientAuthFilter.php`
- `app/Config/Filters.php` (registrar el filtro)
