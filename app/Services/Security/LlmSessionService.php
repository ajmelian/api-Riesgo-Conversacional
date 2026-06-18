<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\UnauthorizedException;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

/**
 * Nombre: LlmSessionService
 *
 * Descripción de la funcionalidad:
 * Servicio que gestiona sesiones LLM efímeras: crear, validar, extender
 * y revocar sesiones con TTL de 300 segundos por inactividad.
 *
 * Parámetros de entrada:
 * - LlmTokenCipherService $cipherService Servicio de cifrado de tokens.
 *
 * Parámetros de salida:
 * - Sesiones efímeras gestionadas en base de datos.
 *
 * Método de uso:
 * $sessionService = new LlmSessionService($cipherService);
 * $result = $sessionService->createSession($clientId, $llmProvider, $llmToken, $ip, $userAgent);
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class LlmSessionService
{
    private BaseConnection $db;

    public function __construct(
        private readonly LlmTokenCipherService $cipherService
    ) {
        $this->db = Database::connect();
    }

    /**
     * Nombre: createSession
     *
     * Descripción de la funcionalidad:
     * Crea una sesión efímera cifrando el token LLM y almacenando solo
     * el hash del token de sesión.
     *
     * Parámetros de entrada:
     * - int $clientId ID interno del cliente autenticado.
     * - string $llmProvider Proveedor LLM (openai, anthropic).
     * - string $llmToken Token API del proveedor LLM.
     * - string $ipAddress Dirección IP del cliente.
     * - string $userAgent User-Agent del cliente.
     *
     * Parámetros de salida:
     * - array{sessionToken: string, tokenType: string, expiresIn: int, llmProvider: string}
     *
     * Método de uso:
     * $result = $sessionService->createSession(1, 'openai', 'sk-...', '1.2.3.4', 'App/1.0');
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function createSession(
        int $clientId,
        string $llmProvider,
        string $llmToken,
        string $ipAddress,
        string $userAgent
    ): array {
        $sessionToken = 'riskapi_sess_' . bin2hex(random_bytes(32));
        $sessionTokenHash = hash('sha256', $sessionToken);
        $llmTokenFingerprint = hash('sha256', $llmToken);
        $userAgentHash = hash('sha256', $userAgent);

        $encrypted = $this->cipherService->encryptToken($llmToken);

        $now = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', time() + 300);

        $this->db->table('api_llm_sessions')->insert([
            'client_id'            => $clientId,
            'session_token_hash'   => $sessionTokenHash,
            'llm_provider'         => $llmProvider,
            'llm_token_ciphertext' => $encrypted['cipherText'],
            'llm_token_nonce'      => $encrypted['nonce'],
            'llm_token_fingerprint' => $llmTokenFingerprint,
            'ip_address'           => inet_pton($ipAddress),
            'user_agent_hash'      => $userAgentHash,
            'created_at'           => $now,
            'last_activity_at'     => $now,
            'expires_at'           => $expiresAt,
        ]);

        return [
            'sessionToken' => $sessionToken,
            'tokenType'    => 'Bearer',
            'expiresIn'    => 300,
            'llmProvider'  => $llmProvider,
        ];
    }

    /**
     * Nombre: validateSession
     *
     * Descripción de la funcionalidad:
     * Valida un token de sesión Bearer, verificando hash, expiración y
     * revocación. Si es válido, extiende la expiración.
     *
     * Parámetros de entrada:
     * - string $sessionToken Token de sesión Bearer.
     *
     * Parámetros de salida:
     * - array Datos de la sesión validada.
     *
     * Método de uso:
     * $sessionData = $sessionService->validateSession('riskapi_sess_...');
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function validateSession(string $sessionToken): array
    {
        $sessionTokenHash = hash('sha256', $sessionToken);

        $session = $this->db->table('api_llm_sessions')
            ->where('session_token_hash', $sessionTokenHash)
            ->get()
            ->getRowArray();

        if ($session === null) {
            throw new UnauthorizedException('Sesión no encontrada.');
        }

        if ($session['revoked_at'] !== null) {
            throw new UnauthorizedException('Sesión revocada.');
        }

        $expiresAt = new \DateTime($session['expires_at']);
        $now = new \DateTime();

        if ($expiresAt < $now) {
            throw new UnauthorizedException('Sesión expirada.');
        }

        // Extender expiración
        $newExpiresAt = date('Y-m-d H:i:s', time() + 300);
        $this->db->table('api_llm_sessions')
            ->where('id', $session['id'])
            ->update([
                'last_activity_at' => date('Y-m-d H:i:s'),
                'expires_at'       => $newExpiresAt,
            ]);

        return $session;
    }

    /**
     * Nombre: revokeSession
     *
     * Descripción de la funcionalidad:
     * Revoca una sesión marcando revoked_at con la fecha actual.
     *
     * Parámetros de entrada:
     * - string $sessionToken Token de sesión Bearer.
     *
     * Parámetros de salida:
     * - void
     *
     * Método de uso:
     * $sessionService->revokeSession('riskapi_sess_...');
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function revokeSession(string $sessionToken): void
    {
        $sessionTokenHash = hash('sha256', $sessionToken);

        $this->db->table('api_llm_sessions')
            ->where('session_token_hash', $sessionTokenHash)
            ->update(['revoked_at' => date('Y-m-d H:i:s')]);
    }
}
