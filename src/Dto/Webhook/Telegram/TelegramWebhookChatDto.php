<?php

namespace App\Dto\Webhook\Telegram;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TelegramWebhookChatDto
{
    private $id;

    #[SerializedName('first_name')]
    private $firstName;

    private $username;

    private $type;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }
}
