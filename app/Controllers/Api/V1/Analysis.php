<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Exceptions\AnalysisException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use App\Services\Risk\ConversationAnalysisService;
use CodeIgniter\RESTful\ResourceController;

/**
 * Nombre: Analysis
 *
 * Descripción de la funcionalidad:
 * Controlador que expone el endpoint POST /v1/conversations/analyze
 * para análisis de riesgo conversacional.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class Analysis extends ResourceController
{
    /**
     * Nombre: analyze
     *
     * Descripción de la funcionalidad:
     * Recibe una conversación, la analiza mediante reglas deterministas
     * y LLM, y devuelve el resultado del scoring.
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function analyze(): \CodeIgniter\HTTP\ResponseInterface
    {
        try {
            $clientId = (int) ($this->request->client_id ?? 0);
            $sessionId = (int) ($this->request->session_id ?? 0);

            $payload = $this->request->getJSON(true);
            if (! is_array($payload)) {
                return $this->failValidationErrors([
                    ['field' => 'body', 'message' => 'El cuerpo debe ser JSON válido.'],
                ]);
            }

            $session = $this->getSessionData($sessionId);
            $llmProvider = $session['llm_provider'] ?? 'openai';

            $service = new ConversationAnalysisService();
            $result = $service->analyze($payload, $clientId, $llmProvider, $sessionId);

            return $this->respond($result, 200);
        } catch (ValidationException $e) {
            return $this->response->setStatusCode(422)->setJSON([
                'error'      => 'validation_error',
                'message'    => $e->getMessage(),
                'traceId'    => '',
                'violations' => $e->getViolations(),
            ]);
        } catch (UnauthorizedException $e) {
            return $this->failUnauthorized($e->getMessage());
        } catch (AnalysisException $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'error'   => 'analysis_error',
                'message' => $e->getMessage(),
                'traceId' => '',
            ]);
        }
    }

    private function getSessionData(int $sessionId): array
    {
        $db = \Config\Database::connect();
        $session = $db->table('api_llm_sessions')
            ->where('id', $sessionId)
            ->get()
            ->getRowArray();

        if ($session === null) {
            throw new UnauthorizedException('Sesión no encontrada.');
        }

        return $session;
    }
}
