# Tarea 2D.4 — Implementar BearerSessionFilter

**Dependencias:** fase-2C (LlmSessionService funcional)

**Descripción:** Implementar el filtro que valida `Authorization: Bearer riskapi_sess_*` e inyecta `session_id`.

**Criterio de aceptación:**
- [ ] Clase `BearerSessionFilter` en `app/Filters/BearerSessionFilter.php`
- [ ] `declare(strict_types=1)`
- [ ] Extrae token Bearer de la cabecera `Authorization`
- [ ] Valida formato `riskapi_sess_*`
- [ ] Delega validación en `LlmSessionService::validateSession()`
- [ ] Inyecta `session_id` en el request
- [ ] Responde 401 con `ErrorResponse` si el token es inválido, expirado o revocado
- [ ] Responde 401 si la cabecera `Authorization` está ausente
- [ ] Registrado en `app/Config/Filters.php` con alias `bearerSession`
- [ ] PHPDoc en español

**Ficheros implicados:**
- `app/Filters/BearerSessionFilter.php`
- `app/Config/Filters.php`
