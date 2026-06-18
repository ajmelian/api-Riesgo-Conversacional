# Tarea 1.1 — Configurar ruta GET /v1/health

**Dependencias:** fase-0 (todas las tareas)

**Descripción:** Registrar la ruta `GET /v1/health` en el grupo `v1` de rutas de CodeIgniter.

**Criterio de aceptación:**
- [ ] `app/Config/Routes.php` tiene un grupo `v1` con namespace `App\Controllers\Api\V1`
- [ ] Dentro del grupo: `$routes->get('health', 'Health::index')`
- [ ] `php spark routes` muestra la ruta `/v1/health` con método GET
- [ ] `GET /v1/health` devuelve 404 hasta que se implemente el controlador (esperado en este paso)

**Ficheros implicados:**
- `app/Config/Routes.php`
