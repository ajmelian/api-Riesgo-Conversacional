# Tarea 4.6 — Implementar AuditLogService

**Dependencias:** tarea 4.5 (tests RED)

**Descripción:** Implementar el servicio de auditoría que registra eventos sin secretos.

**Criterio de aceptación:**
- [ ] Clase `AuditLogService` en `app/Services/Security/AuditLogService.php`
- [ ] `declare(strict_types=1)`
- [ ] `log(string $eventType, ?int $clientId, string $actorType, ?string $actorId, array $metadata, string $traceId, ?string $ipAddress, ?string $userAgentHash): void`
- [ ] Filtra metadata: elimina claves que contengan `token`, `secret`, `key`, `password`, `authorization`
- [ ] Genera `trace_id` si no se proporciona (UUID v4 o similar)
- [ ] Hashea user agent antes de persistir
- [ ] Almacena IP como `inet_pton()` (VARBINARY)
- [ ] Usa Query Builder de CI4
- [ ] PHPDoc en español
- [ ] Los 3 tests de `AuditLogServiceTest` pasan a verde

**Ficheros implicados:**
- `app/Services/Security/AuditLogService.php`
- `app/Models/AuditLogModel.php`
