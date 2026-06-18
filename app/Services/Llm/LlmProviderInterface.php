<?php

declare(strict_types=1);

namespace App\Services\Llm;

/**
 * Nombre: LlmProviderInterface
 *
 * Descripción de la funcionalidad:
 * Contrato que deben implementar todos los adaptadores de proveedores LLM.
 * Define validación de token y análisis de conversaciones.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
interface LlmProviderInterface
{
    public function validateToken(string $token): bool;

    public function analyze(string $prompt, array $messages): array;
}
