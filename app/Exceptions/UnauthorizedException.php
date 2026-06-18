<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Nombre: UnauthorizedException
 *
 * Descripción de la funcionalidad:
 * Excepción lanzada cuando las credenciales del cliente integrador no son válidas.
 * Se traduce a código HTTP 401.
 *
 * Parámetros de entrada:
 * - string $message Mensaje descriptivo.
 *
 * Parámetros de salida:
 * - void
 *
 * Método de uso:
 * throw new UnauthorizedException('Credenciales inválidas');
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class UnauthorizedException extends RuntimeException
{
}
