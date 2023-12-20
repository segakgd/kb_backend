<?php

namespace App\Dto\Webhook\Telegram;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TelegramWebhookMessageDto
{
    #[SerializedName('message_id')]
    private $messageId;

    private $from;

    private TelegramWebhookChatDto $chat;

    private $date;

    private $text;

    private $entities;

    public function setMessageId($messageId): void
    {
        $this->messageId = $messageId;
    }

    public function setFrom($from): void
    {
        $this->from = $from;
    }

    public function getChat()
    {
        return $this->chat;
    }

    public function setChat($chat): void
    {
        $this->chat = $chat;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function setEntities($entities): void
    {
        $this->entities = $entities;
    }

    public function isCommand(): bool
    {
        $entities = $this->entities;

        return isset($entities[0]['type']) && $entities[0]['type'] === 'bot_command';
    }

    public function isMessage(): bool
    {
        return $this->text !== null;
    }
}
