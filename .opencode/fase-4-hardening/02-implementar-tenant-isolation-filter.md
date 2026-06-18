# Tarea 4.2 — Implementar TenantIsolationFilter

**Dependencias:** tarea 4.1 (tests RED), fase-2D (ApiClientAuthFilter y BearerSessionFilter)

**Descripción:** Implementar filtro que garantiza que toda consulta a BD filtra por `client_id` del cliente autenticado.

**Criterio de aceptación:**
- [ ] Clase `TenantIsolationFilter` en `app/Filters/TenantIsolationFilter.php`
- [ ] `declare(strict_types=1)`
- [ ] Obtiene `client_id` del request (inyectado por `ApiClientAuthFilter` o `BearerSessionFilter`)
- [ ] Si no hay `client_id` en el request y la ruta requiere autenticación → 401
- [ ] Inyecta `client_id` en el request para que servicios y modelos filtren automáticamente
- [ ] Registrado en `app/Config/Filters.php` con alias `tenantIsolation`
- [ ] Se coloca DESPUÉS de `apiClientAuth` y `bearerSession` en la pipeline
- [ ] PHPDoc en español
- [ ] Los 2 tests de `TenantIsolationTest` pasan a verde

**Ficheros implicados:**
- `app/Filters/TenantIsolationFilter.php`
- `app/Config/Filters.php`
