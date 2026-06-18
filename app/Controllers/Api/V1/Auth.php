<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Exceptions\UnauthorizedException;
use App\Services\Security\LlmSessionService;
use App\Services\Security\LlmTokenCipherService;
use CodeIgniter\RESTful\ResourceController;

/**
 * Nombre: Auth
 *
 * Descripción de la funcionalidad:
 * Controlador que expone los endpoints de gestión de sesiones LLM:
 * POST /v1/auth/llm-session y DELETE /v1/auth/llm-session.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class Auth extends ResourceController
{
    /**
     * Nombre: createSession
     *
     * Descripción de la funcionalidad:
     * Crea una sesión efímera con el proveedor LLM indicado.
     * Requiere ApiClientAuthFilter.
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function createSession(): \CodeIgniter\HTTP\ResponseInterface
    {
        try {
            $clientId = (int) $this->request->client_id;
            $llmProvider = $this->request->getHeaderLine('X-LLM-Provider');
            $llmToken = $this->request->getHeaderLine('X-LLM-Token');

            if ($llmProvider === '' || $llmToken === '') {
                return $this->failValidationErrors([
                    ['field' => 'X-LLM-Provider', 'message' => 'Cabecera X-LLM-Provider requerida.'],
                    ['field' => 'X-LLM-Token', 'message' => 'Cabecera X-LLM-Token requerida.'],
                ]);
            }

            $encryptionKey = base64_decode(
                ltrim((string) env('LLM_TOKEN_ENCRYPTION_KEY'), 'base64:')
            );
            $cipherService = new LlmTokenCipherService($encryptionKey);
            $sessionService = new LlmSessionService($cipherService);

            $result = $sessionService->createSession(
                $clientId,
                $llmProvider,
                $llmToken,
                $this->request->getIPAddress(),
                (string) ($this->request->getUserAgent() ?? 'unknown')
            );

            return $this->respondCreated($result);
        } catch (UnauthorizedException $e) {
            return $this->failUnauthorized($e->getMessage());
        }
    }

    /**
     * Nombre: revokeSession
     *
     * Descripción de la funcionalidad:
     * Revoca la sesión LLM activa identificada por el token Bearer.
     * Requiere BearerSessionFilter.
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function revokeSession(): \CodeIgniter\HTTP\ResponseInterface
    {
        try {
            $authHeader = $this->request->getHeaderLine('Authorization');
            $sessionToken = substr($authHeader, 7);

            $encryptionKey = base64_decode(
                ltrim((string) env('LLM_TOKEN_ENCRYPTION_KEY'), 'base64:')
            );
            $cipherService = new LlmTokenCipherService($encryptionKey);
            $sessionService = new LlmSessionService($cipherService);

            $sessionService->revokeSession($sessionToken);

            return $this->respond(null, 204);
        } catch (UnauthorizedException $e) {
            return $this->failUnauthorized($e->getMessage());
        }
    }
}
