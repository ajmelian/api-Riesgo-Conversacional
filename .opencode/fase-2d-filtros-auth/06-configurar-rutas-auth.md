# Tarea 2D.6 — Configurar rutas /v1/auth/llm-session

**Dependencias:** tarea 1.1 (grupo de rutas v1 existente)

**Descripción:** Añadir las rutas POST y DELETE para `/v1/auth/llm-session` en el grupo `v1`.

**Criterio de aceptación:**
- [ ] `$routes->post('auth/llm-session', 'Auth::createSession')` en el grupo v1
- [ ] `$routes->delete('auth/llm-session', 'Auth::revokeSession')` en el grupo v1
- [ ] Las rutas aplican filtros: `apiClientAuth` para POST, `bearerSession` para DELETE
- [ ] `php spark routes` muestra ambas rutas
- [ ] Los 7 tests de integración + 2 tests de contrato pasan a verde

**Ficheros implicados:**
- `app/Config/Routes.php`
