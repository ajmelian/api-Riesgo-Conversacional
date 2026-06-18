<?php

declare(strict_types=1);

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Nombre: AuthTest
 *
 * Descripción de la funcionalidad:
 * Tests de integración para los endpoints de autenticación:
 * POST /v1/auth/llm-session y DELETE /v1/auth/llm-session.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AuthTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    private string $clientId = 'client_test_001';
    private string $clientSecret = 'test_secret_change_me';

    protected function setUp(): void
    {
        parent::setUp();

        $this->db->table('api_clients')->insert([
            'public_id'            => $this->clientId,
            'name'                 => 'Integration Test Client',
            'client_secret_hash'   => password_hash($this->clientSecret, PASSWORD_BCRYPT, ['cost' => 12]),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);
    }

    public function testCreateLlmSessionSuccess(): void
    {
        $result = $this->withHeaders([
            'X-Client-Id'     => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'X-LLM-Provider'  => 'openai',
            'X-LLM-Token'     => 'sk-test-token-12345',
        ])->post('v1/auth/llm-session');

        $result->assertStatus(201);

        $body = json_decode((string) $result->response()->getBody(), true);
        $this->assertArrayHasKey('sessionToken', $body);
        $this->assertStringStartsWith('riskapi_sess_', $body['sessionToken']);
        $this->assertSame('Bearer', $body['tokenType']);
        $this->assertSame(300, $body['expiresIn']);
        $this->assertSame('openai', $body['llmProvider']);
    }

    public function testCreateLlmSessionInvalidSecret(): void
    {
        $result = $this->withHeaders([
            'X-Client-Id'     => $this->clientId,
            'X-Client-Secret' => 'wrong-secret',
            'X-LLM-Provider'  => 'openai',
            'X-LLM-Token'     => 'sk-test-token-12345',
        ])->post('v1/auth/llm-session');

        $result->assertStatus(401);

        $body = json_decode((string) $result->response()->getBody(), true);
        $this->assertArrayHasKey('error', $body);
        $this->assertArrayHasKey('message', $body);
    }

    public function testCreateLlmSessionInvalidClientId(): void
    {
        $result = $this->withHeaders([
            'X-Client-Id'     => 'nonexistent_client',
            'X-Client-Secret' => $this->clientSecret,
            'X-LLM-Provider'  => 'openai',
            'X-LLM-Token'     => 'sk-test-token-12345',
        ])->post('v1/auth/llm-session');

        $result->assertStatus(401);

        $body = json_decode((string) $result->response()->getBody(), true);
        $this->assertArrayHasKey('error', $body);
        $this->assertArrayHasKey('message', $body);
    }

    public function testCreateLlmSessionMissingHeaders(): void
    {
        $result = $this->post('v1/auth/llm-session');

        $status = $result->response()->getStatusCode();
        $this->assertContains($status, [401, 400]);
    }

    public function testRevokeLlmSessionSuccess(): void
    {
        $createResult = $this->withHeaders([
            'X-Client-Id'     => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'X-LLM-Provider'  => 'openai',
            'X-LLM-Token'     => 'sk-test-token-12345',
        ])->post('v1/auth/llm-session');

        $body = json_decode((string) $createResult->response()->getBody(), true);
        $sessionToken = $body['sessionToken'];

        $result = $this->withHeaders([
            'Authorization' => 'Bearer ' . $sessionToken,
        ])->delete('v1/auth/llm-session');

        $result->assertStatus(204);
        $this->assertEmpty((string) $result->response()->getBody());
    }

    public function testRevokeLlmSessionWithoutToken(): void
    {
        $result = $this->delete('v1/auth/llm-session');

        $result->assertStatus(401);
    }
}
