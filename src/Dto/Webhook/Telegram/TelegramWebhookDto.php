<?php

namespace App\Dto\Webhook\Telegram;

use Exception;
use Symfony\Component\Serializer\Annotation\SerializedName;

class TelegramWebhookDto
{
    #[SerializedName('update_id')]
    private $updateId;

    private TelegramWebhookMessageDto $message;

    public function setUpdateId($updateId): void
    {
        $this->updateId = $updateId;
    }

    public function setMessage(TelegramWebhookMessageDto $message): void
    {
        $this->message = $message;
    }

    /**
     * @throws Exception
     */
    public function getWebhookType(): string
    {
        if ($this->message->isCommand()){
            return 'command';
        } elseif ($this->message->isMessage()){
            return 'message';
        }

        throw new Exception();
    }

    public function getWebhookContent(): string
    {
        return $this->message->getText();
    }

    public function getWebhookChatId(): int // мб targetId ??
    {
        return $this->message->getChat()->getId();
    }

    public function getVisitorName(): string
    {
        return $this->message->getChat()->getFirstName();
    }
}
