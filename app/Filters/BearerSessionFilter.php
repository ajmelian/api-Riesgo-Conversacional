<?php

declare(strict_types=1);

namespace App\Filters;

use App\Exceptions\UnauthorizedException;
use App\Services\Security\LlmSessionService;
use App\Services\Security\LlmTokenCipherService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Nombre: BearerSessionFilter
 *
 * Descripción de la funcionalidad:
 * Filtro que valida el token Bearer de sesión LLM en la cabecera
 * Authorization e inyecta session_id y client_id en el request.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class BearerSessionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if ($authHeader === '' || ! str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorizedResponse('Token de sesión requerido (Authorization: Bearer riskapi_sess_...).');
        }

        $sessionToken = substr($authHeader, 7);

        try {
            $encryptionKey = base64_decode(
                ltrim((string) env('LLM_TOKEN_ENCRYPTION_KEY'), 'base64:')
            );
            $cipherService = new LlmTokenCipherService($encryptionKey);
            $sessionService = new LlmSessionService($cipherService);

            $session = $sessionService->validateSession($sessionToken);

            $request->session_id = (int) $session['id'];
            $request->client_id = (int) $session['client_id'];

            return $request;
        } catch (UnauthorizedException $e) {
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
