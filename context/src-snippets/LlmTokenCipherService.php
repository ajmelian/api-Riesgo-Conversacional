<?php

declare(strict_types=1);

namespace App\Services\Security;

use RuntimeException;

/**
 * Nombre: LlmTokenCipherService
 *
 * Descripción de la funcionalidad:
 * Servicio responsable de cifrar y descifrar tokens temporales de proveedores LLM
 * usando libsodium y cifrado autenticado XChaCha20-Poly1305.
 *
 * Parámetros de entrada:
 * - string $encryptionKey Clave binaria de cifrado del sistema.
 *
 * Parámetros de salida:
 * - Servicio inicializado para cifrado y descifrado de secretos temporales.
 *
 * Método de uso:
 * $cipher = new LlmTokenCipherService($encryptionKey);
 * $encryptedToken = $cipher->encryptToken($plainToken);
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final readonly class LlmTokenCipherService
{
    /**
     * Nombre: __construct
     *
     * Descripción de la funcionalidad:
     * Inicializa el servicio validando la longitud de la clave de cifrado.
     *
     * Parámetros de entrada:
     * - string $encryptionKey Clave binaria de cifrado.
     *
     * Parámetros de salida:
     * - void
     *
     * Método de uso:
     * new LlmTokenCipherService($encryptionKey);
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function __construct(
        private string $encryptionKey
    ) {
        if (mb_strlen($this->encryptionKey, '8bit') !== SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES) {
            throw new RuntimeException('La clave de cifrado no tiene una longitud válida.');
        }
    }

    /**
     * Nombre: encryptToken
     *
     * Descripción de la funcionalidad:
     * Cifra un token LLM para almacenamiento temporal seguro.
     *
     * Parámetros de entrada:
     * - string $plainToken Token original del proveedor LLM.
     *
     * Parámetros de salida:
     * - array{cipherText: string, nonce: string} Token cifrado y nonce codificados en Base64.
     *
     * Método de uso:
     * $encryptedToken = $cipher->encryptToken($plainToken);
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function encryptToken(string $plainToken): array
    {
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);

        $cipherText = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $plainToken,
            '',
            $nonce,
            $this->encryptionKey
        );

        sodium_memzero($plainToken);

        return [
            'cipherText' => base64_encode($cipherText),
            'nonce' => base64_encode($nonce),
        ];
    }

    /**
     * Nombre: decryptToken
     *
     * Descripción de la funcionalidad:
     * Descifra un token LLM almacenado temporalmente para invocar al proveedor externo.
     *
     * Parámetros de entrada:
     * - string $cipherText Token cifrado en Base64.
     * - string $nonce Nonce en Base64.
     *
     * Parámetros de salida:
     * - string Token LLM descifrado.
     *
     * Método de uso:
     * $plainToken = $cipher->decryptToken($cipherText, $nonce);
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function decryptToken(string $cipherText, string $nonce): string
    {
        $decodedCipherText = base64_decode($cipherText, true);
        $decodedNonce = base64_decode($nonce, true);

        if ($decodedCipherText === false || $decodedNonce === false) {
            throw new RuntimeException('El token cifrado no tiene un formato válido.');
        }

        $plainToken = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            $decodedCipherText,
            '',
            $decodedNonce,
            $this->encryptionKey
        );

        if ($plainToken === false) {
            throw new RuntimeException('No se pudo descifrar el token LLM.');
        }

        return $plainToken;
    }
}
