<?php

namespace App\Controller\Admin\Lead\DTO\Response\Fields;

class LeadContactsRespDto
{
    private LeadFieldRespDto $fullName;

    private LeadFieldRespDto $phone;

    private LeadFieldRespDto $mail;

    private LeadFieldRespDto $telegram;

    public function getFullName(): LeadFieldRespDto
    {
        return $this->fullName;
    }

    public function setFullName(LeadFieldRespDto $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getPhone(): LeadFieldRespDto
    {
        return $this->phone;
    }

    public function setPhone(LeadFieldRespDto $phone): void
    {
        $this->phone = $phone;
    }

    public function getMail(): LeadFieldRespDto
    {
        return $this->mail;
    }

    public function setMail(LeadFieldRespDto $mail): void
    {
        $this->mail = $mail;
    }

    public function getTelegram(): LeadFieldRespDto
    {
        return $this->telegram;
    }

    public function setTelegram(LeadFieldRespDto $telegram): void
    {
        $this->telegram = $telegram;
    }
}
