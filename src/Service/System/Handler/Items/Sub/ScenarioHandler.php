<?php

namespace App\Service\System\Handler\Items\Sub;

use App\Entity\Scenario\Scenario;
use App\Service\System\Handler\PreMessageDto;

class ScenarioHandler
{
    public function handle(PreMessageDto $preMessageDto, Scenario $scenario): PreMessageDto
    {
        $scenarioSteps = $scenario->getSteps();

        foreach ($scenarioSteps as $scenarioStep) {
            // todo вот тут будет проблема, будет выбран последний шаг!

            if ($scenarioStep['message']) {
                $preMessageDto->setMessage($scenarioStep['message']);
            }

            if (!empty($scenarioStep['keyboard'])) {
                $replyMarkups = $this->keyboard($scenarioStep);

                if (!empty($replyMarkups)) {
                    $preMessageDto->setKeyBoard($replyMarkups);
                }
            }

            if (!empty($scenarioStep['attached'])) {
                dd('сработали attached', $scenarioStep['attached']);
            }
        }

        return $preMessageDto;
    }

    private function keyboard(array $scenarioStep): array
    {
        $replyMarkups = [];

        foreach ($scenarioStep['keyboard']['replyMarkup'] as $key => $replyMarkup) {
            foreach ($replyMarkup as $keyItem => $replyMarkupItem) {
                $replyMarkups[$key][$keyItem]['text'] = $replyMarkupItem['text'];
            }
        }

        return $replyMarkups;
    }
}
