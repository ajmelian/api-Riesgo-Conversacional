# AGENTS.md

## Qué es este repo

Paquete de diseño previo a la implementación para una **API de Análisis de Riesgo Conversacional**. No contiene código ejecutable — este repo alberga la especificación, el contrato, el esquema y fragmentos de referencia sobre los que se construirá el repo de implementación. Todo el contenido está bajo `context/`.

## Documentos clave (orden de lectura)

1. `context/proyecto_tecnico_api_analisis_riesgo_conversacional.md` — especificación técnica autoritativa (1043 líneas)
2. `context/openapi/risk-api.v1.yaml` — contrato de la API (OpenAPI 3.1.2); fuente de verdad de los endpoints
3. `context/database/mysql/schema.sql` — esquema inicial MySQL/MariaDB
4. `context/src-snippets/` — implementaciones PHP de referencia (servicio de cifrado, comando de limpieza)

## Restricciones vinculantes (no negociables)

- **Sin contenedores**: Docker, Podman, LXC, Kubernetes, Docker Compose quedan excluidos. Solo Linux sobre hierro.
- **Stack cerrado**: PHP 8.5.x, CodeIgniter 4.7.x, CodeIgniter Shield 1.3.x, MySQL/MariaDB.
- **Sin Redis, sin PostgreSQL** — MySQL/MariaDB como único almacén de datos.
- **Proveedores LLM**: solo OpenAI y Anthropic para el MVP.
- **Sesiones**: TTL de 300 segundos por inactividad, token LLM cifrado con `sodium_crypto_aead_xchacha20poly1305_ietf_encrypt`, token de sesión hasheado (SHA-256), nunca almacenado en claro.
- **Multi-tenancy**: toda consulta y acceso a recursos debe filtrar por `client_id`.

## Convenciones de código (PHP)

- `declare(strict_types=1)` en todos los ficheros.
- camelCase para métodos/variables, PascalCase para clases.
- Autocarga PSR-4, namespace raíz `App\`.
- PHPDoc en todas las clases y métodos usando la **plantilla en español**: `Nombre`, `Descripción de la funcionalidad`, `Parámetros de entrada`, `Parámetros de salida`, `Método de uso`, `Fecha de desarrollo`, `Autor`.
- Sin lógica de negocio en controladores; usar Services.
- Sin concatenación SQL; sin acceso directo a `$_SERVER`/`$_POST`/`$_GET` fuera de capas controladas.
- Secretos mediante `.env`, nunca hardcodeados.

## Reglas de logging

Nunca registrar: `Authorization`, `X-Client-Secret`, `X-LLM-Token`, `Cookie`, `Set-Cookie`, tokens LLM descifrados, tokens de sesión en claro, prompts completos con datos personales.

## Modelo de autenticación (tres tipos de credenciales separadas)

1. **Usuarios internos administradores** → CodeIgniter Shield (login de backoffice, roles)
2. **Credenciales del cliente integrador** → cabeceras `X-Client-Id` + `X-Client-Secret`
3. **Token del proveedor LLM** → cabeceras `X-LLM-Provider` + `X-LLM-Token` (propiedad del cliente, nunca persistido en claro)

## Shield vs autenticación API (dos sistemas separados)

CodeIgniter Shield **solo** gestiona usuarios internos del backoffice. No debe usarse para autenticar peticiones de la API pública.

| Sistema | Para quién | Cómo autentica |
|---------|-----------|----------------|
| CodeIgniter Shield | Usuarios internos (admin, auditor, moderator) | Login web con sesión de navegador |
| Capa propia (`api_clients`) | Clientes integradores (máquina a máquina) | Cabeceras `X-Client-Id` + `X-Client-Secret` |
| Sesiones LLM | Clientes integradores (análisis) | Token Bearer `riskapi_sess_*` |

Shield puede asociar un `api_client` a un usuario propietario para trazabilidad administrativa, pero nunca se usa para autenticar llamadas a la API pública.

## Flujo de desarrollo SDD + TDD

Para cualquier endpoint nuevo o modificación de uno existente, se sigue este orden:

```
Paso 1 → Congelar/escribir el schema en OpenAPI (spec)
Paso 2 → Test de contrato (RED) — valida request/response contra el YAML
Paso 3 → Test de integración (RED) — HTTP completo con BD de test
Paso 4 → Tests unitarios del servicio (RED) — aislados con mocks
Paso 5 → Implementar el servicio (GREEN)
Paso 6 → Implementar controlador + filtro + ruta (GREEN)
Paso 7 → Ejecutar suite completa (verificar que nada se rompe)
Paso 8 → Refactorizar si procede, manteniendo tests verdes
```

Nunca se implementa un endpoint sin que sus tests de contrato e integración estén escritos primero.

## Arquitectura de filtros (orden y responsabilidad)

El orden de la pipeline de filtros es crítico. Se registran en `app/Config/Filters.php`:

```
Petición entrante
  → TraceIdFilter           (genera/propaga X-Trace-Id en toda petición)
  → RateLimitFilter         (rechaza antes de gastar CPU en auth)
  → ApiClientAuthFilter     (valida X-Client-Id + X-Client-Secret, inyecta client_id)
  → BearerSessionFilter     (valida Authorization: Bearer, inyecta session_id)
  → TenantIsolationFilter   (garantiza filtrado por client_id en toda query)
  → Controller
```

Si el orden se invierte (ej. TenantIsolation antes que ApiClientAuth), no hay `client_id` que filtrar y el sistema falla.

## Interfaces/contratos internos para adaptadores LLM

El diseño prevé añadir más proveedores LLM tras el MVP. Se define la interfaz desde el día 1:

```php
interface LlmProviderInterface
{
    public function validateToken(string $token): bool;
    public function analyze(string $prompt, array $messages): array;
}
```

`OpenAiProviderService` y `AnthropicProviderService` implementan esa interfaz. Un factory (`LlmProviderFactory`) resuelve el adaptador según `X-LLM-Provider`.

## Manejo de errores y excepciones

Las excepciones de los servicios burbujean hasta el controlador como respuestas HTTP estandarizadas:

| Excepción | Código HTTP |
|-----------|-------------|
| `App\Exceptions\UnauthorizedException` | 401 |
| `App\Exceptions\ValidationException` | 422 |
| `App\Exceptions\RateLimitException` | 429 |
| `App\Exceptions\AnalysisException` | 400 |

El controlador captura en un `try/catch` o un filter `ExceptionHandler` y devuelve siempre `ErrorResponse {error, message, traceId}`. Nunca se expone el stack trace en producción (`CI_ENVIRONMENT=production`).

## Gestión de versiones de la API y rutas

Convención de versionado mediante grupos de rutas en CI4:

```php
// app/Config/Routes.php
$routes->group('v1', ['namespace' => 'App\Controllers\Api\V1'], function ($routes) {
    $routes->get('health', 'Health::index');
    $routes->post('auth/llm-session', 'Auth::createSession');
    $routes->delete('auth/llm-session', 'Auth::revokeSession');
    $routes->post('conversations/analyze', 'Analysis::analyze');
});
```

Los controladores viven en `app/Controllers/Api/V1/`. Para una v2 futura se crea `app/Controllers/Api/V2/` y se añade otro grupo `$routes->group('v2', ...)`.

## Testing con base de datos

CI4 tiene particularidades para tests con BD:

```php
// Test de integración — hereda de CIDatabaseTestCase
class FooIntegrationTest extends \CodeIgniter\Test\CIDatabaseTestCase
{
    protected $refresh = true;        // migra + siembra antes de cada test
    protected $namespace = 'App';     // namespace de migraciones
    protected $seed = 'App\Database\Seeds\TestSeeder';
    protected $basePath = APPPATH . 'Database/';
}

// Test unitario — sin BD, usa mocks
class FooServiceTest extends \CodeIgniter\Test\CIUnitTestCase
{
    // mockear dependencias con Mockery o CI4 Services::injectMock()
}
```

Configurar `phpunit.xml.dist` con:
- `DBDriver = SQLite3` y `database = ':memory:'` para tests unitarios
- Variables de entorno de test en el bloque `<php>` del XML

## Comandos spark y testing (recetario ejecutable)

```
# Migraciones
php spark make:migration CreateFooTable        # crear migración
php spark migrate --all                         # aplicar todas
php spark migrate:rollback --all                # revertir todas
php spark migrate:status                        # ver estado

# Seeders
php spark make:seeder FooSeeder                 # crear seeder
php spark db:seed FooSeeder                     # ejecutar un seeder

# Tests
php vendor/bin/phpunit                          # suite completa
php vendor/bin/phpunit --filter=HealthContract  # un solo test
php vendor/bin/phpunit --testsuite=contract     # suite de contrato
php vendor/bin/phpunit --coverage-text          # cobertura

# Limpieza de sesiones
php spark riskapi:cleanup-llm-sessions          # elimina sesiones expiradas/revocadas (ejecutar cada minuto vía cron/systemd)
```

## Comandos CLI

## Despliegue (sobre hierro)

- Web root: `/var/www/risk-api/current/public`
- Composer: `composer install --no-dev --optimize-autoloader`
- Migraciones: `php spark migrate --all`
- Health check: `GET /v1/health`
- Entrada cron de limpieza en `context/ops/risk-api-cleanup.cron.example`
- Configuración Nginx de referencia en `context/ops/nginx-site.example.conf`
- Plantilla `.env` en `context/config/env.example`

## Definition of Done (DoD) — MVP 0.1.0

Cada ítem es verificable objetivamente y debe cumplirse antes de etiquetar una versión como terminada. La metodología es SDD + TDD: primero el test de contrato contra OpenAPI, luego tests de integración, luego tests unitarios, luego implementación.

### 1. Contrato OpenAPI (Spec-Driven)

- [ ] `GET /v1/health` → 200 con JSON válido contra schema `HealthResponse`
- [ ] `POST /v1/auth/llm-session` → 201 contra `LlmSessionResponse`; errores 400/401/422/429 contra `ErrorResponse`
- [ ] `DELETE /v1/auth/llm-session` → 204 sin body; error 401 contra `ErrorResponse`
- [ ] `POST /v1/conversations/analyze` → 200 contra `ConversationAnalysisResponse`; errores 400/401/413/422/429 contra `ErrorResponse`/`ValidationErrorResponse`

Verificación: `tests/Contract/` → 4 tests con `league/openapi-psr7-validator` en verde.

### 2. Completitud funcional (TDD)

**Autenticación y sesiones**
- [ ] `POST /v1/auth/llm-session` crea sesión de 300s TTL con token LLM cifrado (libsodium) y token de sesión hasheado (SHA-256)
- [ ] El token de sesión nunca se almacena en claro en BD
- [ ] La sesión extiende `expires_at` en cada llamada válida
- [ ] Sesión expirada (>300s sin actividad) o revocada → 401
- [ ] `DELETE /v1/auth/llm-session` marca `revoked_at` y la sesión deja de ser válida

**Análisis de conversación**
- [ ] `POST /v1/conversations/analyze` devuelve `riskLevel` (1-5), `confidence` (0.00-1.00), `requiresHumanReview` (true si nivel ≤2)
- [ ] `automaticExternalNotification` siempre `false`; evidencias ≤1000 caracteres con `messageId`, `category`, `excerpt`, `reason`
- [ ] Rechazo de payloads inválidos: participantes [2,20], mensajes [1,500], texto >5000 chars → 422
- [ ] Minimización de datos antes de enviar al LLM (sin IPs, emails, nombres reales)
- [ ] Respuesta del LLM validada como JSON antes de procesarse

**Operaciones**
- [ ] `GET /v1/health` sin autenticación devuelve `{status:"ok", timestamp:ISO8601}`
- [ ] `php spark riskapi:cleanup-llm-sessions` elimina sesiones con `expires_at < NOW()` o `revoked_at IS NOT NULL`

Verificación: `tests/Unit/` (~41 tests) + `tests/Integration/` (~16 tests) todos verdes.

### 3. Seguridad

- [ ] **Multi-tenancy**: cliente A no accede a sesiones ni análisis del cliente B (tests `TenantIsolationTest`)
- [ ] **Rate limiting**: >60 req/min desde misma IP/cliente → 429 con cabecera `Retry-After`
- [ ] **Secretos en reposo**: `client_secret_hash` es hash bcrypt/argon2; `llm_token_ciphertext` siempre cifrado con nonce único
- [ ] **Logs sin secretos**: nunca se registra `Authorization`, `X-Client-Secret`, `X-LLM-Token`, `Cookie`, `Set-Cookie`, tokens descifrados ni tokens de sesión en claro (test `LogRedactionTest`)
- [ ] **Cabeceras HTTP de seguridad**: `X-Frame-Options:DENY`, `X-Content-Type-Options:nosniff`, `Strict-Transport-Security`, `Referrer-Policy:no-referrer`
- [ ] **Debug desactivado**: `CI_ENVIRONMENT=production` → sin stack traces en errores
- [ ] **Payload máximo**: 1 MB validado en CI4 + nginx `client_max_body_size`

### 4. Calidad de código

- [ ] `declare(strict_types=1)` en todos los ficheros `.php` propios
- [ ] PHPDoc en español (plantilla completa: `Nombre`, `Descripción de la funcionalidad`, `Parámetros de entrada`, `Parámetros de salida`, `Método de uso`, `Fecha de desarrollo`, `Autor`) en todas las clases y métodos públicos
- [ ] Cero lógica de negocio en controladores; toda la lógica en `App\Services\*`
- [ ] Cero concatenación SQL; todo mediante Query Builder de CI4 o consultas preparadas
- [ ] Cero acceso directo a `$_SERVER`, `$_POST`, `$_GET` fuera de filtros controlados
- [ ] Sin secretos hardcodeados; todos los secretos desde `.env`
- [ ] Namespaces PSR-4 bajo `App\`; camelCase para métodos/variables; PascalCase para clases
- [ ] Todos los tests pasan sin warnings ni errores de PHP

### 5. Base de datos

- [ ] Las 5 migraciones ejecutan limpias: `php spark migrate --all` sin errores
- [ ] Las 5 migraciones revierten limpias: `php spark migrate:rollback --all` sin errores
- [ ] Seeder de `api_clients` con al menos un cliente de prueba activo (`public_id`, `name`, `client_secret_hash`, `allowed_llm_providers=["openai","anthropic"]`, `rate_limit_per_minute=60`, `monthly_quota=10000`)
- [ ] FK declaradas con `ON DELETE CASCADE` (sesiones/análisis/evidencias) y `ON DELETE SET NULL` (auditoría)
- [ ] Charset `utf8mb4`, collation `utf8mb4_unicode_ci`, timestamps en UTC

### 6. Despliegue y operaciones

- [ ] `composer install --no-dev --optimize-autoloader` sin errores
- [ ] `.env` fuera del repositorio con `APP_KEY`, `LLM_TOKEN_ENCRYPTION_KEY`, `database.default.*`, `SESSION_TTL_SECONDS`
- [ ] Nginx/Apache con `root → /var/www/risk-api/current/public` y HTTPS/TLS 1.3
- [ ] Cron cada minuto: `php spark riskapi:cleanup-llm-sessions`
- [ ] Rotación de logs en `/var/log/risk-api/`
- [ ] Health check externo: `curl -fsS https://<dominio>/v1/health` → 200 OK

## Definition of Success (DoS) — MVP 0.1.0

El DoD define _cuándo terminamos de construir_. El DoS define _si lo construido funciona en producción_. Se mide tras la implantación, no antes.

### 1. Fiabilidad operacional

| Métrica | Objetivo |
|---------|----------|
| Disponibilidad | ≥ 99.5% mensual |
| Latencia p50 health | ≤ 50ms |
| Latencia p95 sesión LLM | ≤ 500ms |
| Latencia p95 análisis | ≤ 5s (incluye llamada al proveedor LLM) |
| Tasa de error 5xx | < 0.5% del total de requests |

### 2. Precisión del análisis

| Métrica | Objetivo |
|---------|----------|
| Falsos positivos | < 20% de análisis con `requiresHumanReview=true` descartados por revisor humano |
| Falsos negativos | 0 incidentes donde la API dio nivel 5 y existía riesgo real |
| Concordancia humano-máquina | ≥ 70% de coincidencia entre `riskLevel` de la API y el criterio del revisor |
| Evidencia relevante | ≥ 80% de las evidencias son señaladas como relevantes por el revisor |

### 3. Seguridad

| Métrica | Objetivo |
|---------|----------|
| Fugas de token LLM | 0 incidentes |
| Acceso cruzado multi-tenant | 0 incidentes |
| Secretos en logs | 0 ocurrencias en producción |
| Vulnerabilidades en dependencias | 0 críticas/altas (`composer audit`) |
| Cumplimiento OWASP ASVS | Nivel 2 superado en controles aplicables |
| EIPD | Completada antes de procesar datos de menores |

### 4. Adopción y escalabilidad

| Métrica | Objetivo |
|---------|----------|
| Clientes en producción | ≥ 1 activo en el primer trimestre |
| Volumen de análisis | ≥ 50 análisis/mes en el primer trimestre |
| Rate limiting efectivo | < 5% de requests devuelven 429 por cliente bien configurado |
| Cuota mensual | Ningún cliente supera su cuota sin previo aviso |

### 5. Eficiencia de costes

| Métrica | Objetivo |
|---------|----------|
| Coste LLM por análisis | ≤ 0.05 USD/análisis |
| Sesiones huérfanas | 0 sesiones activas con >24h sin actividad |
| Tamaño medio de payload | ≤ 50 KB por análisis (tras minimización) |

### 6. Mantenibilidad y DevOps

| Métrica | Objetivo |
|---------|----------|
| Cobertura de tests | ≥ 80% de líneas |
| Tiempo de suite de tests | ≤ 90s (unitarios + integración + contrato) |
| Tiempo de despliegue | ≤ 3 minutos desde `git push` a health check verde |
| Rollback | ≤ 2 minutos desde decisión a servicio restaurado |
| Rotación de logs | Configurada y verificada, sin llenar disco |

### 7. Experiencia del cliente integrador

| Métrica | Objetivo |
|---------|----------|
| Tiempo hasta primera sesión | ≤ 10 minutos desde recepción de credenciales |
| Documentación OpenAPI | SDK o colección generada automáticamente desde el spec |
| Errores auto-diagnosticables | ≥ 90% de errores incluyen `traceId` correlacionable con logs |
| Soporte | Tiempo de respuesta < 24h para incidencias no críticas |

### Criterio de éxito consolidado

El proyecto se considera exitoso si tras 30 días en producción se cumplen simultáneamente:

```
✓ 0 incidentes de seguridad (fugas, acceso cruzado, secretos en logs)
✓ 0 falsos negativos reportados (riesgo real no detectado)
✓ ≥ 1 cliente integrador activo con ≥ 50 análisis/mes
✓ Coste LLM ≤ 0.05 USD/análisis
✓ Cobertura de tests ≥ 80%, suite completa en ≤ 90s
✓ Health check 200 OK 24/7
```
