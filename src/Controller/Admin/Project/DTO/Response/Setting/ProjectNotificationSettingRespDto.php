<?php

namespace App\Controller\Admin\Project\DTO\Response\Setting;

class ProjectNotificationSettingRespDto
{
    private ?bool $system = true;

    private ?bool $mail;

    private ?bool $telegram;

    private ?bool $sms;

    public function getSystem(): ?bool
    {
        return $this->system;
    }

    public function setSystem(?bool $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getMail(): ?bool
    {
        return $this->mail;
    }

    public function setMail(?bool $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTelegram(): ?bool
    {
        return $this->telegram;
    }

    public function setTelegram(?bool $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function getSms(): ?bool
    {
        return $this->sms;
    }

    public function setSms(?bool $sms): self
    {
        $this->sms = $sms;

        return $this;
    }
}
