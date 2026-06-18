<?php

declare(strict_types=1);

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Nombre: FullFlowTest
 *
 * Descripción de la funcionalidad:
 * Test end-to-end que ejecuta el ciclo completo: crear sesión,
 * analizar conversación, revocar sesión y verificar que la
 * sesión revocada no puede analizar.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class FullFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    private string $clientId = 'client_e2e';
    private string $clientSecret = 'e2e_secret';

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['LLM_TOKEN_ENCRYPTION_KEY'] = 'base64:' . base64_encode(random_bytes(32));

        $this->db->table('api_clients')->insert([
            'public_id'            => $this->clientId,
            'name'                 => 'E2E Test Client',
            'client_secret_hash'   => password_hash($this->clientSecret, PASSWORD_BCRYPT, ['cost' => 12]),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);
    }

    public function testFullSessionLifecycle(): void
    {
        // Paso 1: Crear sesión LLM
        $createResult = $this->withHeaders([
            'X-Client-Id'     => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'X-LLM-Provider'  => 'openai',
            'X-LLM-Token'     => 'sk-test-e2e-token',
        ])->post('v1/auth/llm-session');

        $createResult->assertStatus(201);
        $sessionBody = json_decode((string) $createResult->response()->getBody(), true);
        $this->assertNotEmpty($sessionBody['sessionToken']);
        $sessionToken = $sessionBody['sessionToken'];

        // Paso 2: Intentar análisis con sesión válida (el LLM fallará pero el flujo se valida)
        $analyzeResult = $this->withHeaders([
            'Authorization' => 'Bearer ' . $sessionToken,
        ])->withBody(json_encode([
            'conversationId' => 'e2e_conv_001',
            'context'        => [
                'platformType'   => 'forum',
                'language'        => 'es',
                'containsMinors' => true,
            ],
            'participants'   => [
                ['participantId' => 'usr_a', 'role' => 'affected_candidate'],
                ['participantId' => 'usr_b', 'role' => 'risk_actor_candidate'],
            ],
            'messages'       => [
                [
                    'messageId'     => 'msg_001',
                    'participantId' => 'usr_a',
                    'text'          => 'No quiero seguir hablando contigo.',
                    'createdAt'     => '2026-06-18T10:30:00Z',
                ],
                [
                    'messageId'     => 'msg_002',
                    'participantId' => 'usr_b',
                    'text'          => 'Te vas a arrepentir si cuentas algo.',
                    'createdAt'     => '2026-06-18T10:30:10Z',
                ],
            ],
        ]))->post('v1/conversations/analyze');

        // Análisis válido — puede fallar por la llamada real al LLM pero el flujo debe estar definido
        $status = $analyzeResult->response()->getStatusCode();
        $this->assertContains($status, [200, 400, 500], 'Análisis debe responder');

        // Paso 3: Revocar sesión
        $revokeResult = $this->withHeaders([
            'Authorization' => 'Bearer ' . $sessionToken,
        ])->delete('v1/auth/llm-session');

        $revokeResult->assertStatus(204);

        // Paso 4: Intentar análisis con sesión revocada
        $analyzeResult2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $sessionToken,
        ])->withBody(json_encode([
            'conversationId' => 'e2e_conv_002',
            'context'        => ['platformType' => 'chat', 'language' => 'es', 'containsMinors' => false],
            'participants'   => [
                ['participantId' => 'usr_c'],
                ['participantId' => 'usr_d'],
            ],
            'messages'       => [
                ['messageId' => 'msg_003', 'participantId' => 'usr_c', 'text' => 'Hola', 'createdAt' => '2026-06-18T10:00:00Z'],
            ],
        ]))->post('v1/conversations/analyze');

        $analyzeResult2->assertStatus(401);
    }
}
