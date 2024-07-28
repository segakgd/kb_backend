<?php

namespace App\Dto\Webhook\Telegram;

use Exception;
use Symfony\Component\Serializer\Annotation\SerializedName;

class TelegramWebhookDto
{
    #[SerializedName('update_id')]
    private int $updateId;

    private TelegramWebhookMessageDto $message;

    public function getUpdateId(): int
    {
        return $this->updateId;
    }

    public function setUpdateId(int $updateId): void
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
        if ($this->message->isCommand()) {
            return 'command';
        }

        if ($this->message->isMessage()) {
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
