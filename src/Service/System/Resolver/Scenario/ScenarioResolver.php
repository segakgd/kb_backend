<?php

namespace App\Service\System\Resolver\Scenario;

use App\Dto\Contract\ResponsibleMessageDto;
use App\Dto\SessionCache\Cache\CacheStepDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Responsible;

class ScenarioResolver
{
    public function resolve(Responsible $responsible, CacheStepDto $scenarioStep): Responsible
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage();

        $this->setMessage($responsibleMessage, $scenarioStep);
        $this->setKeyboard($responsibleMessage, $scenarioStep);

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    private function setMessage(ResponsibleMessageDto $responsibleMessageDto, CacheStepDto $scenarioStep): void
    {
        if (!$scenarioStep->getMessage()) {
            return;
        }

        $responsibleMessageDto->setMessage($scenarioStep->getMessage());
    }

    private function setKeyboard(ResponsibleMessageDto $responsibleMessageDto, CacheStepDto $scenarioStep): void
    {
        if (empty($scenarioStep->getKeyboard())) {
            return;
        }

        $replyMarkups = KeyboardHelper::mapKeyboard($scenarioStep->getKeyboard());

        if (empty($replyMarkups)) {
            return;
        }

        $responsibleMessageDto->setKeyBoard($replyMarkups);
    }

    private function handleAttached(array $attachedData): void
    {
        // todo write an implementation
    }
}
