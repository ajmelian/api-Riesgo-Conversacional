# Tarea 4.1 — Test de integración: TenantIsolationTest

**Dependencias:** fase-3C (análisis completo funcional)

**Descripción:** Escribir 2 tests que verifiquen que un cliente no puede acceder a recursos de otro.

**Criterio de aceptación:**
- [ ] Test `TenantIsolationTest` en `tests/Integration/TenantIsolationTest.php`
- [ ] `testCannotAccessOtherClientSession`: cliente A crea sesión; cliente B intenta usar ese token → 401
- [ ] `testCannotAccessOtherClientAnalysis`: cliente A crea análisis; cliente B intenta consultarlo → 404/401
- [ ] El test falla inicialmente (RED — TenantIsolationFilter no implementado)

**Ficheros implicados:**
- `tests/Integration/TenantIsolationTest.php`
