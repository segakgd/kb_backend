<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Dto\Core\Telegram\Request\Message\PhotoDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Handler\ChainHandler;
use App\Service\System\Handler\PreMessageDto;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MessageHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioRepository $scenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly ChainHandler $chainHandler,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $visitorEvent): bool
    {
        $token = '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U';

        $scenario = $this->scenarioRepository->findOneBy(
            [
                'UUID' => $visitorEvent->getScenarioUUID(),
            ]
        );

        if (!$scenario) {
            throw new Exception('Не существует сценария');
        }

        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());

        $cache = $visitorSession->getCache();

        $status = $cache['event']['status'] ?? null;
        $content = $cache['content'];

        $preMessageDto = (new PreMessageDto())
            ->setMessage('Дефолтное сообщение...')
        ;

        if ($status === 'process') {
            $preMessageDto = $this->chain($preMessageDto, $cache, $content);

            $visitorSession->setCache($cache);
        } else {
            $preMessageDto = $this->scenario($preMessageDto, $scenario);
        }

        if ($preMessageDto->getPhoto()) {
            $this->sendPhoto($preMessageDto, $visitorSession, $token);
        } else {
            $this->sendMessage($preMessageDto, $visitorSession, $token);
        }

        $this->entityManager->persist($scenario);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        return true;
    }

    private function sendPhoto(PreMessageDto $preMessageDto, VisitorSession $visitorSession, string $token): void
    {
        $message = $preMessageDto->getMessage();
        $replyMarkup = $preMessageDto->getKeyBoard();
        $photo = $preMessageDto->getPhoto();

        $photoDto = (new PhotoDto())
            ->setChatId($visitorSession->getChatId());

        $photoDto->setPhoto($photo);
        $photoDto->setCaption($message);
        $photoDto->setReplyMarkup($replyMarkup);

        $this->telegramService->sendPhoto(
            $photoDto,
            $token
        );
    }

    private function sendMessage(PreMessageDto $preMessageDto, VisitorSession $visitorSession, string $token): void
    {
        $message = $preMessageDto->getMessage();
        $replyMarkup = $preMessageDto->getKeyBoard();

        $messageDto = (new MessageDto())
            ->setChatId($visitorSession->getChatId());

        $messageDto->setText($message);
        $messageDto->setReplyMarkup($replyMarkup);

        $this->telegramService->sendMessage(
            $messageDto,
            $token
        );
    }

    private function chain(PreMessageDto $preMessageDto, array &$cache, string $content): PreMessageDto
    {
        $chains = $cache['event']['chains'];

        // todo подумай в рамках ооп, создай сущность которая будех зранить значения нунешнего шага и всё такое...

        foreach ($chains as $key => $chain) {
            if ($chain['finished'] === false) {
                $isHandle = $this->chainHandler->handleByType($chain['target'], $preMessageDto, $content);

                if (count($chains) === ($key + 1)) {
                    $cache['event']['status'] = 'finished';
                }

                if ($isHandle) {
                    $chains[$key]['finished'] = true;
                }

                break;
            }
        }

        $cache['event']['chains'] = $chains;


        return $preMessageDto;
    }

    private function scenario(PreMessageDto $preMessageDto, Scenario $scenario): PreMessageDto
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
