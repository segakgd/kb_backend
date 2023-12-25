<?php

namespace App\Controller\Admin\Project\DTO\Request\Setting\Notification;

class ProjectNotificationSettingReqDto
{
    private ?bool $mail;

    private ?bool $telegram;

    private ?bool $sms;

    public function getMail(): ?bool
    {
        return $this->mail;
    }

    public function setMail(?bool $mail): void
    {
        $this->mail = $mail;
    }

    public function getTelegram(): ?bool
    {
        return $this->telegram;
    }

    public function setTelegram(?bool $telegram): void
    {
        $this->telegram = $telegram;
    }

    public function getSms(): ?bool
    {
        return $this->sms;
    }

    public function setSms(?bool $sms): void
    {
        $this->sms = $sms;
    }
}
