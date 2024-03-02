<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Entity\Visitor\VisitorEvent;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use Exception;

/** @deprecated временно */
class CommandHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioRepository $scenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $visitorEvent): bool
    {
        $behaviorScenarioId = $visitorEvent->getBehaviorScenario();

        $behaviorScenario = $this->scenarioRepository->find($behaviorScenarioId);

        $behaviorScenarioContent = $behaviorScenario->getContent();

        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());

        if (!$visitorSession){
            throw new Exception('Сессии этого собития не существует не существует или у сессии уже другое событие'); // todo при таком раскладе можно удалить событие
        }

        //        if ($behaviorScenarioContent['product']){
//            $invoiceDto = (new InvoiceDto())
//                ->setChatId($visitorSession->getChannelId())
//                ->setTitle($behaviorScenarioContent['product']['name'] ?? 'asdasd sa')
//                ->setDescription('его тут пока что нет')
//                ->setPayload("200")
//                ->setProviderToken("381764678:TEST:60367")
//                ->setCurrency("RUB")
//                ->setPrices( json_encode([
//                    [
//                        'label' => 'first',
//                        'amount' => "20000",
//                    ] ])
//                )
//                ->setPhotoUrl($behaviorScenarioContent['product']['imageUri'])
//            ;
//
//            if(!empty($behaviorScenarioContent['replyMarkup'])){
//                $invoiceDto->setReplyMarkup($behaviorScenarioContent['replyMarkup']);
//            }
//
//            $this->telegramService->sendInvoice($invoiceDto, '5109953245:AAE7TIhplLRxJdGmM27YSeSIdJdOh4ZXVVY');
//
//        } else {
            $messageDto = (new MessageDto())
                ->setChatId($visitorSession->getChatId())
                ->setText($behaviorScenarioContent['message'])
            ;

            if(!empty($behaviorScenarioContent['replyMarkup'])){
                $messageDto->setReplyMarkup($behaviorScenarioContent['replyMarkup']);
            }

            $this->telegramService->sendMessage($messageDto, '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U'); // todo токен брать из настрек
//        }

        return true;
    }
}
