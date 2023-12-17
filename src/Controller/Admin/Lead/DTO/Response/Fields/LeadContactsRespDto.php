<?php

namespace App\Controller\Admin\Lead\DTO\Response\Fields;

class LeadContactsRespDto
{
    private ?LeadFieldRespDto $fullName = null;

    private ?LeadFieldRespDto $phone = null;

    private ?LeadFieldRespDto $mail = null;

    private ?LeadFieldRespDto $telegram = null;

    public function getFullName(): ?LeadFieldRespDto
    {
        return $this->fullName;
    }

    public function setFullName(LeadFieldRespDto $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhone(): ?LeadFieldRespDto
    {
        return $this->phone;
    }

    public function setPhone(LeadFieldRespDto $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?LeadFieldRespDto
    {
        return $this->mail;
    }

    public function setMail(LeadFieldRespDto $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTelegram(): ?LeadFieldRespDto
    {
        return $this->telegram;
    }

    public function setTelegram(LeadFieldRespDto $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }
}
