<?php

namespace App\Dto\Admin\Lead\All;

class LeadContactsRespDto
{
    private LeadContactRespDto $phone;

    private LeadContactRespDto $mail;

    private LeadContactRespDto $telegram;

    public function getPhone(): LeadContactRespDto
    {
        return $this->phone;
    }

    public function setPhone(LeadContactRespDto $phone): void
    {
        $this->phone = $phone;
    }

    public function getMail(): LeadContactRespDto
    {
        return $this->mail;
    }

    public function setMail(LeadContactRespDto $mail): void
    {
        $this->mail = $mail;
    }

    public function getTelegram(): LeadContactRespDto
    {
        return $this->telegram;
    }

    public function setTelegram(LeadContactRespDto $telegram): void
    {
        $this->telegram = $telegram;
    }
}
