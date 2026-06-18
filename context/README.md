# API de Análisis de Riesgo Conversacional

Proyecto técnico actualizado para el desarrollo e implantación de una API multi-cliente de análisis de conversaciones mediante proveedores LLM externos.

## Stack fijado

- PHP 8.5.x
- CodeIgniter 4.7.x
- CodeIgniter Shield 1.3.x
- OpenAPI 3.1.2
- MySQL/MariaDB
- Nginx o Apache + PHP-FPM
- Sin Docker ni otros contenedores

## Contenido del paquete

```text
api-riesgo-conversacional/
├── README.md
├── proyecto_tecnico_api_analisis_riesgo_conversacional.md
├── openapi/
│   └── risk-api.v1.yaml
├── database/
│   └── mysql/
│       ├── schema.sql
│       └── cleanup_sessions.sql
├── docs/
│   ├── arquitectura_codeigniter4_shield.md
│   ├── implantacion_sin_contenedores.md
│   └── seguridad_owasp.md
├── config/
│   ├── composer.json.example
│   └── env.example
├── src-snippets/
│   ├── CleanupLlmSessionsCommand.php
│   └── LlmTokenCipherService.php
└── ops/
```

## Decisiones principales

El proyecto descarta contenedores y concentra el despliegue sobre un servidor Linux tradicional con PHP-FPM y MySQL/MariaDB. Las sesiones efímeras con tokens LLM se almacenan temporalmente cifradas en base de datos y se eliminan tras 5 minutos de inactividad.

## Siguiente paso recomendado

Validar el contrato OpenAPI, generar el esqueleto CodeIgniter 4, instalar Shield y convertir los SQL en migraciones nativas de CodeIgniter.
