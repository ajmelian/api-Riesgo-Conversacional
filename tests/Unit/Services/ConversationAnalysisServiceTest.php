<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\ValidationException;
use App\Services\Risk\ConversationAnalysisService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Nombre: ConversationAnalysisServiceTest
 *
 * Descripción de la funcionalidad:
 * Tests unitarios para el servicio orquestador del análisis de conversaciones.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class ConversationAnalysisServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    private array $validPayload;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['LLM_TOKEN_ENCRYPTION_KEY'] = 'base64:' . base64_encode(random_bytes(32));

        $this->db->table('api_clients')->insert([
            'public_id'            => 'test_client_3c',
            'name'                 => '3C Test Client',
            'client_secret_hash'   => password_hash('secret', PASSWORD_BCRYPT),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        $this->validPayload = [
            'conversationId' => 'conv_test_123',
            'context'        => [
                'platformType'   => 'forum',
                'language'        => 'es',
                'containsMinors' => false,
            ],
            'participants'   => [
                ['participantId' => 'user_a', 'role' => 'affected_candidate'],
                ['participantId' => 'user_b', 'role' => 'risk_actor_candidate'],
            ],
            'messages'       => [
                [
                    'messageId'     => 'msg_001',
                    'participantId' => 'user_a',
                    'text'          => 'Hola, ¿cómo estás?',
                    'createdAt'     => '2026-06-18T10:00:00Z',
                ],
            ],
        ];
    }

    public function testPayloadExceedsMessageLimit(): void
    {
        $payload = $this->validPayload;
        $payload['messages'] = array_fill(0, 501, [
            'messageId'     => 'msg',
            'participantId' => 'user_a',
            'text'          => 'test',
            'createdAt'     => '2026-06-18T10:00:00Z',
        ]);

        $this->expectException(ValidationException::class);
        $service = new ConversationAnalysisService();
        $service->analyze($payload, 1, 'openai', 1);
    }

    public function testMessageExceedsLengthLimit(): void
    {
        $payload = $this->validPayload;
        $payload['messages'][0]['text'] = str_repeat('x', 5001);

        $this->expectException(ValidationException::class);
        $service = new ConversationAnalysisService();
        $service->analyze($payload, 1, 'openai', 1);
    }

    public function testParticipantCountOutOfRange(): void
    {
        $payload = $this->validPayload;
        $payload['participants'] = [['participantId' => 'single']];

        $this->expectException(ValidationException::class);
        $service = new ConversationAnalysisService();
        $service->analyze($payload, 1, 'openai', 1);
    }
}
