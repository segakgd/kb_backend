<?php

namespace App\Service\Integration\Telegram;

use App\Dto\Core\Telegram\Request\Invoice\InvoiceDto;
use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Dto\Core\Telegram\Request\Message\PhotoDto;
use App\Dto\Core\Telegram\Request\Webhook\WebhookDto;
use App\Dto\Core\Telegram\Response\GetWebhookInfoDto;
use App\Dto\Responsible\ResponsibleMessageDto;
use App\Service\HttpClient\HttpClient;
use App\Service\HttpClient\HttpClientInterface;
use App\Service\HttpClient\RequestMethodEnum;
use App\Service\HttpClient\Response\ResponseInterface;

readonly class TelegramService implements TelegramServiceInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    /**
     * @return GetWebhookInfoDto
     */
    public function getWebhookInfo(string $token): ResponseInterface
    {
        $request = HttpClient::buildRequest(
            method: RequestMethodEnum::Get,
            scenario: 'getWebhookInfo',
            token: $token,
            responseClassName: GetWebhookInfoDto::class
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

        $request = HttpClient::buildRequest(
            method: RequestMethodEnum::Post,
            scenario: 'sendPhoto',
            token: $token,
            data: $photoDto->getArray(),
        );

        $this->httpClient->request($request);
    }

    public function sendMessage(ResponsibleMessageDto $responsibleMessageDto, string $token, int $chatId): void
    {
        // todo БАГ! при отправке в режиме setParseMode = MarkdownV2, с сообщением в котором есть многоточие - случается 400я - телега не может парсить
        $message = $responsibleMessageDto->getMessage();
        $replyMarkup = $responsibleMessageDto->getKeyBoard();

        $messageDto = (new MessageDto())
            ->setChatId($chatId);

        $messageDto->setText($message);
        $messageDto->setReplyMarkup($replyMarkup);

        $request = HttpClient::buildRequest(
            method: RequestMethodEnum::Post,
            scenario: 'sendMessage',
            token: $token,
            data: $messageDto->getArray(),
        );

        $this->httpClient->request($request);
    }

    public function sendInvoice(InvoiceDto $invoiceDto, string $token): void
    {
        $request = HttpClient::buildRequest(
            method: RequestMethodEnum::Post,
            scenario: 'sendInvoice',
            token: $token,
            data: $invoiceDto->getArray(),
        );

        $this->httpClient->request($request);
    }

    public function setWebhook(WebhookDto $webhookDto, string $token): void
    {
        $request = HttpClient::buildRequest(
            method: RequestMethodEnum::Post,
            scenario: 'setWebhook',
            token: $token,
            data: $webhookDto->getArray(),
        );

        $this->httpClient->request($request);
    }
}
