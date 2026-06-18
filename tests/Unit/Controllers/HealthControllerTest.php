<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;

/**
 * Nombre: HealthControllerTest
 *
 * Descripción de la funcionalidad:
 * Test unitario del controlador Health. Verifica que devuelve 200 con status ok
 * y timestamp en formato ISO8601.
 *
 * Parámetros de entrada:
 * - Ninguno.
 *
 * Parámetros de salida:
 * - void
 *
 * Método de uso:
 * php vendor/bin/phpunit --filter=HealthControllerTest
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class HealthControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;

    public function testHealthReturnsOkStatus(): void
    {
        $this->controller('App\Controllers\Api\V1\Health')
            ->execute('index');

        $this->assertNotEmpty($this->response);
        $body = json_decode((string) $this->response->getBody(), true);
        $this->assertIsArray($body);
        $this->assertSame('ok', $body['status']);
    }

    public function testHealthReturnsIso8601Timestamp(): void
    {
        $this->controller('App\Controllers\Api\V1\Health')
            ->execute('index');

        $body = json_decode((string) $this->response->getBody(), true);
        $this->assertNotEmpty($body['timestamp']);

        $date = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $body['timestamp']);
        $this->assertNotFalse($date, 'timestamp must be valid ISO8601');
    }
}
