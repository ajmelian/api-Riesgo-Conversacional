<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\Security\LlmSessionService;
use App\Services\Security\LlmTokenCipherService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Nombre: LlmSessionServiceTest
 *
 * Descripción de la funcionalidad:
 * Tests unitarios para el servicio de gestión de sesiones LLM efímeras.
 *
 * Parámetros de entrada:
 * - Ninguno.
 *
 * Parámetros de salida:
 * - void
 *
 * Método de uso:
 * php vendor/bin/phpunit --filter=LlmSessionServiceTest
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class LlmSessionServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    private LlmSessionService $service;
    private string $encryptionKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->encryptionKey = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES);
        $cipherService = new LlmTokenCipherService($this->encryptionKey);

        $this->service = new LlmSessionService($cipherService);

        // Insert a test client
        $this->db->table('api_clients')->insert([
            'public_id'            => 'test_client',
            'name'                 => 'Test Client',
            'client_secret_hash'   => password_hash('secret', PASSWORD_BCRYPT),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);
    }

    public function testCreateSessionReturnsToken(): void
    {
        $result = $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        $this->assertArrayHasKey('sessionToken', $result);
        $this->assertStringStartsWith('riskapi_sess_', $result['sessionToken']);
        $this->assertSame('Bearer', $result['tokenType']);
        $this->assertSame(300, $result['expiresIn']);
        $this->assertSame('openai', $result['llmProvider']);
    }

    public function testSessionTokenIsHashedNotStoredInPlaintext(): void
    {
        $result = $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        $stored = $this->db->table('api_llm_sessions')->get()->getRowArray();
        $this->assertNotNull($stored);
        $this->assertNotSame($result['sessionToken'], $stored['session_token_hash']);
        $this->assertSame(64, strlen($stored['session_token_hash']));
    }

    public function testSessionExpiresIn300Seconds(): void
    {
        $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        $stored = $this->db->table('api_llm_sessions')->get()->getRowArray();

        $created = new \DateTime($stored['created_at']);
        $expires = new \DateTime($stored['expires_at']);
        $diff = $expires->getTimestamp() - $created->getTimestamp();

        $this->assertEqualsWithDelta(300, $diff, 2);
    }

    public function testValidateSessionWithValidToken(): void
    {
        $result = $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        $validated = $this->service->validateSession($result['sessionToken']);

        $this->assertIsArray($validated);
        $this->assertSame(1, $validated['client_id']);
    }

    public function testValidateSessionExtendsExpiry(): void
    {
        $result = $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        sleep(1);

        $this->service->validateSession($result['sessionToken']);

        $stored = $this->db->table('api_llm_sessions')->get()->getRowArray();
        $expires = new \DateTime($stored['expires_at']);
        $now = new \DateTime();
        $diff = $expires->getTimestamp() - $now->getTimestamp();

        $this->assertGreaterThan(295, $diff);
    }

    public function testValidateSessionWithExpiredTokenThrows(): void
    {
        $result = $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        // Force expiration
        $this->db->table('api_llm_sessions')
            ->update(['expires_at' => date('Y-m-d H:i:s', time() - 10)], ['client_id' => 1]);

        $this->expectException(\App\Exceptions\UnauthorizedException::class);
        $this->service->validateSession($result['sessionToken']);
    }

    public function testRevokeSessionMarksRevokedAt(): void
    {
        $result = $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        $this->service->revokeSession($result['sessionToken']);

        $stored = $this->db->table('api_llm_sessions')->get()->getRowArray();
        $this->assertNotNull($stored['revoked_at']);
    }

    public function testCreateSessionEncryptsLlmToken(): void
    {
        $this->service->createSession(1, 'openai', 'sk-test-token', '127.0.0.1', 'PHPUnit');

        $stored = $this->db->table('api_llm_sessions')->get()->getRowArray();

        $this->assertNotEmpty($stored['llm_token_ciphertext']);
        $this->assertNotEmpty($stored['llm_token_nonce']);
        $this->assertNotSame('sk-test-token', $stored['llm_token_ciphertext']);
    }
}
