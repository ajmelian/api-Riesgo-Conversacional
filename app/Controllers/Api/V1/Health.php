<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

/**
 * Nombre: Health
 *
 * Descripción de la funcionalidad:
 * Controlador que expone el endpoint de estado operativo de la API.
 * Devuelve status ok y timestamp UTC en formato ISO8601.
 *
 * Parámetros de entrada:
 * - Ninguno.
 *
 * Parámetros de salida:
 * - JSON {status: string, timestamp: string} con código 200.
 *
 * Método de uso:
 * GET /v1/health
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class Health extends ResourceController
{
    /**
     * Nombre: index
     *
     * Descripción de la funcionalidad:
     * Devuelve el estado operativo de la API sin requerir autenticación.
     *
     * Parámetros de entrada:
     * - Ninguno.
     *
     * Parámetros de salida:
     * - Respuesta JSON con status ok y timestamp ISO8601.
     *
     * Método de uso:
     * GET /v1/health
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->respond([
            'status'    => 'ok',
            'timestamp' => gmdate(\DateTimeInterface::ATOM),
        ]);
    }
}
