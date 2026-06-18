<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\Security\LlmTokenCipherService;
use CodeIgniter\Test\CIUnitTestCase;
use RuntimeException;

/**
 * Nombre: LlmTokenCipherServiceTest
 *
 * Descripción de la funcionalidad:
 * Tests unitarios para el servicio de cifrado/descifrado de tokens LLM
 * usando libsodium XChaCha20-Poly1305.
 *
 * Parámetros de entrada:
 * - Ninguno.
 *
 * Parámetros de salida:
 * - void
 *
 * Método de uso:
 * php vendor/bin/phpunit --filter=LlmTokenCipherServiceTest
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class LlmTokenCipherServiceTest extends CIUnitTestCase
{
    private string $validKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validKey = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES);
    }

    public function testEncryptDecryptRoundtrip(): void
    {
        $cipher = new LlmTokenCipherService($this->validKey);
        $plainToken = 'sk-test-token-12345-secret';

        $encrypted = $cipher->encryptToken($plainToken);
        $this->assertArrayHasKey('cipherText', $encrypted);
        $this->assertArrayHasKey('nonce', $encrypted);
        $this->assertNotEmpty($encrypted['cipherText']);
        $this->assertNotEmpty($encrypted['nonce']);

        $decrypted = $cipher->decryptToken($encrypted['cipherText'], $encrypted['nonce']);
        $this->assertSame($plainToken, $decrypted);
    }

    public function testEncryptProducesDifferentOutputEachTime(): void
    {
        $cipher = new LlmTokenCipherService($this->validKey);
        $plainToken = 'sk-test-token-12345-secret';

        $result1 = $cipher->encryptToken($plainToken);
        $result2 = $cipher->encryptToken($plainToken);

        $this->assertNotSame($result1['cipherText'], $result2['cipherText']);
        $this->assertNotSame($result1['nonce'], $result2['nonce']);
    }

    public function testRejectsInvalidKeyLength(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('La clave de cifrado no tiene una longitud válida.');

        new LlmTokenCipherService('too-short');
    }

    public function testDecryptWithWrongNonceThrows(): void
    {
        $cipher = new LlmTokenCipherService($this->validKey);
        $plainToken = 'sk-test-token-12345-secret';

        $encrypted = $cipher->encryptToken($plainToken);
        $wrongNonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No se pudo descifrar el token LLM.');

        $cipher->decryptToken($encrypted['cipherText'], base64_encode($wrongNonce));
    }

    public function testDecryptWithTamperedCiphertextThrows(): void
    {
        $cipher = new LlmTokenCipherService($this->validKey);
        $plainToken = 'sk-test-token-12345-secret';

        $encrypted = $cipher->encryptToken($plainToken);
        $tamperedCipher = base64_encode(
            random_bytes(strlen(base64_decode($encrypted['cipherText'], true)))
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No se pudo descifrar el token LLM.');

        $cipher->decryptToken($tamperedCipher, $encrypted['nonce']);
    }

    public function testDecryptWithInvalidBase64Throws(): void
    {
        $cipher = new LlmTokenCipherService($this->validKey);
        $plainToken = 'sk-test-token-12345-secret';

        $encrypted = $cipher->encryptToken($plainToken);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('El token cifrado no tiene un formato válido.');

        $cipher->decryptToken('!!!invalid!!!', $encrypted['nonce']);
    }
}
