<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Nombre: CleanupLlmSessionsCommand
 *
 * Descripción de la funcionalidad:
 * Comando CLI de CodeIgniter 4 para eliminar sesiones LLM expiradas o revocadas
 * en instalaciones sin contenedores, ejecutado mediante cron o systemd timer.
 *
 * Parámetros de entrada:
 * - No requiere parámetros obligatorios.
 *
 * Parámetros de salida:
 * - Código de salida CLI y mensaje de sesiones eliminadas.
 *
 * Método de uso:
 * php spark riskapi:cleanup-llm-sessions
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class CleanupLlmSessionsCommand extends BaseCommand
{
    protected $group = 'RiskAPI';
    protected $name = 'riskapi:cleanup-llm-sessions';
    protected $description = 'Elimina sesiones LLM expiradas o revocadas.';

    /**
     * Nombre: run
     *
     * Descripción de la funcionalidad:
     * Ejecuta la limpieza de sesiones efímeras en la base de datos MySQL/MariaDB.
     *
     * Parámetros de entrada:
     * - array<int, string> $params Parámetros CLI opcionales.
     *
     * Parámetros de salida:
     * - void
     *
     * Método de uso:
     * php spark riskapi:cleanup-llm-sessions
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function run(array $params): void
    {
        $database = db_connect();

        $database->query(
            "DELETE FROM api_llm_sessions WHERE expires_at < UTC_TIMESTAMP() OR revoked_at IS NOT NULL"
        );

        CLI::write('Limpieza de sesiones LLM ejecutada correctamente.', 'green');
    }
}
