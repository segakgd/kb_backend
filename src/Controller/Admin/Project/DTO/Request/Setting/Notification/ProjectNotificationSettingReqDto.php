<?php

namespace App\Controller\Admin\Project\DTO\Request\Setting\Notification;

class ProjectNotificationSettingReqDto
{
    private ?bool $system = null;

    private ?bool $mail = null;

    private ?bool $telegram = null;

    private ?bool $sms = null;

    public function getSystem(): ?bool
    {
        return $this->system;
    }

    public function setSystem(?bool $system): void
    {
        $this->system = $system;
    }

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
