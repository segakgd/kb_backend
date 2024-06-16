<?php

namespace App\Service\Constructor\Core\Scenario;

use App\Dto\Responsible\ResponsibleMessageDto;
use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\Responsible;

class ScenarioResolver
{
    public function resolve(Responsible $responsible, CacheContractDto $cacheContractDto): Responsible
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage();

        $this->setMessage($responsibleMessage, $cacheContractDto);
        $this->setKeyboard($responsibleMessage, $cacheContractDto);

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    private function setMessage(ResponsibleMessageDto $responsibleMessageDto, CacheContractDto $cacheContractDto): void
    {
        if (!$cacheContractDto->getMessage()) {
            return;
        }

        $responsibleMessageDto->setMessage($cacheContractDto->getMessage());
    }

    private function setKeyboard(ResponsibleMessageDto $responsibleMessageDto, CacheContractDto $cacheContractDto): void
    {
        if (
            empty($cacheContractDto->getKeyboard())
            || is_null($cacheContractDto->getKeyboard()->getReplyMarkup())
        ) {
            return;
        }

        $replyMarkups = KeyboardHelper::mapKeyboard($cacheContractDto->getKeyboard());

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
