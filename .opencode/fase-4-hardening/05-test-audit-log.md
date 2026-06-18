# Tarea 4.5 — Tests unitarios: AuditLogServiceTest

**Dependencias:** fase-0 (tabla api_audit_logs migrada)

**Descripción:** Escribir 3 tests unitarios para el servicio de auditoría, verificando que nunca registra secretos.

**Criterio de aceptación:**
- [ ] Test `AuditLogServiceTest` en `tests/Unit/Services/AuditLogServiceTest.php`
- [ ] `testLogsEventWithoutSecrets`: registra evento en `api_audit_logs` — verifica que metadata no contiene tokens ni secretos
- [ ] `testLogsTraceId`: cada registro incluye `trace_id` correlacionable
- [ ] `testDoesNotLogAuthorizationHeader`: verifica que la cabecera Authorization nunca aparece en logs
- [ ] El test falla inicialmente (RED)

**Ficheros implicados:**
- `tests/Unit/Services/AuditLogServiceTest.php`
