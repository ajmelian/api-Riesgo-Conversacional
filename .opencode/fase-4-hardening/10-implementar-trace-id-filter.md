# Tarea 4.10 — Implementar TraceIdFilter

**Dependencias:** fase-0

**Descripción:** Implementar filtro que genera o propaga el `X-Trace-Id` en todas las peticiones.

**Criterio de aceptación:**
- [ ] Clase `TraceIdFilter` en `app/Filters/TraceIdFilter.php`
- [ ] `declare(strict_types=1)`
- [ ] Si la petición entrante tiene `X-Trace-Id` → lo propaga
- [ ] Si no → genera UUID v4 y lo inyecta
- [ ] Añade `X-Trace-Id` a la respuesta
- [ ] Inyecta `trace_id` en el request para servicios posteriores
- [ ] Registrado en `app/Config/Filters.php` con alias `traceId`
- [ ] Se coloca PRIMERO en la pipeline (antes que rate limit)
- [ ] PHPDoc en español

**Ficheros implicados:**
- `app/Filters/TraceIdFilter.php`
- `app/Config/Filters.php`
