<?php

declare(strict_types=1);

namespace Tests\Contract;

use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Nyholm\Psr7\Factory\Psr17Factory;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Nombre: AnalysisContractTest
 *
 * Descripción de la funcionalidad:
 * Tests de contrato para POST /v1/conversations/analyze.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AnalysisContractTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    private mixed $responseValidator;
    private Psr17Factory $psr17Factory;
    private string $sessionToken;

    protected function setUp(): void
    {
        parent::setUp();

        $yamlPath = ROOTPATH . 'context/openapi/risk-api.v1.yaml';
        $this->responseValidator = (new ValidatorBuilder())
            ->fromYamlFile($yamlPath)
            ->getResponseValidator();
        $this->psr17Factory = new Psr17Factory();

        // Crear cliente y sesión
        $this->db->table('api_clients')->insert([
            'public_id'            => 'client_contract_3c',
            'name'                 => 'Contract Client',
            'client_secret_hash'   => password_hash('secret', PASSWORD_BCRYPT, ['cost' => 12]),
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

    public function testAnalyzeWithoutTokenReturns401(): void
    {
        $result = $this->post('v1/conversations/analyze');

        $this->assertSame(401, $result->response()->getStatusCode());
    }
}
