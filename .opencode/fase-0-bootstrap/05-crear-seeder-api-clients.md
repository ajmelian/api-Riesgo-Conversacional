# Tarea 0.5 — Crear seeder api_clients

**Dependencias:** tarea 0.4 (migraciones aplicadas)

**Descripción:** Crear un seeder que inserte al menos un cliente de prueba activo en `api_clients`.

**Criterio de aceptación:**
- [ ] Seeder `ApiClientSeeder` inserta un cliente con:
  - `public_id`: `client_test_001`
  - `name`: `Cliente de Prueba Sandbox`
  - `client_secret_hash`: bcrypt del secreto `test_secret_change_me` (12 rounds mínimo)
  - `status`: `active`
  - `allowed_llm_providers`: `["openai", "anthropic"]` (JSON)
  - `rate_limit_per_minute`: 60
  - `monthly_quota`: 10000
- [ ] `php spark db:seed ApiClientSeeder` ejecuta sin errores
- [ ] La fila existe en `api_clients` tras ejecutar `php spark migrate:refresh --all && php spark db:seed ApiClientSeeder`

**Ficheros implicados:**
- `app/Database/Seeds/ApiClientSeeder.php`
