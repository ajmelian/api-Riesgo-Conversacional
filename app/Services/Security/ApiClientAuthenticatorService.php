<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\UnauthorizedException;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

/**
 * Nombre: ApiClientAuthenticatorService
 *
 * Descripción de la funcionalidad:
 * Servicio que valida las credenciales de un cliente integrador (X-Client-Id
 * y X-Client-Secret) contra la tabla api_clients.
 *
 * Parámetros de entrada:
 * - string $clientPublicId Identificador público del cliente.
 * - string $clientSecret Secreto privado del cliente.
 *
 * Parámetros de salida:
 * - array Datos del cliente autenticado.
 *
 * Método de uso:
 * $client = $service->authenticate($clientPublicId, $clientSecret);
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class ApiClientAuthenticatorService
{
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Nombre: authenticate
     *
     * Descripción de la funcionalidad:
     * Busca al cliente por public_id, verifica el secreto con password_verify
     * y comprueba que el estado es active.
     *
     * Parámetros de entrada:
     * - string $clientPublicId Identificador público del cliente.
     * - string $clientSecret Secreto privado del cliente.
     *
     * Parámetros de salida:
     * - array Datos del cliente.
     *
     * Método de uso:
     * $client = $service->authenticate('client_xxx', 'secret_xxx');
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function authenticate(string $clientPublicId, string $clientSecret): array
    {
        $client = $this->db->table('api_clients')
            ->where('public_id', $clientPublicId)
            ->get()
            ->getRowArray();

        if ($client === null) {
            throw new UnauthorizedException('Cliente no encontrado.');
        }

        if ($client['status'] !== 'active') {
            throw new UnauthorizedException('Cliente no activo.');
        }

        if (! password_verify($clientSecret, $client['client_secret_hash'])) {
            throw new UnauthorizedException('Secreto de cliente inválido.');
        }

        return $client;
    }
}
