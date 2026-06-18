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
 * Nombre: AuthContractTest
 *
 * Descripción de la funcionalidad:
 * Tests de contrato para POST y DELETE /v1/auth/llm-session
 * validando respuestas contra el OpenAPI YAML.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AuthContractTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    private mixed $responseValidator;
    private Psr17Factory $psr17Factory;
    private string $clientId = 'client_test_auth';
    private string $clientSecret = 'test_secret_change_me';

    protected function setUp(): void
    {
        parent::setUp();

        $yamlPath = ROOTPATH . 'context/openapi/risk-api.v1.yaml';
        $this->responseValidator = (new ValidatorBuilder())
            ->fromYamlFile($yamlPath)
            ->getResponseValidator();
        $this->psr17Factory = new Psr17Factory();

        $this->db->table('api_clients')->insert([
            'public_id'            => $this->clientId,
            'name'                 => 'Auth Contract Client',
            'client_secret_hash'   => password_hash($this->clientSecret, PASSWORD_BCRYPT, ['cost' => 12]),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);
    }

    public function testCreateLlmSessionContract(): void
    {
        $result = $this->withHeaders([
            'X-Client-Id'     => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'X-LLM-Provider'  => 'openai',
            'X-LLM-Token'     => 'sk-test-token-contract-12345',
        ])->post('v1/auth/llm-session');

        $this->assertSame(201, $result->response()->getStatusCode());

        $psrResponse = $this->psr17Factory->createResponse(201);
        $psrResponse = $psrResponse->withBody(
            $this->psr17Factory->createStream((string) $result->response()->getBody())
        );
        $psrResponse = $psrResponse->withHeader(
            'Content-Type',
            $result->response()->getHeaderLine('Content-Type') ?: 'application/json'
        );

        $this->responseValidator->validate(
            new OperationAddress('/v1/auth/llm-session', 'post'),
            $psrResponse
        );

        $body = json_decode((string) $result->response()->getBody(), true);
        $this->assertSame('Bearer', $body['tokenType']);
        $this->assertSame(300, $body['expiresIn']);
    }
}
