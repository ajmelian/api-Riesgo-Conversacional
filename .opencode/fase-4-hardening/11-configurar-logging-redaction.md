# Tarea 4.11 — Configurar logging con redacción de secretos

**Dependencias:** tarea 4.9 (LogRedactionTest RED)

**Descripción:** Configurar el logger de CI4 para que nunca registre cabeceras o valores sensibles.

**Criterio de aceptación:**
- [ ] Configurar `app/Config/Logger.php` o equivalente
- [ ] Lista negra de claves a redactar: `Authorization`, `X-Client-Secret`, `X-LLM-Token`, `Cookie`, `Set-Cookie`, `token`, `secret`, `password`, `key`
- [ ] Redacción aplica a `$_SERVER`, cabeceras de request, y cualquier array logueado
- [ ] `LOG_SECRET_REDACTION = true` en `.env` activa la funcionalidad
- [ ] `CI_ENVIRONMENT=production` → `LOG_THRESHOLD` adecuado (solo errores)
- [ ] Los 2 tests de `LogRedactionTest` pasan a verde

**Ficheros implicados:**
- `app/Config/Logger.php`
- `app/Config/Filters.php` (registrar orden correcto de todos los filtros)
