<?php

namespace App\Controller\Admin\Lead\DTO\Response;

class AllLeadContactsRespDto
{
    private AllLeadContactRespDto $phone;

    private AllLeadContactRespDto $mail;

    private AllLeadContactRespDto $telegram;

    public function getPhone(): AllLeadContactRespDto
    {
        return $this->phone;
    }

    public function setPhone(AllLeadContactRespDto $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): AllLeadContactRespDto
    {
        return $this->mail;
    }

    public function setMail(AllLeadContactRespDto $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTelegram(): AllLeadContactRespDto
    {
        return $this->telegram;
    }

    public function setTelegram(AllLeadContactRespDto $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }
}
