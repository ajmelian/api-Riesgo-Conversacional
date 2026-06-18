<?php

declare(strict_types=1);

namespace App\Services\Llm;

use App\Exceptions\AnalysisException;
use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

/**
 * Nombre: AnthropicProviderService
 *
 * Descripción de la funcionalidad:
 * Adaptador para Anthropic Claude Messages API. Minimiza datos antes del envío
 * y valida la respuesta JSON.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class AnthropicProviderService implements LlmProviderInterface
{
    private CURLRequest $client;
    private string $baseUrl;

    public function __construct()
    {
        $this->client = Services::curlrequest();
        $this->baseUrl = rtrim((string) env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com'), '/');
    }

    public function validateToken(string $token): bool
    {
        try {
            $response = $this->client->request('GET', $this->baseUrl . '/v1/models', [
                'headers' => [
                    'x-api-key'    => $token,
                    'anthropic-version' => '2023-06-01',
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
            'model'      => 'claude-3-haiku-20240307',
            'max_tokens' => 1024,
            'temperature' => 0.3,
            'system'     => $prompt,
            'messages'   => $minimizedMessages,
        ];

        try {
            $response = $this->client->request('POST', $this->baseUrl . '/v1/messages', [
                'json'        => $payload,
                'headers'     => [
                    'x-api-key'         => env('DUMMY_TOKEN', ''),
                    'anthropic-version' => '2023-06-01',
                    'Content-Type'      => 'application/json',
                ],
                'http_errors' => false,
                'timeout'     => 4,
            ]);
        } catch (\Throwable $e) {
            throw new AnalysisException('Error de conexión con Anthropic: ' . $e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new AnalysisException('Anthropic devolvió error: ' . $response->getStatusCode());
        }

        $body = json_decode((string) $response->getBody(), true);

        if (! is_array($body)) {
            throw new AnalysisException('Anthropic no devolvió JSON válido.');
        }

        $textContent = null;
        foreach (($body['content'] ?? []) as $block) {
            if (($block['type'] ?? '') === 'text') {
                $textContent = $block['text'] ?? null;
                break;
            }
        }

        if ($textContent === null) {
            throw new AnalysisException('Anthropic no devolvió contenido válido.');
        }

        $parsed = json_decode($textContent, true);
        if (! is_array($parsed)) {
            throw new AnalysisException('Anthropic no devolvió JSON válido en el contenido.');
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
