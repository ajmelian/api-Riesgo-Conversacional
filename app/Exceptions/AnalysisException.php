<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Nombre: AnalysisException
 *
 * Descripción de la funcionalidad:
 * Excepción lanzada cuando el análisis de conversación falla
 * (error del LLM, timeout, respuesta malformada).
 * Se traduce a código HTTP 400.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AnalysisException extends RuntimeException
{
}
