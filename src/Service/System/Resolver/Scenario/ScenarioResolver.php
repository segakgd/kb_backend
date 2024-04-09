<?php

namespace App\Service\System\Resolver\Scenario;

use App\Dto\Contract\ContractMessageDto;
use App\Dto\SessionCache\Cache\CacheStepDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Contract;

class ScenarioResolver
{
    public function resolve(Contract $contract, CacheStepDto $scenarioStep): Contract
    {
        $contractMessage = MessageHelper::createContractMessage();

        $this->setMessage($contractMessage, $scenarioStep);
        $this->setKeyboard($contractMessage, $scenarioStep);

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    private function setMessage(ContractMessageDto $contractMessage, CacheStepDto $scenarioStep): void
    {
        if (!$scenarioStep->getMessage()) {
            return;
        }

        $contractMessage->setMessage($scenarioStep->getMessage());
    }

    private function setKeyboard(ContractMessageDto $contractMessage, CacheStepDto $scenarioStep): void
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
        // todo write an implementation
    }
}
