<?php

declare(strict_types=1);

namespace App\Services\Risk;

/**
 * Nombre: RiskScoringService
 *
 * Descripción de la funcionalidad:
 * Motor de scoring que combina reglas deterministas, resultado del LLM,
 * contexto de menores y reincidencia para clasificar el riesgo conversacional
 * en niveles DEFCON invertido (5=normal, 1=crítico).
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class RiskScoringService
{
    private const RISK_LABELS = [
        5 => 'normalConversation',
        4 => 'mildHostility',
        3 => 'harassmentWarning',
        2 => 'highRiskHarassment',
        1 => 'criticalRisk',
    ];

    private const RECOMMENDED_ACTIONS = [
        5 => 'noAction',
        4 => 'softWarning',
        3 => 'formalWarning',
        2 => 'temporaryLockAndModeratorReview',
        1 => 'urgentHumanReview',
    ];

    /**
     * Nombre: score
     *
     * Descripción de la funcionalidad:
     * Calcula el riesgo conversacional combinando reglas deterministas
     * con el resultado del LLM y el contexto de menores.
     *
     * Parámetros de entrada:
     * - array $llmResult Resultado del proveedor LLM (riskLevel, sentiment).
     * - array $deterministicFlags Banderas de reglas deterministas.
     * - bool $containsMinors Indica si hay menores en la conversación.
     *
     * Parámetros de salida:
     * - array Resultado del scoring con riskLevel, riskLabel, sentiment,
     *        confidence, requiresHumanReview, recommendedAction,
     *        automaticExternalNotification, legalEscalationCandidate.
     *
     * Fecha de desarrollo: 18/06/2026
     * Autor: Aythami Melián Perdomo
     */
    public function score(array $llmResult, array $deterministicFlags, bool $containsMinors): array
    {
        $deterministicLevel = $this->calculateDeterministicLevel($deterministicFlags);
        $llmLevel = (int) ($llmResult['riskLevel'] ?? 5);

        // El nivel más grave prevalece
        $riskLevel = min($deterministicLevel, $llmLevel);

        // Incrementar riesgo si hay menores
        if ($containsMinors && $riskLevel > 1) {
            $riskLevel = max(1, $riskLevel - 1);
        }

        $requiresHumanReview = $riskLevel <= 2;
        $legalEscalationCandidate = ($deterministicFlags['hasSelfHarm'] ?? false)
            || ($deterministicFlags['hasGrooming'] ?? false);

        $sentiment = $this->determineSentiment($riskLevel, $llmResult['sentiment'] ?? 'neutral');

        return [
            'riskLevel'                    => $riskLevel,
            'riskLabel'                    => self::RISK_LABELS[$riskLevel],
            'sentiment'                    => $sentiment,
            'confidence'                   => round($llmResult['confidence'] ?? 0.85, 4),
            'requiresHumanReview'          => $requiresHumanReview,
            'recommendedAction'            => self::RECOMMENDED_ACTIONS[$riskLevel],
            'automaticExternalNotification' => false,
            'legalEscalationCandidate'     => $legalEscalationCandidate,
        ];
    }

    private function calculateDeterministicLevel(array $flags): int
    {
        if (! empty($flags['hasSelfHarm']) || ! empty($flags['hasGrooming'])) {
            return 1;
        }
        if (! empty($flags['hasThreat']) || ! empty($flags['hasCoercion'])) {
            return 2;
        }
        if (! empty($flags['hasHumiliation']) || (! empty($flags['hasInsults']) && ! empty($flags['hasRepeatedHarassment']))) {
            return 3;
        }
        if (! empty($flags['hasInsults'])) {
            return 4;
        }

        return 5;
    }

    private function determineSentiment(int $riskLevel, string $llmSentiment): string
    {
        if ($riskLevel <= 1) {
            return 'critical';
        }
        if ($riskLevel === 2) {
            return 'hostile';
        }

        $validSentiments = ['neutral', 'positive', 'tense', 'hostile', 'critical'];

        return in_array($llmSentiment, $validSentiments, true) ? $llmSentiment : 'neutral';
    }
}
