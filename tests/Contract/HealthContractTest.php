<?php

declare(strict_types=1);

namespace Tests\Contract;

use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Nyholm\Psr7\Factory\Psr17Factory;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Nombre: HealthContractTest
 *
 * Descripción de la funcionalidad:
 * Test de contrato que valida la respuesta de GET /v1/health contra el schema
 * HealthResponse definido en el OpenAPI YAML.
 *
 * Parámetros de entrada:
 * - Ninguno.
 *
 * Parámetros de salida:
 * - void
 *
 * Método de uso:
 * php vendor/bin/phpunit --filter=HealthContractTest
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class HealthContractTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    private mixed $responseValidator;
    private Psr17Factory $psr17Factory;

    protected function setUp(): void
    {
        parent::setUp();

        $yamlPath = ROOTPATH . 'context/openapi/risk-api.v1.yaml';
        $this->responseValidator = (new ValidatorBuilder())
            ->fromYamlFile($yamlPath)
            ->getResponseValidator();
        $this->psr17Factory = new Psr17Factory();
    }

    public function testHealthEndpointCompliesWithOpenApiContract(): void
    {
        $result = $this->get('v1/health');

        $result->assertStatus(200);

        $psrResponse = $this->psr17Factory->createResponse(200);
        $psrResponse = $psrResponse->withBody(
            $this->psr17Factory->createStream((string) $result->response()->getBody())
        );
        $psrResponse = $psrResponse->withHeader(
            'Content-Type',
            $result->response()->getHeaderLine('Content-Type') ?: 'application/json'
        );

        $this->responseValidator->validate(
            new OperationAddress('/v1/health', 'get'),
            $psrResponse
        );

        $body = json_decode((string) $result->response()->getBody(), true);
        $this->assertSame('ok', $body['status']);
        $this->assertNotEmpty($body['timestamp']);
    }
}
