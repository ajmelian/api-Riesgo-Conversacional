# Fase 2A — LlmTokenCipherService (cifrado libsodium)

**Objetivo:** Servicio que cifra/descifra tokens LLM con `sodium_crypto_aead_xchacha20poly1305_ietf_encrypt`.

| # | Tarea | Estado | Dependencias |
|---|-------|--------|-------------|
| 01 | Tests unitarios: LlmTokenCipherServiceTest | ⬜ pendiente | fase-0 |
| 02 | Implementar LlmTokenCipherService | ⬜ pendiente | 01 |

**Verificación final de fase:** 6 tests unitarios verdes (roundtrip, nonce único, clave inválida, nonce incorrecto, ciphertext manipulado, base64 inválido).
