<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\Risk\RiskScoringService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Nombre: RiskScoringServiceTest
 *
 * Descripción de la funcionalidad:
 * Tests unitarios para el motor de scoring de riesgo conversacional.
 *
 * Fecha de desarrollo: 18/06/2026
 * Autor: Aythami Melián Perdomo
 */
final class RiskScoringServiceTest extends CIUnitTestCase
{
    private RiskScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RiskScoringService();
    }

    public function testNormalConversationScoresLevel5(): void
    {
        $messages = [
            ['text' => 'Hola, ¿cómo estás?'],
            ['text' => 'Bien, gracias. ¿Y tú?'],
        ];
        $result = $this->service->score(['riskLevel' => 5, 'sentiment' => 'neutral'], [], false);

        $this->assertSame(5, $result['riskLevel']);
        $this->assertSame('normalConversation', $result['riskLabel']);
        $this->assertSame('neutral', $result['sentiment']);
        $this->assertSame('noAction', $result['recommendedAction']);
        $this->assertFalse($result['requiresHumanReview']);
        $this->assertFalse($result['automaticExternalNotification']);
    }

    public function testMildHostilityScoresLevel4(): void
    {
        $messages = [
            ['text' => 'Eres un idiota, no sabes nada.'],
        ];
        $deterministicFlags = ['hasInsults' => true];
        $result = $this->service->score(['riskLevel' => 4, 'sentiment' => 'tense'], $deterministicFlags, false);

        $this->assertSame(4, $result['riskLevel']);
        $this->assertSame('mildHostility', $result['riskLabel']);
        $this->assertSame('tense', $result['sentiment']);
        $this->assertSame('softWarning', $result['recommendedAction']);
        $this->assertFalse($result['requiresHumanReview']);
    }

    public function testHarassmentScoresLevel3(): void
    {
        $messages = [
            ['text' => 'Nadie te quiere aquí, vete.'],
            ['text' => 'Siempre molestando a todos, eres un fracaso.'],
        ];
        $deterministicFlags = ['hasInsults' => true, 'hasHumiliation' => true];
        $result = $this->service->score(['riskLevel' => 3, 'sentiment' => 'hostile'], $deterministicFlags, false);

        $this->assertSame(3, $result['riskLevel']);
        $this->assertSame('harassmentWarning', $result['riskLabel']);
        $this->assertSame('hostile', $result['sentiment']);
        $this->assertSame('formalWarning', $result['recommendedAction']);
    }

    public function testThreatRequiresHumanReview(): void
    {
        $deterministicFlags = ['hasThreat' => true];
        $result = $this->service->score(['riskLevel' => 2, 'sentiment' => 'hostile'], $deterministicFlags, false);

        $this->assertSame(2, $result['riskLevel']);
        $this->assertSame('highRiskHarassment', $result['riskLabel']);
        $this->assertTrue($result['requiresHumanReview']);
        $this->assertSame('temporaryLockAndModeratorReview', $result['recommendedAction']);
    }

    public function testSelfHarmScoresLevel1(): void
    {
        $deterministicFlags = ['hasSelfHarm' => true];
        $result = $this->service->score(['riskLevel' => 1, 'sentiment' => 'critical'], $deterministicFlags, false);

        $this->assertSame(1, $result['riskLevel']);
        $this->assertSame('criticalRisk', $result['riskLabel']);
        $this->assertSame('critical', $result['sentiment']);
        $this->assertTrue($result['requiresHumanReview']);
        $this->assertTrue($result['legalEscalationCandidate']);
        $this->assertSame('urgentHumanReview', $result['recommendedAction']);
    }

    public function testDeterministicRulesHavePriority(): void
    {
        $deterministicFlags = ['hasThreat' => true];
        $llmResult = ['riskLevel' => 5, 'sentiment' => 'neutral'];
        $result = $this->service->score($llmResult, $deterministicFlags, false);

        $this->assertSame(2, $result['riskLevel']);
    }

    public function testContainsMinorsIncreasesRisk(): void
    {
        $llmResult = ['riskLevel' => 3, 'sentiment' => 'hostile'];
        $deterministicFlags = ['hasInsults' => true, 'hasHumiliation' => true];

        $resultWithoutMinors = $this->service->score($llmResult, $deterministicFlags, false);
        $resultWithMinors = $this->service->score($llmResult, $deterministicFlags, true);

        // Con menores, nivel 3 baja a 2 y requiere revisión humana
        $this->assertSame(3, $resultWithoutMinors['riskLevel']);
        $this->assertSame(2, $resultWithMinors['riskLevel']);
        $this->assertTrue($resultWithMinors['requiresHumanReview']);
        $this->assertFalse($resultWithoutMinors['requiresHumanReview']);
    }
}
