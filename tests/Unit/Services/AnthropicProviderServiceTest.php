<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\Llm\AnthropicProviderService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Nombre: AnthropicProviderServiceTest
 *
 * Descripción de la funcionalidad:
 * Tests unitarios para el adaptador de Anthropic Claude.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AnthropicProviderServiceTest extends CIUnitTestCase
{
    public function testClassImplementsLlmProviderInterface(): void
    {
        $service = new AnthropicProviderService();
        $this->assertInstanceOf(\App\Services\Llm\LlmProviderInterface::class, $service);
    }

    public function testMinimizationRemovesSensitiveData(): void
    {
        $service = new AnthropicProviderService();
        $ref = new \ReflectionClass($service);
        $method = $ref->getMethod('minimizeMessages');

        $messages = [
            ['text' => 'Mensaje de prueba', 'participantId' => 'user_a', 'ip' => '192.168.1.1'],
        ];
        $result = $method->invoke($service, $messages);

        $content = $result[0]['content'];
        $this->assertStringContainsString('user_a', $content);
        $this->assertStringNotContainsString('192.168.1.1', $content);
    }
}
