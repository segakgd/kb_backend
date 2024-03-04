<?php

namespace App\Service\System\Handler\Items\Sub;

use App\Helper;
use App\Service\System\Handler\Contract;

class ScenarioHandler
{
    public function handle(Contract $contract, array $scenarioStep): Contract
    {
        $contractMessage = Helper::createContractMessage('');

        if ($scenarioStep['message']) {
            $contractMessage->setMessage($scenarioStep['message']);
        }

        if (!empty($scenarioStep['keyboard'])) {
            $replyMarkups = $this->keyboard($scenarioStep);

            if (!empty($replyMarkups)) {
                $contractMessage->setKeyBoard($replyMarkups);
            }
        }

        if (!empty($scenarioStep['attached'])) {
            dd('сработали attached', $scenarioStep['attached']);
        }

        $contract->addMessage($contractMessage);

        return $contract;
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
