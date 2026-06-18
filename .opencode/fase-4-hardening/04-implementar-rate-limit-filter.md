# Tarea 4.4 — Implementar RateLimitFilter

**Dependencias:** tarea 4.3 (tests RED)

**Descripción:** Implementar filtro de rate limiting basado en IP + client_id usando la tabla `api_clients` para el límite configurado.

**Criterio de aceptación:**
- [ ] Clase `RateLimitFilter` en `app/Filters/RateLimitFilter.php`
- [ ] `declare(strict_types=1)`
- [ ] Usa `client_id` del request (si está autenticado) o IP como clave de rate limit
- [ ] Lee `rate_limit_per_minute` del cliente desde `api_clients`; si no hay cliente, usa valor por defecto de `.env`
- [ ] Almacena contadores en BD (`api_llm_sessions` u otra tabla de rate limiting) o en caché de archivos (sin Redis)
- [ ] Ventana deslizante de 60 segundos
- [ ] Responde 429 con `ErrorResponse` y cabecera `Retry-After`
- [ ] Registrado en `app/Config/Filters.php` con alias `rateLimit`
- [ ] Se coloca PRIMERO en la pipeline (antes de auth, para no gastar CPU)
- [ ] PHPDoc en español
- [ ] Los 2 tests de `RateLimitTest` pasan a verde

**Ficheros implicados:**
- `app/Filters/RateLimitFilter.php`
- `app/Config/Filters.php`
