# Tarea 0.3 — Configurar phpunit.xml.dist

**Dependencias:** tarea 0.1

**Descripción:** Configurar PHPUnit para el proyecto: SQLite en memoria para tests unitarios, variables de entorno de test, suites separadas para contrato, unitarios e integración.

**Criterio de aceptación:**
- [ ] `phpunit.xml.dist` existe en la raíz
- [ ] Base de datos configurada: `DBDriver = SQLite3`, `database = ':memory:'`
- [ ] Variables de entorno de test en el bloque `<php>`: `CI_ENVIRONMENT=testing`, `database.default.hostname`, etc.
- [ ] Suite `contract` configurada apuntando a `tests/Contract/`
- [ ] Suite `unit` configurada apuntando a `tests/Unit/`
- [ ] Suite `integration` configurada apuntando a `tests/Integration/`
- [ ] `php vendor/bin/phpunit` se ejecuta sin errores (aunque no haya tests aún)

**Ficheros implicados:**
- `phpunit.xml.dist`
