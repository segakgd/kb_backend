<?php

namespace App\Service\System\Resolver;

use App\Dto\Contract\ContractMessageDto;
use App\Dto\Scenario\ScenarioStepDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Contract;

class ScenarioResolver
{
    public function resolve(Contract $contract, ScenarioStepDto $scenarioStep): Contract
    {
        $contractMessage = MessageHelper::createContractMessage();

        $this->setMessage($contractMessage, $scenarioStep);
        $this->setKeyboard($contractMessage, $scenarioStep);

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    private function setMessage(ContractMessageDto $contractMessage, ScenarioStepDto $scenarioStep): void
    {
        if (!$scenarioStep['message']) {
            return;
        }

        $contractMessage->setMessage($scenarioStep['message']);
    }

    private function setKeyboard(ContractMessageDto $contractMessage, ScenarioStepDto $scenarioStep): void
    {
        if (empty($scenarioStep->getKeyboard())) {
            return;
        }

        $replyMarkups = KeyboardHelper::mapKeyboard($scenarioStep->getKeyboard());

        if (empty($replyMarkups)) {
            return;
        }

        $contractMessage->setKeyBoard($replyMarkups);
    }

    private function handleAttached(array $attachedData): void
    {
        dd('сработали attached', $attachedData);
    }
}
