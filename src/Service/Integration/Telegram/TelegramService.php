<?php

namespace App\Service\Integration\Telegram;

use App\Dto\Responsible\ResponsibleMessageDto;
use App\Dto\Core\Telegram\Request\Invoice\InvoiceDto;
use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Dto\Core\Telegram\Request\Message\PhotoDto;
use App\Dto\Core\Telegram\Request\Webhook\WebhookDto;
use App\Dto\Core\Telegram\Response\GetWebhookInfoDto;
use App\Service\System\HttpClient\HttpClient;
use App\Service\System\HttpClient\HttpClientInterface;
use App\Service\System\HttpClient\Request\Request;
use App\Service\System\HttpClient\Response\ResponseInterface;

readonly class TelegramService implements TelegramServiceInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    // todo если ожидать тут бота, то многие проблемы решит это, т.к у бота есть и id проекта и токены

    /**
     * @return GetWebhookInfoDto
     */
    public function getWebhookInfo(string $token): ResponseInterface
    {
        $request = $this->buildRequest(
            HttpClient::METHOD_GET,
            'getWebhookInfo',
            $token,
            null,
            GetWebhookInfoDto::class
        );

        return $this->httpClient->request($request);
    }

    public function sendPhoto(ResponsibleMessageDto $responsibleMessageDto, string $token, int $chatId): void
    {
        $message = $responsibleMessageDto->getMessage();
        $replyMarkup = $responsibleMessageDto->getKeyBoard();
        $photo = $responsibleMessageDto->getPhoto();

        $photoDto = (new PhotoDto())
            ->setChatId($chatId);

        $photoDto->setPhoto($photo);
        $photoDto->setCaption($message);
        $photoDto->setReplyMarkup($replyMarkup);
        $photoDto->setParseMode('MarkdownV2');

        $request = $this->buildRequest(
            HttpClient::METHOD_POST,
            'sendPhoto',
            $token,
            $photoDto->getArray(),
        );

        $this->httpClient->request($request);
    }

    public function sendMessage(ResponsibleMessageDto $responsibleMessageDto, string $token, int $chatId): void
    {
        // БАГ! при отправке в реживе setParseMode = MarkdownV2, с сообщением в котором есть многоточие - случается 400я - нтелега не может распарсить

        $message = $responsibleMessageDto->getMessage();
        $replyMarkup = $responsibleMessageDto->getKeyBoard();

        $messageDto = (new MessageDto())
            ->setChatId($chatId);

        $messageDto->setText($message);
        $messageDto->setReplyMarkup($replyMarkup);

        $request = $this->buildRequest(
            HttpClient::METHOD_POST,
            'sendMessage',
            $token,
            $messageDto->getArray(),
        );

        $this->httpClient->request($request);
    }

    public function sendInvoice(InvoiceDto $invoiceDto, string $token): void
    {
        $request = $this->buildRequest(
            HttpClient::METHOD_POST,
            'sendInvoice',
            $token,
            $invoiceDto->getArray(),
        );

        $this->httpClient->request($request);
    }

    public function setWebhook(WebhookDto $webhookDto, string $token): void
    {
        $request = $this->buildRequest(
            HttpClient::METHOD_POST,
            'setWebhook',
            $token,
            $webhookDto->getArray(),
        );

        $this->httpClient->request($request);
    }

    private function buildRequest(
        string $method,
        string $scenario,
        string $token,
        ?array $data = null,
        ?string $responseClassName = null,
    ): Request {
        return (new Request())
            ->setMethod($method)
            ->setScenario($scenario)
            ->setToken($token)
            ->setData($data)
            ->setResponseClassName($responseClassName);
    }
}
