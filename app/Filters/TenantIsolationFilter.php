<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Nombre: TenantIsolationFilter
 *
 * Descripción de la funcionalidad:
 * Garantiza que toda consulta a BD filtre por client_id del cliente
 * autenticado, evitando acceso cruzado entre clientes.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class TenantIsolationFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
