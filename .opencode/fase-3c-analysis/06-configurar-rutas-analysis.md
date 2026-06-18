# Tarea 3C.6 — Configurar ruta /v1/conversations/analyze

**Dependencias:** tarea 1.1 (grupo v1)

**Descripción:** Añadir la ruta POST para análisis en el grupo `v1`.

**Criterio de aceptación:**
- [ ] `$routes->post('conversations/analyze', 'Analysis::analyze')` en el grupo v1
- [ ] Aplica filtro `bearerSession`
- [ ] `php spark routes` muestra la ruta
- [ ] Los 7 tests unitarios + 4 de integración + 3 de contrato pasan a verde

**Ficheros implicados:**
- `app/Config/Routes.php`
