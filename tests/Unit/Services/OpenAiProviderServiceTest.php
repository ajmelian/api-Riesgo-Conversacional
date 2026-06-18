<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\Llm\OpenAiProviderService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Nombre: OpenAiProviderServiceTest
 *
 * Descripción de la funcionalidad:
 * Tests unitarios para el adaptador de OpenAI.
 * Verifica minimización de datos y manejo de errores.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class OpenAiProviderServiceTest extends CIUnitTestCase
{
    public function testClassImplementsLlmProviderInterface(): void
    {
        $service = new OpenAiProviderService();
        $this->assertInstanceOf(\App\Services\Llm\LlmProviderInterface::class, $service);
    }

    public function testMinimizationRemovesSensitiveData(): void
    {
        $service = new OpenAiProviderService();
        $ref = new \ReflectionClass($service);
        $method = $ref->getMethod('minimizeMessages');

        $messages = [
            ['text' => 'Hola', 'participantId' => 'user_1', 'ip' => '10.0.0.1', 'email' => 'test@test.com'],
        ];
        $result = $method->invoke($service, $messages);

        $content = $result[0]['content'];
        $this->assertStringContainsString('user_1', $content);
        $this->assertStringNotContainsString('10.0.0.1', $content);
        $this->assertStringNotContainsString('test@test.com', $content);
        $this->assertSame('user', $result[0]['role']);
    }
}
