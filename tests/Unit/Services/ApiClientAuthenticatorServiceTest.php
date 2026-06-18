<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\UnauthorizedException;
use App\Services\Security\ApiClientAuthenticatorService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Nombre: ApiClientAuthenticatorServiceTest
 *
 * Descripción de la funcionalidad:
 * Tests unitarios para el servicio de autenticación de clientes integradores.
 * Valida X-Client-Id y X-Client-Secret contra la tabla api_clients.
 *
 * Parámetros de entrada:
 * - Ninguno.
 *
 * Parámetros de salida:
 * - void
 *
 * Método de uso:
 * php vendor/bin/phpunit --filter=ApiClientAuthenticatorServiceTest
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class ApiClientAuthenticatorServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db->table('api_clients')->insert([
            'public_id'            => 'client_active',
            'name'                 => 'Active Client',
            'client_secret_hash'   => password_hash('correct_secret', PASSWORD_BCRYPT),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('api_clients')->insert([
            'public_id'            => 'client_disabled',
            'name'                 => 'Disabled Client',
            'client_secret_hash'   => password_hash('correct_secret', PASSWORD_BCRYPT),
            'status'               => 'disabled',
            'allowed_llm_providers' => json_encode(['openai']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('api_clients')->insert([
            'public_id'            => 'client_suspended',
            'name'                 => 'Suspended Client',
            'client_secret_hash'   => password_hash('correct_secret', PASSWORD_BCRYPT),
            'status'               => 'suspended',
            'allowed_llm_providers' => json_encode(['openai']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);
    }

    public function testValidCredentialsReturnClient(): void
    {
        $service = new ApiClientAuthenticatorService();
        $result = $service->authenticate('client_active', 'correct_secret');

        $this->assertIsArray($result);
        $this->assertSame('client_active', $result['public_id']);
        $this->assertSame('active', $result['status']);
    }

    public function testInvalidSecretThrows(): void
    {
        $service = new ApiClientAuthenticatorService();

        $this->expectException(UnauthorizedException::class);
        $service->authenticate('client_active', 'wrong_secret');
    }

    public function testNonexistentClientThrows(): void
    {
        $service = new ApiClientAuthenticatorService();

        $this->expectException(UnauthorizedException::class);
        $service->authenticate('nonexistent_client', 'some_secret');
    }

    public function testDisabledClientThrows(): void
    {
        $service = new ApiClientAuthenticatorService();

        $this->expectException(UnauthorizedException::class);
        $service->authenticate('client_disabled', 'correct_secret');
    }

    public function testSuspendedClientThrows(): void
    {
        $service = new ApiClientAuthenticatorService();

        $this->expectException(UnauthorizedException::class);
        $service->authenticate('client_suspended', 'correct_secret');
    }
}
