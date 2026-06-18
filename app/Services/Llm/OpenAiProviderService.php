<?php

declare(strict_types=1);

namespace App\Services\Llm;

use App\Exceptions\AnalysisException;
use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

/**
 * Nombre: OpenAiProviderService
 *
 * Descripción de la funcionalidad:
 * Adaptador para OpenAI Chat Completions API. Minimiza datos antes del envío
 * y valida la respuesta JSON.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class OpenAiProviderService implements LlmProviderInterface
{
    private CURLRequest $client;
    private string $baseUrl;

    public function __construct()
    {
        $this->client = Services::curlrequest();
        $this->baseUrl = rtrim((string) env('OPENAI_BASE_URL', 'https://api.openai.com'), '/');
    }

    public function validateToken(string $token): bool
    {
        try {
            $response = $this->client->request('GET', $this->baseUrl . '/v1/models', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'http_errors' => false,
                'timeout'     => 5,
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Throwable) {
            return false;
        }
    }

    public function analyze(string $prompt, array $messages): array
    {
        $minimizedMessages = $this->minimizeMessages($messages);

        $payload = [
            'model'       => 'gpt-4o-mini',
            'temperature' => 0.3,
            'messages'    => array_merge(
                [['role' => 'system', 'content' => $prompt]],
                $minimizedMessages
            ),
            'response_format' => ['type' => 'json_object'],
        ];

        try {
            $response = $this->client->request('POST', $this->baseUrl . '/v1/chat/completions', [
                'json'        => $payload,
                'headers'     => [
                    'Authorization' => 'Bearer ' . env('DUMMY_TOKEN', ''),
                    'Content-Type'  => 'application/json',
                ],
                'http_errors' => false,
                'timeout'     => 4,
            ]);
        } catch (\Throwable $e) {
            throw new AnalysisException('Error de conexión con OpenAI: ' . $e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new AnalysisException('OpenAI devolvió error: ' . $response->getStatusCode());
        }

        $body = json_decode((string) $response->getBody(), true);

        if (! is_array($body)) {
            throw new AnalysisException('OpenAI no devolvió JSON válido.');
        }

        $content = $body['choices'][0]['message']['content'] ?? null;
        if ($content === null) {
            throw new AnalysisException('OpenAI no devolvió contenido válido.');
        }

        $parsed = json_decode($content, true);
        if (! is_array($parsed)) {
            throw new AnalysisException('OpenAI no devolvió JSON válido en el contenido.');
        }

        return $parsed;
    }

    private function minimizeMessages(array $messages): array
    {
        $minimized = [];
        foreach ($messages as $msg) {
            $text = $msg['text'] ?? '';
            $participantId = $msg['participantId'] ?? 'unknown';
            $minimized[] = [
                'role'    => 'user',
                'content' => "Participant {$participantId}: {$text}",
            ];
        }

        return $minimized;
    }
}
