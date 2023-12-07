<?php

namespace App\Dto\Admin\Lead\Response;

class AllLeadContactsRespDto
{
    private AllLeadContactRespDto $phone;

    private AllLeadContactRespDto $mail;

    private AllLeadContactRespDto $telegram;

    public function getPhone(): AllLeadContactRespDto
    {
        return $this->phone;
    }

    public function setPhone(AllLeadContactRespDto $phone): void
    {
        $this->phone = $phone;
    }

    public function getMail(): AllLeadContactRespDto
    {
        return $this->mail;
    }

    public function setMail(AllLeadContactRespDto $mail): void
    {
        $this->mail = $mail;
    }

    public function getTelegram(): AllLeadContactRespDto
    {
        return $this->telegram;
    }

    public function setTelegram(AllLeadContactRespDto $telegram): void
    {
        $this->telegram = $telegram;
    }
}
