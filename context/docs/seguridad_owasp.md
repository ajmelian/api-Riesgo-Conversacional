# Seguridad OWASP para la API

**Proyecto:** API de Análisis de Riesgo Conversacional  
**Autor:** Aythami Melián Perdomo  
**Fecha:** 18/06/2026

## 1. Controles prioritarios

El proyecto debe priorizar OWASP API Security Top 10 2023 y OWASP ASVS.

## 2. Controles del MVP

| Control | Implementación esperada |
|---|---|
| Autenticación rota | Client credentials, Bearer opaco, hash de tokens, TTL. |
| Autorización rota a nivel de objeto | Filtrado obligatorio por `client_id`. |
| Consumo no controlado | Rate limiting, cuotas, límites de payload y mensajes. |
| Exposición de datos | DTOs de salida, minimización y redacción de logs. |
| Gestión de secretos | Variables de entorno, libsodium, no logs con secretos. |
| Prompt injection | Separación de instrucciones y datos, validación JSON. |
| APIs de terceros | Timeouts, allowlist de dominios y validación de respuestas LLM. |

## 3. Cabeceras prohibidas en logs

- Authorization
- X-Client-Secret
- X-LLM-Token
- Cookie
- Set-Cookie

## 4. Evidencias

Las evidencias deben ser fragmentos mínimos, con límite de longitud y sin duplicar conversaciones completas salvo necesidad justificada.
