<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Invoice\InvoiceDto;
use App\Dto\Core\Telegram\Message\MessageDto;
use App\Entity\Visitor\VisitorEvent;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\Visitor\VisitorRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;

class CommandHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioRepository $behaviorScenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorRepository $visitorRepository,
    ) {
    }

    public function handle(VisitorEvent $visitorEvent): void
    {
        $behaviorScenarioId = $visitorEvent->getBehaviorScenario();

        $behaviorScenario = $this->behaviorScenarioRepository->find($behaviorScenarioId);
        $behaviorScenarioContent = $behaviorScenario->getContent();

        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());
        $visitor = $this->visitorRepository->find($visitorSession->getVisitorId());

        if ($behaviorScenarioContent['product']){
            $invoiceDto = (new InvoiceDto())
                ->setChatId($visitor->getChannelVisitorId())
                ->setTitle($behaviorScenarioContent['product']['name'] ?? 'asdasd sa')
                ->setDescription('его тут пока что нет')
                ->setPayload("200")
                ->setProviderToken("381764678:TEST:60367")
                ->setCurrency("RUB")
                ->setPrices( json_encode([
                    [
                        'label' => 'first',
                        'amount' => "20000",
                    ] ])
                )
                ->setPhotoUrl($behaviorScenarioContent['product']['imageUri'])
            ;

            if(!empty($behaviorScenarioContent['replyMarkup'])){
                $invoiceDto->setReplyMarkup($behaviorScenarioContent['replyMarkup']);
            }

            $this->telegramService->sendInvoice($invoiceDto, '5109953245:AAE7TIhplLRxJdGmM27YSeSIdJdOh4ZXVVY');

        } else {
            $messageDto = (new MessageDto())
                ->setChatId($visitor->getChannelVisitorId())
                ->setText($behaviorScenarioContent['message'])
            ;

            if(!empty($behaviorScenarioContent['replyMarkup'])){
                $messageDto->setReplyMarkup($behaviorScenarioContent['replyMarkup']);
            }

            $this->telegramService->sendMessage($messageDto, '5109953245:AAE7TIhplLRxJdGmM27YSeSIdJdOh4ZXVVY');
        }
    }
}
