# Tarea 0.2 — Configurar .env

**Dependencias:** tarea 0.1

**Descripción:** Copiar `env` a `.env` y configurar todas las variables de entorno requeridas: base de datos, clave de aplicación, clave de cifrado de tokens LLM, límites, logging.

**Criterio de aceptación:**
- [ ] `CI_ENVIRONMENT = development` (para desarrollo local; `production` en despliegue)
- [ ] `app.baseURL` configurado
- [ ] `database.default.*` configurado con hostname, database, username, password, DBDriver=MySQLi, charset=utf8mb4
- [ ] `APP_KEY` generado (32+ bytes, hexadecimal o base64)
- [ ] `LLM_TOKEN_ENCRYPTION_KEY` generado (32 bytes aleatorios, base64)
- [ ] `SESSION_TTL_SECONDS = 300`
- [ ] `LLM_ALLOWED_PROVIDERS = openai,anthropic`
- [ ] `OPENAI_BASE_URL` y `ANTHROPIC_BASE_URL` configurados
- [ ] Límites de API configurados: rate limit, max payload, max messages, max message length
- [ ] `LOG_THRESHOLD = 4` y `LOG_SECRET_REDACTION = true`

**Ficheros implicados:**
- `.env`
- `env` (plantilla original de CI4)

**Referencia:** `context/config/env.example`
