<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Nombre: TraceIdFilter
 *
 * Descripción de la funcionalidad:
 * Genera o propaga el identificador de trazabilidad X-Trace-Id
 * en todas las peticiones y respuestas.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class TraceIdFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $traceId = $request->getHeaderLine('X-Trace-Id');

        if ($traceId === '') {
            $traceId = bin2hex(random_bytes(16));
        }

        $request->trace_id = $traceId;

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('X-Trace-Id', $request->trace_id ?? '');

        return $response;
    }
}
