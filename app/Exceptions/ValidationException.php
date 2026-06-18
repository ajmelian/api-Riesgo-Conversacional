<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Nombre: ValidationException
 *
 * Descripción de la funcionalidad:
 * Excepción lanzada cuando la validación de entrada falla.
 * Se traduce a código HTTP 422 con detalles de violaciones.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class ValidationException extends RuntimeException
{
    /**
     * @param array<int, array{field: string, message: string}> $violations
     */
    public function __construct(
        string $message = 'Error de validación.',
        private readonly array $violations = []
    ) {
        parent::__construct($message);
    }

    /**
     * @return array<int, array{field: string, message: string}>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
