<?php

namespace App\Service\Integration\Telegram;

use App\Dto\Core\Telegram\Request\Invoice\InvoiceDto;
use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Dto\Core\Telegram\Request\Message\PhotoDto;
use App\Dto\Core\Telegram\Request\Webhook\WebhookDto;
use App\Dto\Core\Telegram\Response\GetWebhookInfoDto;
use App\Service\System\Handler\Contract;
use App\Service\System\HttpClient\HttpClient;
use App\Service\System\HttpClient\HttpClientInterface;
use App\Service\System\HttpClient\Request\Request;
use App\Service\System\HttpClient\Response\ResponseInterface;

class TelegramService implements TelegramServiceInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
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

    public function sendPhoto(Contract $preMessageDto, string $token, int $chatId): void
    {
        $message = $preMessageDto->getMessage();
        $replyMarkup = $preMessageDto->getKeyBoard();
        $photo = $preMessageDto->getPhoto();

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

    public function sendMessage(Contract $preMessageDto, string $token, int $chatId): void
    {
        $message = $preMessageDto->getMessage();
        $replyMarkup = $preMessageDto->getKeyBoard();

        $messageDto = (new MessageDto())
            ->setChatId($chatId);

        $messageDto->setText($message);
        $messageDto->setReplyMarkup($replyMarkup);
        $messageDto->setParseMode('MarkdownV2');

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
