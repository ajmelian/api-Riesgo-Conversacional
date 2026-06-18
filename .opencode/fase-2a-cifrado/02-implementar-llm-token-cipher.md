# Tarea 2A.2 — Implementar LlmTokenCipherService

**Dependencias:** tarea 2A.1 (tests RED)

**Descripción:** Implementar el servicio de cifrado/descifrado basado en `context/src-snippets/LlmTokenCipherService.php`.

**Criterio de aceptación:**
- [ ] Clase `LlmTokenCipherService` en `app/Services/Security/LlmTokenCipherService.php`
- [ ] `declare(strict_types=1)`, clase `final readonly`
- [ ] Constructor valida longitud de clave contra `SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES`
- [ ] `encryptToken(string $plainToken): array{cipherText: string, nonce: string}` — cifra con `sodium_crypto_aead_xchacha20poly1305_ietf_encrypt`, limpia memoria con `sodium_memzero`, devuelve Base64
- [ ] `decryptToken(string $cipherText, string $nonce): string` — decodifica Base64, descifra con `sodium_crypto_aead_xchacha20poly1305_ietf_decrypt`
- [ ] PHPDoc en español con plantilla completa
- [ ] Los 6 tests de `LlmTokenCipherServiceTest` pasan a verde
- [ ] `php vendor/bin/phpunit --filter=LlmTokenCipherService` → 6 tests verdes

**Ficheros implicados:**
- `app/Services/Security/LlmTokenCipherService.php`

**Referencia:** `context/src-snippets/LlmTokenCipherService.php`
