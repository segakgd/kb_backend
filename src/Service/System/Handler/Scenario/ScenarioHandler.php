<?php

namespace App\Service\System\Handler\Scenario;

use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\System\Contract;

class ScenarioHandler
{
    public function handle(Contract $contract, array $scenarioStep): Contract
    {
        $contractMessage = MessageHelper::createContractMessage('');

        if ($scenarioStep['message']) {
            $contractMessage->setMessage($scenarioStep['message']);
        }

        if (!empty($scenarioStep['keyboard'])) {
            $replyMarkups = KeyboardHelper::mapKeyboard($scenarioStep);

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
}
