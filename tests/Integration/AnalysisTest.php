<?php

declare(strict_types=1);

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Nombre: AnalysisTest
 *
 * Descripción de la funcionalidad:
 * Tests de integración para el endpoint POST /v1/conversations/analyze.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AnalysisTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    private string $clientId = 'client_analysis_integration';
    private string $clientSecret = 'test_secret';
    private string $sessionToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db->table('api_clients')->insert([
            'public_id'            => $this->clientId,
            'name'                 => 'Analysis Integration Client',
            'client_secret_hash'   => password_hash($this->clientSecret, PASSWORD_BCRYPT, ['cost' => 12]),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        $this->sessionToken = 'riskapi_sess_' . bin2hex(random_bytes(32));
        $this->db->table('api_llm_sessions')->insert([
            'client_id'            => 1,
            'session_token_hash'   => hash('sha256', $this->sessionToken),
            'llm_provider'         => 'openai',
            'llm_token_ciphertext' => 'dummy',
            'llm_token_nonce'      => 'dummy',
            'llm_token_fingerprint' => hash('sha256', 'dummy'),
            'ip_address'           => inet_pton('127.0.0.1'),
            'user_agent_hash'      => hash('sha256', 'PHPUnit'),
            'created_at'           => date('Y-m-d H:i:s'),
            'last_activity_at'     => date('Y-m-d H:i:s'),
            'expires_at'           => date('Y-m-d H:i:s', time() + 300),
        ]);
    }

    public function testAnalyzeWithoutBearerToken(): void
    {
        $result = $this->post('v1/conversations/analyze');

        $result->assertStatus(401);
    }

    public function testAnalyzeWithInvalidPayload(): void
    {
        $result = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->sessionToken,
        ])->withBody(json_encode([
            'conversationId' => 'test',
            'context'        => ['platformType' => 'forum', 'language' => 'es', 'containsMinors' => false],
            'participants'   => [['participantId' => 'single']],
            'messages'       => [],
        ]))->post('v1/conversations/analyze');

        $this->assertContains($result->response()->getStatusCode(), [422, 400]);
    }
}
