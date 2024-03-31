<?php

namespace App\Service\System\Resolver;

use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Contract;

/**
 * @deprecated need refactoring
 */
class ScenarioResolver
{
    public function resolve(Contract $contract, array $scenarioStep): Contract
    {
        $contractMessage = MessageHelper::createContractMessage();

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

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }
}
