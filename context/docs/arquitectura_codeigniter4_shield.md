# Arquitectura CodeIgniter 4 y Shield

**Proyecto:** API de Análisis de Riesgo Conversacional  
**Autor:** Aythami Melián Perdomo  
**Fecha:** 18/06/2026

## 1. Uso de CodeIgniter 4

CodeIgniter 4 actuará como framework principal del backend. La arquitectura interna debe separar controladores, servicios, filtros, modelos, DTOs y adaptadores externos.

## 2. Uso de Shield

CodeIgniter Shield se utilizará para usuarios internos, administración, grupos, permisos y backoffice. Para clientes API machine-to-machine se implementará una capa propia basada en `api_clients`, vinculable a usuarios o cuentas de servicio gestionadas por Shield.

## 3. Roles internos sugeridos

| Rol | Descripción |
|---|---|
| superAdmin | Control total del sistema. |
| securityAdmin | Gestión de seguridad, auditoría y clientes API. |
| clientManager | Alta y mantenimiento de clientes integradores. |
| auditor | Acceso de lectura a eventos y análisis. |
| moderator | Revisión humana de alertas cuando exista panel. |

## 4. Filtros sugeridos

- `ApiClientAuthFilter`: valida `X-Client-Id` y `X-Client-Secret`.
- `BearerSessionFilter`: valida token Bearer de sesión LLM.
- `TenantIsolationFilter`: garantiza filtrado por `client_id`.
- `RateLimitFilter`: limita consumo por cliente, IP y endpoint.
- `TraceIdFilter`: genera o propaga identificador de trazabilidad.

## 5. Servicios sugeridos

- `ApiClientAuthenticatorService`
- `LlmSessionService`
- `LlmTokenCipherService`
- `ConversationAnalysisService`
- `RiskScoringService`
- `AuditLogService`
- `OpenAiProviderService`
- `AnthropicProviderService`
