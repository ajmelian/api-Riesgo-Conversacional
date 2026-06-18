<?php

declare(strict_types=1);

namespace App\Filters;

use App\Services\Security\ApiClientAuthenticatorService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Nombre: ApiClientAuthFilter
 *
 * Descripción de la funcionalidad:
 * Filtro que valida las cabeceras X-Client-Id y X-Client-Secret en cada
 * petición, autentica al cliente integrador e inyecta client_id en el request.
 *
 * Parámetros de entrada:
 * - RequestInterface $request Petición entrante.
 *
 * Parámetros de salida:
 * - RequestInterface|ResponseInterface Petición con client_id inyectado o respuesta 401.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class ApiClientAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $clientId = $request->getHeaderLine('X-Client-Id');
        $clientSecret = $request->getHeaderLine('X-Client-Secret');

        if ($clientId === '' || $clientSecret === '') {
            return $this->unauthorizedResponse('Cabeceras X-Client-Id y X-Client-Secret requeridas.');
        }

        try {
            $authenticator = new ApiClientAuthenticatorService();
            $client = $authenticator->authenticate($clientId, $clientSecret);

            $request->client_id = (int) $client['id'];

            return $request;
        } catch (\App\Exceptions\UnauthorizedException $e) {
            return $this->unauthorizedResponse($e->getMessage());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }

    private function unauthorizedResponse(string $message): ResponseInterface
    {
        $response = service('response');
        $response->setStatusCode(401);
        $response->setJSON([
            'error'   => 'unauthorized',
            'message' => $message,
            'traceId' => '',
        ]);

        return $response;
    }
}
