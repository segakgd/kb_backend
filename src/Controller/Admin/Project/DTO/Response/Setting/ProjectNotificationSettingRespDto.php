<?php

namespace App\Controller\Admin\Project\DTO\Response\Setting;

class ProjectNotificationSettingRespDto
{
    private ?bool $post;

    private ?bool $telegram;

    private ?bool $sms;

    public function getPost(): ?bool
    {
        return $this->post;
    }

    public function setPost(?bool $post): void
    {
        $this->post = $post;
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
