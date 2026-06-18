# Tarea 2A.1 — Tests unitarios: LlmTokenCipherServiceTest

**Dependencias:** fase-0 (estructura de directorios, .env con LLM_TOKEN_ENCRYPTION_KEY)

**Descripción:** Escribir 6 tests unitarios para el servicio de cifrado de tokens LLM usando libsodium.

**Criterio de aceptación:**
- [ ] Test `LlmTokenCipherServiceTest` en `tests/Unit/Services/LlmTokenCipherServiceTest.php`
- [ ] `testEncryptDecryptRoundtrip`: cifra y descifra el mismo token correctamente
- [ ] `testEncryptProducesDifferentOutputEachTime`: mismo plaintext → distinto ciphertext (nonce único)
- [ ] `testRejectsInvalidKeyLength`: clave de longitud ≠ `SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES` lanza RuntimeException
- [ ] `testDecryptWithWrongNonceThrows`: nonce modificado lanza RuntimeException
- [ ] `testDecryptWithTamperedCiphertextThrows`: ciphertext manipulado lanza RuntimeException
- [ ] `testDecryptWithInvalidBase64Throws`: base64 inválido lanza RuntimeException
- [ ] El test falla inicialmente (RED — sin implementación)

**Ficheros implicados:**
- `tests/Unit/Services/LlmTokenCipherServiceTest.php`
