# Proyecto Técnico: API de Análisis de Riesgo Conversacional para Protección Digital

**Versión:** 0.2.0  
**Fecha de actualización:** 18/06/2026  
**Autor:** Aythami Melián Perdomo  
**Estado:** Proyecto técnico actualizado para procesamiento, desarrollo e implantación  
**Formato:** Markdown  
**Stack técnico objetivo:** PHP 8.5.x, CodeIgniter 4.7.x, CodeIgniter Shield 1.3.x, OpenAPI 3.1.2, MySQL/MariaDB, servidor Linux sin contenedores, HTTPS/TLS 1.3, proveedores LLM OpenAI y Anthropic Claude.  
**Restricción expresa de implantación:** no se utilizará Docker ni ningún otro sistema de contenedores.

---

## 1. Resumen ejecutivo

Este documento define el proyecto técnico para el diseño, desarrollo, securización, despliegue e implantación de una API multi-cliente destinada al análisis de conversaciones entre usuarios en chats, foros, plataformas educativas, comunidades online, entornos gaming, intranets o soluciones de terceros.

La API permitirá que plataformas externas envíen conversaciones entre dos o más participantes para ser analizadas mediante modelos de lenguaje accesibles por API, inicialmente OpenAI y Anthropic Claude. El objetivo es detectar indicios de acoso, bullying, ciberbullying, maltrato psicológico, vejaciones, amenazas, coacción, humillaciones, discriminación, grooming, riesgo de autolesión, riesgo contra la vida o integridad de una persona y violencia digital contra menores.

La API no actuará como juez automatizado ni ejecutará comunicaciones externas automáticas. Su función será generar una clasificación técnica de riesgo, aportar evidencias mínimas, calcular un nivel de alerta, recomendar una acción operativa y determinar si es necesaria revisión humana.

El proyecto se diseña bajo enfoque API-first mediante OpenAPI 3.1.2, seguridad desde el diseño, privacidad desde el diseño, mínima retención de secretos, cifrado de tokens LLM temporales, multi-tenancy estricto, auditoría, cumplimiento RGPD, controles OWASP API Security Top 10 y verificación técnica mediante OWASP ASVS.

---

## 2. Decisiones técnicas vinculantes

Las siguientes decisiones quedan fijadas para el desarrollo del MVP y para la primera implantación productiva:

| Área | Decisión |
|---|---|
| Lenguaje | PHP 8.5.x, rama estable actual de PHP 8. |
| Framework | CodeIgniter 4.7.x. |
| Autenticación y autorización interna | CodeIgniter Shield 1.3.x. |
| Contrato API | OpenAPI 3.1.2. |
| Base de datos | MySQL o MariaDB. |
| Almacenamiento de sesiones efímeras | MySQL/MariaDB, sin Redis en el diseño base. |
| Contenedores | Quedan excluidos Docker, Podman, LXC y cualquier otro sistema de contenedores. |
| Servidor de aplicación | Nginx + PHP-FPM o Apache + PHP-FPM/mod_proxy_fcgi. |
| Gestión de procesos | systemd, cron/systemd timers y servicios nativos del sistema operativo. |
| Proveedores LLM iniciales | OpenAI y Anthropic Claude. |
| Seguridad de transporte | HTTPS obligatorio, TLS 1.3 cuando esté disponible. |
| Estilo de desarrollo | POO, Clean Code, PHPDoc, tipado estricto y camelCase. |

La elección de MySQL/MariaDB como único sistema gestor de base de datos implica que las sesiones efímeras, auditoría, clientes API, análisis, evidencias y políticas de moderación se modelarán sobre tablas relacionales.

---

## 3. Referencias normativas y técnicas

El proyecto deberá alinearse, como mínimo, con las siguientes referencias:

- OpenAPI Specification 3.1.2.
- OWASP API Security Top 10 2023.
- OWASP Application Security Verification Standard.
- OWASP Cheat Sheet Series, especialmente API Security, Authentication, Secrets Management, Logging, Input Validation y Cryptographic Storage.
- Reglamento General de Protección de Datos, Reglamento (UE) 2016/679.
- Ley Orgánica 3/2018, de Protección de Datos Personales y garantía de los derechos digitales.
- Ley Orgánica 8/2021, de protección integral a la infancia y la adolescencia frente a la violencia.
- Guías de la AEPD sobre evaluaciones de impacto, menores, tratamientos de alto riesgo y privacidad desde el diseño.
- Buenas prácticas de seguridad de proveedores LLM sobre custodia de claves API.

---

## 4. Visión del producto

El producto será una API integrable en plataformas de terceros para analizar conversaciones y devolver un resultado estructurado de riesgo conversacional. La API debe poder operar en modo SaaS multi-cliente o en despliegue dedicado para un cliente concreto, pero la primera especificación se orienta a un SaaS multi-cliente.

La API debe ofrecer:

- Autenticación de cliente integrador.
- Sesión efímera asociada al token LLM del cliente.
- Análisis de conversaciones mediante reglas deterministas y LLM.
- Clasificación de riesgo tipo DEFCON invertido, donde 5 significa normalidad y 1 significa riesgo crítico.
- Detección de sentimiento conversacional.
- Evidencia mínima justificativa.
- Identificación prudente del posible participante afectado y del posible participante generador de riesgo.
- Recomendación operativa.
- Marca de revisión humana obligatoria cuando proceda.
- Auditoría técnica sin almacenar secretos ni información innecesaria.
- Soporte inicial para OpenAI y Anthropic Claude.

---

## 5. Objetivos

### 5.1 Objetivo general

Desarrollar una API segura, multi-cliente y documentada mediante OpenAPI que permita analizar conversaciones de terceros para detectar indicios de riesgo conversacional, especialmente en contextos donde puedan existir menores o situaciones de acoso, amenaza, vejación o violencia digital.

### 5.2 Objetivos técnicos

- Implementar el backend en PHP 8.5.x.
- Usar CodeIgniter 4.7.x como framework principal.
- Integrar CodeIgniter Shield 1.3.x para autenticación, autorización y gestión de usuarios internos, panel administrativo y permisos.
- Implementar autenticación machine-to-machine para clientes API mediante `X-Client-Id` y `X-Client-Secret`.
- Implementar sesiones LLM efímeras con TTL de 300 segundos por inactividad.
- Cifrar el token LLM temporal antes de almacenarlo en MySQL/MariaDB.
- Almacenar únicamente hash del token de sesión propio de la API.
- Validar todas las entradas mediante reglas de CodeIgniter, JSON Schema y contrato OpenAPI.
- Generar documentación API desde OpenAPI.
- Implantar sin contenedores, usando servicios nativos del sistema operativo.

### 5.3 Objetivos funcionales

- Permitir crear una sesión LLM efímera.
- Permitir revocar sesión LLM.
- Permitir analizar conversaciones.
- Permitir consultar estado operativo de la API.
- Permitir configurar políticas de riesgo por cliente en una fase posterior.
- Permitir auditar análisis, eventos de seguridad y accesos administrativos.

### 5.4 Objetivos de seguridad

- Evitar almacenamiento persistente de claves LLM.
- Evitar logs con secretos.
- Reducir exposición de datos personales.
- Aplicar cifrado en tránsito y en reposo.
- Aplicar rate limiting y control de consumo.
- Aislar clientes mediante multi-tenancy estricto.
- Evitar autorización rota a nivel de objeto.
- Evitar consumo no controlado de recursos y costes LLM.
- Evitar prompt injection y manipulación de instrucciones internas.

---

## 6. Fuera de alcance inicial

Quedan fuera del MVP:

- Denuncias automáticas a organismos públicos.
- Bloqueos legales automáticos sin revisión humana.
- Panel completo de moderación en tiempo real.
- Webhooks productivos de escalado externo.
- Compatibilidad con proveedores LLM adicionales.
- Entrenamiento de modelos propios.
- Aplicaciones móviles.
- Despliegue en contenedores.
- Kubernetes, Docker Compose, Swarm, Podman o similares.
- PostgreSQL, Redis, MongoDB u otros sistemas de persistencia alternativos.

---

## 7. Principios de diseño

### 7.1 API-first

La especificación OpenAPI será el contrato técnico principal. Ningún endpoint se implementará sin estar previamente definido en OpenAPI.

### 7.2 Seguridad desde el diseño

El sistema se diseñará considerando amenazas desde el inicio: robo de tokens LLM, abuso de consumo, acceso cruzado entre clientes, inyección de prompts, manipulación de payloads, exposición de evidencias y errores de autorización.

### 7.3 Privacidad desde el diseño

Solo se tratarán los datos mínimos necesarios. La IP, identificadores internos, fragmentos de conversación y evidencias se considerarán datos personales o potencialmente personales.

### 7.4 No decisión automatizada plena

La API emitirá recomendaciones técnicas. No ejecutará denuncias automáticas ni declarará culpabilidad. En niveles críticos marcará `requiresHumanReview: true`.

### 7.5 Evidencia mínima

La API devolverá fragmentos mínimos necesarios para justificar la alerta, no transcripciones completas salvo que el cliente lo haya solicitado y exista base legal suficiente.

### 7.6 Multi-tenancy estricto

Todo registro estará asociado a un `clientId`. Las consultas, análisis, sesiones, evidencias y políticas se filtrarán siempre por cliente autenticado.

### 7.7 Sesiones efímeras con secretos cifrados

El token LLM del cliente solo se almacenará temporalmente, cifrado con una clave maestra del sistema, y será eliminado tras 5 minutos de inactividad o revocación explícita.

---

## 8. Terminología

| Término | Definición |
|---|---|
| Cliente integrador | Plataforma externa que usa la API. |
| Usuario final | Persona que conversa dentro de la plataforma del cliente integrador. |
| Participante afectado candidato | Usuario que podría estar siendo atacado, acosado o presionado. |
| Participante generador de riesgo candidato | Usuario que podría estar generando riesgo conversacional. |
| Proveedor LLM | Servicio externo usado para análisis lingüístico y contextual. |
| Token LLM | Clave API del proveedor LLM propiedad del cliente integrador. |
| Sesión LLM | Sesión temporal creada por la API para usar el token LLM cifrado durante 300 segundos de inactividad. |
| Evidencia | Fragmento mínimo que justifica una alerta. |
| DEFCON conversacional | Escala de riesgo de 5 a 1, donde 5 es normalidad y 1 es riesgo crítico. |

---

## 9. Modelo de riesgo conversacional

| Nivel | Estado | Descripción | Acción recomendada |
|---|---|---|---|
| 5 | Normal | Conversación ordinaria sin hostilidad relevante. | No actuar. |
| 4 | Tensión leve | Lenguaje brusco, discusión, insulto aislado o hostilidad leve. | Aviso suave o nudging educativo. |
| 3 | Riesgo moderado | Acoso incipiente, vejación, humillación o intimidación repetida. | Aviso formal, strike y revisión de moderación. |
| 2 | Riesgo alto | Amenaza explícita, acoso grave, coacción, grooming inicial o presión psicológica grave. | Bloqueo preventivo temporal y revisión humana obligatoria. |
| 1 | Riesgo crítico | Riesgo inminente contra la vida, autolesión, abuso, extorsión, grooming avanzado o violencia grave. | Intervención humana urgente y conservación reforzada de evidencias. |

La API no debe ejecutar automáticamente comunicaciones externas. Puede devolver `legalEscalationCandidate: true`, pero la decisión queda en manos del responsable de tratamiento y su equipo humano autorizado.

---

## 10. Arquitectura lógica

### 10.1 Componentes

- **API HTTP CodeIgniter 4:** expone endpoints REST definidos en OpenAPI.
- **Filtros de autenticación:** validan cliente integrador, sesión Bearer, rate limits y permisos.
- **CodeIgniter Shield:** gestiona usuarios internos, administradores, grupos, permisos, panel administrativo y autenticación de backoffice.
- **Módulo de clientes API:** gestiona `clientId`, `clientSecret`, estado, scopes, límites y configuración.
- **Módulo de sesiones LLM:** crea, cifra, valida, renueva y elimina sesiones efímeras.
- **Módulo de proveedores LLM:** abstrae OpenAI y Anthropic Claude mediante adaptadores.
- **Motor de reglas deterministas:** detecta patrones críticos sin depender únicamente del LLM.
- **Motor LLM:** solicita análisis semántico controlado al proveedor configurado.
- **Motor de scoring:** combina reglas, LLM, contexto e histórico.
- **Módulo de auditoría:** registra eventos sin secretos.
- **Base de datos MySQL/MariaDB:** almacena clientes, sesiones temporales cifradas, análisis, evidencias y auditoría.
- **Tarea programada de limpieza:** elimina sesiones expiradas, evidencias caducadas y registros temporales.

### 10.2 Diagrama lógico sin contenedores

```text
Cliente tercero
    |
    | HTTPS / TLS 1.3
    v
Nginx o Apache
    |
    v
PHP-FPM 8.5.x
    |
    v
CodeIgniter 4.7.x + Shield 1.3.x
    |        |            |
    |        |            +--> Adaptador OpenAI / Anthropic
    |        |
    |        +--> Motor de reglas y scoring
    |
    +--> MySQL/MariaDB
             |
             +--> api_clients
             +--> api_llm_sessions
             +--> conversation_analyses
             +--> conversation_analysis_evidence
             +--> api_audit_logs
```

---

## 11. Stack técnico de desarrollo

### 11.1 PHP

Se usará PHP 8.5.x con `declare(strict_types=1);` en todos los ficheros propios. Se exigirá tipado estricto, clases finales cuando proceda, inyección de dependencias, enums, DTOs, servicios desacoplados y PHPDoc completo.

Extensiones PHP recomendadas:

- `mbstring`
- `intl`
- `json`
- `curl`
- `openssl`
- `sodium`
- `pdo_mysql`
- `mysqli`, solo si algún componente heredado lo requiere
- `fileinfo`
- `gd` o `imagick`, solo si en el futuro se procesan adjuntos visuales
- `opcache`

### 11.2 CodeIgniter 4

CodeIgniter 4.7.x será el framework base. Se usarán:

- Controllers RESTful.
- Filters para autenticación, autorización, rate limiting y tenant isolation.
- Services para lógica de negocio.
- Models para persistencia.
- Entities o DTOs para transporte interno.
- Validation para reglas de entrada.
- Events para auditoría desacoplada.
- CLI Commands para limpieza programada.
- Config classes para proveedores LLM, seguridad y límites.

### 11.3 CodeIgniter Shield

Shield se utilizará para:

- Usuarios internos de administración.
- Login seguro del backoffice.
- Grupos y permisos administrativos.
- Gestión de roles como `superAdmin`, `securityAdmin`, `clientManager`, `auditor`, `moderator`.
- Integración con políticas de permisos para operaciones administrativas.

Para clientes API machine-to-machine se implementará una capa propia basada en `api_clients`, `clientSecretHash`, scopes y límites. Shield podrá asociar cada cliente API a un usuario propietario o cuenta de servicio, pero no se dependerá exclusivamente de sesiones de usuario tradicionales para autenticación entre servidores.

### 11.4 MySQL/MariaDB

La base de datos será MySQL o MariaDB. El diseño SQL debe funcionar con ambas tecnologías siempre que sea posible.

Recomendaciones:

- Juego de caracteres `utf8mb4`.
- Collation `utf8mb4_unicode_ci` o equivalente moderna.
- Motor InnoDB.
- Timestamps en UTC.
- Índices por `client_id`, `expires_at`, `created_at`, `risk_level` y hashes de tokens.
- Separación clara entre identificadores internos numéricos y referencias públicas opacas.

---

## 12. Modelo de autenticación

### 12.1 Separación de credenciales

El sistema manejará tres tipos de credenciales:

1. **Credenciales internas de administración:** gestionadas por Shield.
2. **Credenciales del cliente integrador:** `X-Client-Id` y `X-Client-Secret`.
3. **Credenciales del proveedor LLM:** `X-LLM-Provider` y `X-LLM-Token`, propiedad del cliente integrador.

Estas credenciales no deben mezclarse. El token LLM no identifica al cliente dentro de nuestra API. El cliente se identifica con sus propias credenciales.

### 12.2 Creación de sesión LLM

Endpoint:

```http
POST /v1/auth/llm-session
X-Client-Id: client_xxxxx
X-Client-Secret: ********
X-LLM-Provider: openai
X-LLM-Token: sk-...
```

Flujo:

1. Validar formato de headers.
2. Buscar cliente por `client_public_id`.
3. Verificar `clientSecret` mediante `password_verify()` o HMAC seguro.
4. Comprobar que el cliente está activo.
5. Comprobar scopes y límites contratados.
6. Validar proveedor LLM permitido para el cliente.
7. Validar token LLM mediante llamada mínima al proveedor.
8. Generar token opaco de sesión con alta entropía.
9. Guardar hash del token de sesión.
10. Cifrar token LLM con libsodium.
11. Guardar sesión con `expires_at = now + 300 segundos`.
12. Devolver `sessionToken`.

### 12.3 Uso de sesión

Las llamadas posteriores usarán:

```http
Authorization: Bearer riskapi_sess_...
```

La API calculará hash del token recibido, buscará sesión activa, verificará expiración y actualizará `last_activity_at` y `expires_at` en cada llamada válida.

### 12.4 Revocación explícita

Endpoint:

```http
DELETE /v1/auth/llm-session
Authorization: Bearer riskapi_sess_...
```

La revocación debe borrar o inutilizar el token LLM cifrado y marcar la sesión como revocada.

### 12.5 Expiración por inactividad

Transcurridos 5 minutos sin llamadas válidas, la sesión se considerará expirada. Una tarea programada eliminará físicamente el registro expirado.

### 12.6 Almacenamiento temporal del token LLM

El token LLM nunca se almacenará en claro. La tabla de sesiones solo contendrá:

- Token de sesión propio en hash.
- Proveedor LLM.
- Token LLM cifrado.
- Nonce de cifrado.
- Fingerprint no reversible del token.
- Timestamps de creación, actividad y expiración.

---

## 13. Modelo de datos inicial

### 13.1 `api_clients`

Tabla de clientes integradores.

Campos principales:

- `id`
- `public_id`
- `name`
- `client_secret_hash`
- `status`
- `allowed_llm_providers`
- `rate_limit_per_minute`
- `monthly_quota`
- `created_at`
- `updated_at`

### 13.2 `api_llm_sessions`

Tabla de sesiones efímeras.

Campos principales:

- `id`
- `client_id`
- `session_token_hash`
- `llm_provider`
- `llm_token_ciphertext`
- `llm_token_nonce`
- `llm_token_fingerprint`
- `ip_address`
- `user_agent_hash`
- `created_at`
- `last_activity_at`
- `expires_at`
- `revoked_at`

### 13.3 `conversation_analyses`

Tabla de análisis.

Campos principales:

- `id`
- `public_id`
- `client_id`
- `conversation_external_id`
- `risk_level`
- `risk_label`
- `sentiment`
- `confidence`
- `affected_participant_external_id`
- `risk_actor_participant_external_id`
- `requires_human_review`
- `recommended_action`
- `automatic_external_notification`
- `legal_escalation_candidate`
- `llm_provider`
- `model_name`
- `prompt_version`
- `analysis_version`
- `input_hash`
- `output_hash`
- `created_at`

### 13.4 `conversation_analysis_evidence`

Tabla de evidencias mínimas.

Campos principales:

- `id`
- `analysis_id`
- `message_external_id`
- `category`
- `excerpt`
- `reason`
- `created_at`

### 13.5 `api_audit_logs`

Tabla de auditoría técnica.

Campos principales:

- `id`
- `client_id`
- `actor_type`
- `actor_id`
- `event_type`
- `trace_id`
- `ip_address`
- `user_agent_hash`
- `metadata_json`
- `created_at`

---

## 14. Contrato OpenAPI

El contrato inicial se entrega en el fichero:

```text
openapi/risk-api.v1.yaml
```

Endpoints incluidos en el MVP:

- `POST /v1/auth/llm-session`
- `DELETE /v1/auth/llm-session`
- `POST /v1/conversations/analyze`
- `GET /v1/health`

La especificación debe usarse para:

- Documentación pública.
- Generación de SDKs.
- Validación de requests.
- Validación de responses.
- Pruebas contractuales.
- Inventario de endpoints.

---

## 15. Flujo funcional principal

### 15.1 Creación de sesión

1. El cliente llama al endpoint de sesión.
2. La API valida `X-Client-Id` y `X-Client-Secret`.
3. La API valida el proveedor LLM.
4. La API valida el token LLM con una llamada mínima.
5. La API cifra el token LLM.
6. La API crea una sesión efímera de 300 segundos.
7. La API devuelve un token Bearer propio.

### 15.2 Análisis de conversación

1. El cliente llama a `/v1/conversations/analyze`.
2. La API valida el Bearer token.
3. La API renueva la expiración de la sesión.
4. La API valida el JSON de entrada.
5. La API minimiza datos antes de enviarlos al LLM.
6. La API ejecuta reglas deterministas.
7. La API invoca al proveedor LLM.
8. La API combina resultados.
9. La API genera evidencia mínima.
10. La API registra auditoría sin secretos.
11. La API devuelve el resultado.

### 15.3 Revocación

1. El cliente llama a `DELETE /v1/auth/llm-session`.
2. La API localiza la sesión activa.
3. La API elimina o inutiliza el token LLM cifrado.
4. La API marca `revoked_at`.
5. La API devuelve `204 No Content`.

---

## 16. Motor de análisis

### 16.1 Capa de normalización

Debe normalizar idioma, orden temporal, participantes, saltos de línea, caracteres invisibles, emojis y contenido repetido. No debe destruir evidencia relevante.

### 16.2 Capa de minimización

No se enviarán al LLM datos innecesarios como email, nombre completo, IP o identificadores técnicos internos salvo que sea imprescindible para el caso de uso. El LLM recibirá participantes pseudonimizados.

### 16.3 Capa de reglas deterministas

Debe detectar patrones críticos:

- Amenazas directas.
- Incitación a autolesión.
- Chantaje.
- Coacción.
- Grooming.
- Datos personales expuestos.
- Repetición de hostigamiento.
- Lenguaje discriminatorio grave.

### 16.4 Capa LLM

El LLM recibirá un prompt de sistema no controlable por el usuario final y una conversación minimizada. El output deberá estar restringido a JSON validable.

### 16.5 Scoring final

El scoring final combinará:

- Resultado LLM.
- Reglas deterministas.
- Histórico de strikes del cliente, si se habilita.
- Contexto de menores.
- Reincidencia en la conversación.
- Confianza del modelo.
- Riesgo de falso positivo.

---

## 17. Seguridad OWASP API Security Top 10

### API1: Broken Object Level Authorization

Cada recurso se filtrará por `client_id`. Nunca se permitirá consultar análisis, sesiones o evidencias por identificador externo sin verificar pertenencia al cliente autenticado.

### API2: Broken Authentication

Se aplicarán tokens opacos de alta entropía, hash de tokens, expiración por inactividad, revocación explícita, secretos de cliente hasheados y detección de abuso.

### API3: Broken Object Property Level Authorization

La API no devolverá propiedades internas, tokens, hashes, fingerprints, prompts internos ni campos administrativos.

### API4: Unrestricted Resource Consumption

Se establecerán límites de tamaño de payload, número de mensajes, longitud por mensaje, peticiones por minuto, coste estimado de tokens, concurrencia y cuota mensual.

### API5: Broken Function Level Authorization

Los endpoints administrativos estarán separados de endpoints públicos y protegidos por Shield, grupos y permisos específicos.

### API6: Unrestricted Access to Sensitive Business Flows

Se limitará la creación de sesiones, análisis repetidos y validaciones de tokens LLM para evitar abuso económico o ataques de fuerza bruta.

### API7: Server Side Request Forgery

Los adaptadores LLM solo podrán llamar a dominios permitidos por configuración. No se aceptarán URLs arbitrarias proporcionadas por clientes.

### API8: Security Misconfiguration

No habrá debug en producción, no se expondrán stack traces, se usarán cabeceras de seguridad y los permisos de ficheros serán restrictivos.

### API9: Improper Inventory Management

OpenAPI será el inventario oficial. Los endpoints obsoletos se versionarán y retirarán con política documentada.

### API10: Unsafe Consumption of APIs

Las respuestas de proveedores LLM serán validadas como datos no confiables. Se aplicarán timeouts, retries controlados, circuit breakers y validación de JSON.

---

## 18. Gestión de secretos

### 18.1 Secretos del sistema

Secretos gestionados mediante variables de entorno o ficheros de configuración fuera del repositorio:

- `APP_KEY`
- `database.default.password`
- `LLM_TOKEN_ENCRYPTION_KEY`
- Claves de firma internas.
- Secretos de cliente generados.

### 18.2 Cifrado del token LLM

El token LLM temporal se cifrará con `sodium_crypto_aead_xchacha20poly1305_ietf_encrypt()`. La clave de cifrado deberá tener longitud válida y no estará en base de datos.

### 18.3 Logs prohibidos

Queda prohibido registrar:

- `X-Client-Secret`
- `X-LLM-Token`
- `Authorization`
- Token LLM descifrado
- Token de sesión en claro
- Prompt completo con datos personales
- Respuestas completas del proveedor si contienen datos personales innecesarios

---

## 19. Validación de entrada

Todas las entradas deben validarse en tres niveles:

1. Contrato OpenAPI.
2. Reglas de validación CodeIgniter.
3. Validaciones de dominio en servicios internos.

Límites iniciales:

| Campo | Límite |
|---|---|
| Participantes | 2 a 20 |
| Mensajes | 1 a 500 |
| Texto por mensaje | 1 a 5.000 caracteres |
| Fragmento de evidencia | máximo 1.000 caracteres |
| Payload completo | configurable, recomendado 1 MB inicial |

---

## 20. Protección contra prompt injection

La conversación analizada debe tratarse como contenido no confiable. El prompt del sistema debe indicar que las instrucciones dentro de la conversación no deben modificar la tarea de análisis.

Medidas:

- Separar instrucciones de sistema y contenido analizado.
- Etiquetar la conversación como datos no confiables.
- Exigir JSON Schema de salida.
- Validar respuesta del LLM.
- Descartar campos no esperados.
- No ejecutar acciones externas en función directa del LLM.

---

## 21. Privacidad y protección de datos

### 21.1 Datos tratados

La API puede tratar:

- Identificadores de conversación.
- Identificadores de participantes.
- Mensajes.
- IPs.
- User agents hasheados.
- Evidencias mínimas.
- Resultado de análisis.
- Auditoría técnica.

### 21.2 Datos no enviados al LLM por defecto

No se enviarán al proveedor LLM:

- IPs.
- Emails.
- Nombres y apellidos.
- NIF/NIE.
- Tokens.
- Identificadores internos de base de datos.
- Información de facturación del cliente.

### 21.3 Evaluación de impacto

Antes de producción real con menores o decisiones de moderación significativas, se recomienda realizar una Evaluación de Impacto en Protección de Datos.

### 21.4 Retención

La retención debe ser configurable por cliente, con valores máximos razonables. Las sesiones LLM expiradas se eliminarán de forma agresiva. Las evidencias se conservarán solo durante el periodo justificado por la finalidad del tratamiento.

---

## 22. Despliegue sin contenedores

### 22.1 Topología recomendada

- Servidor Linux LTS.
- Nginx o Apache como servidor web.
- PHP-FPM 8.5.x.
- MySQL/MariaDB en el mismo servidor para sandbox o en servidor dedicado para producción.
- Certificados TLS gestionados con ACME/Certbot o proveedor corporativo.
- Servicio systemd para workers futuros.
- Cron o systemd timer para limpieza de sesiones.

### 22.2 Directorios recomendados

```text
/var/www/risk-api/current
/var/www/risk-api/releases
/var/www/risk-api/shared/.env
/var/log/risk-api
/var/backups/risk-api
```

### 22.3 Proceso de despliegue

1. Subir release a `/var/www/risk-api/releases/<timestamp>`.
2. Ejecutar `composer install --no-dev --optimize-autoloader`.
3. Validar `.env`.
4. Ejecutar migraciones.
5. Ejecutar pruebas de smoke.
6. Cambiar symlink `current`.
7. Recargar PHP-FPM.
8. Recargar Nginx/Apache si procede.
9. Verificar `/v1/health`.

---

## 23. Estructura de repositorio propuesta

```text
risk-api/
├── app/
│   ├── Commands/
│   ├── Config/
│   ├── Controllers/
│   │   └── Api/V1/
│   ├── Database/
│   │   ├── Migrations/
│   │   └── Seeds/
│   ├── DTO/
│   ├── Entities/
│   ├── Filters/
│   ├── Models/
│   ├── Services/
│   │   ├── Llm/
│   │   ├── Risk/
│   │   └── Security/
│   └── Validation/
├── openapi/
│   └── risk-api.v1.yaml
├── docs/
├── tests/
├── writable/
├── public/
├── composer.json
├── env
└── spark
```

---

## 24. PHPDoc y normas de código

Todo desarrollo deberá cumplir:

- `declare(strict_types=1);`
- Namespaces PSR-4.
- Variables, métodos y funciones en camelCase.
- Clases en PascalCase.
- Tipado de parámetros y retornos.
- PHPDoc completo en clases y métodos.
- Sin lógica de negocio en controladores.
- Sin acceso directo a `$_SERVER`, `$_POST` o `$_GET` fuera de capas controladas.
- Sin SQL concatenado.
- Sin secretos hardcodeados.

Plantilla PHPDoc mínima:

```php
/**
 * Nombre: createLlmSession
 *
 * Descripción de la funcionalidad:
 * Crea una sesión efímera para un cliente integrador usando un proveedor LLM permitido.
 *
 * Parámetros de entrada:
 * - string $clientPublicId Identificador público del cliente integrador.
 * - string $clientSecret Secreto privado recibido en la petición.
 * - string $llmProvider Proveedor LLM solicitado.
 * - string $llmToken Token API del proveedor LLM.
 *
 * Parámetros de salida:
 * - LlmSessionResult DTO con token de sesión, proveedor y expiración.
 *
 * Método de uso:
 * $sessionResult = $service->createLlmSession($clientPublicId, $clientSecret, $llmProvider, $llmToken);
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
```

---

## 25. Plan de pruebas

### 25.1 Unitarias

- Servicios de cifrado.
- Validación de cliente.
- Generación y hash de tokens.
- Expiración de sesión.
- Scoring de riesgo.
- Normalización de mensajes.

### 25.2 Integración

- Login LLM con OpenAI.
- Login LLM con Anthropic.
- Análisis completo.
- Revocación.
- Limpieza de sesiones.
- Persistencia MySQL/MariaDB.

### 25.3 Contractuales

- Validación de requests contra OpenAPI.
- Validación de responses contra OpenAPI.
- Ejemplos de documentación ejecutables.

### 25.4 Seguridad

- Rate limiting.
- Autorización por cliente.
- Intentos con sesión expirada.
- Payload excesivo.
- Prompt injection.
- Fugas de tokens en logs.
- Acceso cruzado entre clientes.

---

## 26. Plan de implantación

### Fase 0: Preparación

- Validar documento técnico.
- Congelar OpenAPI v1.
- Definir nombres de proyecto, dominio y entornos.
- Preparar servidor sin contenedores.

### Fase 1: Base técnica

- Instalar PHP 8.5.x.
- Instalar Composer.
- Crear proyecto CodeIgniter 4.7.x.
- Instalar CodeIgniter Shield 1.3.x.
- Configurar MySQL/MariaDB.
- Configurar HTTPS.

### Fase 2: Seguridad base

- Configurar `.env`.
- Configurar Shield.
- Crear migraciones.
- Implementar filtros de cliente API.
- Implementar cifrado de tokens LLM.
- Implementar limpieza programada.

### Fase 3: API MVP

- Implementar `/v1/auth/llm-session`.
- Implementar `/v1/conversations/analyze`.
- Implementar `/v1/health`.
- Implementar adaptadores OpenAI y Anthropic.
- Implementar motor inicial de scoring.

### Fase 4: Hardening

- Tests.
- Revisión OWASP.
- Revisión de logs.
- Revisión multi-tenant.
- Límites de consumo.
- Auditoría.

### Fase 5: Sandbox

- Crear cliente de prueba.
- Publicar documentación OpenAPI.
- Ejecutar pruebas con conversaciones sintéticas.
- Ajustar prompts y scoring.

### Fase 6: Piloto controlado

- Activar cliente real limitado.
- Monitorizar falsos positivos.
- Ajustar política de retención.
- Validar EIPD si aplica.

---

## 27. Checklist de salida a producción

### Seguridad

- [ ] HTTPS obligatorio.
- [ ] Debug desactivado.
- [ ] `.env` fuera del repositorio.
- [ ] Token LLM cifrado.
- [ ] Token de sesión hasheado.
- [ ] Limpieza de sesiones expirada activa.
- [ ] Logs sin secretos.
- [ ] Rate limiting activo.
- [ ] Multi-tenancy probado.
- [ ] Backoffice protegido con Shield.

### Operación

- [ ] Backups MySQL/MariaDB.
- [ ] Monitorización de errores.
- [ ] Rotación de logs.
- [ ] Healthcheck operativo.
- [ ] Procedimiento de rollback.
- [ ] Documentación de despliegue sin contenedores.

### OpenAPI

- [ ] Especificación validada.
- [ ] Ejemplos actualizados.
- [ ] Errores normalizados.
- [ ] SDK o colección de pruebas generada.

### Cumplimiento

- [ ] Registro de actividades de tratamiento.
- [ ] Contrato de encargado/responsable si aplica.
- [ ] Evaluación de impacto si hay menores o alto riesgo.
- [ ] Política de retención definida.
- [ ] Protocolo de revisión humana.

---

## 28. Riesgos principales

| Riesgo | Impacto | Mitigación |
|---|---|---|
| Fuga de token LLM | Uso fraudulento del proveedor del cliente. | Cifrado, TTL, hash de sesión y logs sin secretos. |
| Falso positivo | Bloqueo o alerta injustificada. | Revisión humana y scoring explicable. |
| Falso negativo | Riesgo real no detectado. | Reglas deterministas, mejora de prompts y validación continua. |
| Acceso cruzado multi-cliente | Incidente grave de privacidad. | Filtros obligatorios por `client_id` y pruebas BOLA. |
| Coste LLM excesivo | Pérdida económica para cliente. | Cuotas, límites, estimación de tokens y rate limiting. |
| Prompt injection | Manipulación del análisis. | Separación de instrucciones, validación de salida y reglas propias. |
| Retención excesiva | Riesgo RGPD. | Políticas de borrado y minimización. |

---

## 29. Roadmap

### MVP 0.1

- Sesión LLM efímera.
- OpenAI y Anthropic.
- Análisis básico.
- Evidencia mínima.
- MySQL/MariaDB.
- Despliegue sin contenedores.

### Versión 0.2

- Panel administrativo con Shield.
- Gestión de clientes.
- Políticas de riesgo por cliente.
- Reportes de auditoría.

### Versión 0.3

- Webhooks internos.
- Moderation dashboard.
- Exportación de evidencias.
- Módulo de revisión humana.

### Versión 1.0

- SaaS multi-cliente productivo.
- SLA y observabilidad completa.
- SDKs generados.
- Procedimiento jurídico-operativo documentado.

---

## 30. Conclusión

El proyecto queda definido como una API segura, multi-cliente, API-first y orientada a la protección digital mediante análisis de riesgo conversacional. La actualización técnica fija PHP 8.5.x, CodeIgniter 4.7.x, CodeIgniter Shield 1.3.x y MySQL/MariaDB como stack objetivo, eliminando cualquier dependencia de contenedores y de sistemas auxiliares como Redis o PostgreSQL en el diseño base.

La clave técnica y jurídica del producto será no convertir el LLM en autoridad sancionadora, sino utilizarlo como una pieza dentro de un motor de riesgo auditable, explicable, limitado y sujeto a revisión humana cuando el caso sea sensible o crítico.
