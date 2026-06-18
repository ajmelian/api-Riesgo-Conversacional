<?php

declare(strict_types=1);

namespace App\Services\Security;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

/**
 * Nombre: AuditLogService
 *
 * Descripción de la funcionalidad:
 * Servicio de auditoría técnica. Registra eventos sin secretos,
 * filtrando claves sensibles de los metadatos.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AuditLogService
{
    private BaseConnection $db;
    private const SENSITIVE_KEYS = ['token', 'secret', 'key', 'password', 'authorization', 'bearer'];

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Nombre: log
     *
     * Descripción de la funcionalidad:
     * Registra un evento de auditoría limpiando los metadatos de claves sensibles.
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function log(
        string $eventType,
        ?int $clientId,
        string $actorType,
        ?string $actorId,
        array $metadata,
        string $traceId,
        ?string $ipAddress,
        ?string $userAgent
    ): void {
        $safeMetadata = $this->redactSensitiveKeys($metadata);
        $userAgentHash = $userAgent !== null ? hash('sha256', $userAgent) : null;

        $this->db->table('api_audit_logs')->insert([
            'client_id'       => $clientId,
            'actor_type'      => $actorType,
            'actor_id'        => $actorId,
            'event_type'      => $eventType,
            'trace_id'        => $traceId,
            'ip_address'      => $ipAddress !== null ? inet_pton($ipAddress) : null,
            'user_agent_hash' => $userAgentHash,
            'metadata_json'   => json_encode($safeMetadata),
            'created_at'      => date('Y-m-d H:i:s'),
        ]);
    }

    private function redactSensitiveKeys(array $data): array
    {
        $safe = [];
        foreach ($data as $key => $value) {
            $lowerKey = mb_strtolower((string) $key);
            $isSensitive = false;
            foreach (self::SENSITIVE_KEYS as $word) {
                if (str_contains($lowerKey, $word)) {
                    $isSensitive = true;
                    break;
                }
            }
            if ($isSensitive) {
                $safe[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $safe[$key] = $this->redactSensitiveKeys($value);
            } else {
                $safe[$key] = $value;
            }
        }

        return $safe;
    }
}
