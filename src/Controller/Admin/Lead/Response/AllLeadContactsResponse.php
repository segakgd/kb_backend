<?php

namespace App\Controller\Admin\Lead\Response;

class AllLeadContactsResponse
{
    private ?AllLeadContactResponse $phone;

    private ?AllLeadContactResponse $mail;

    private ?AllLeadContactResponse $telegram;

    public function getPhone(): ?AllLeadContactResponse
    {
        return $this->phone;
    }

    public function setPhone(?AllLeadContactResponse $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?AllLeadContactResponse
    {
        return $this->mail;
    }

    public function setMail(?AllLeadContactResponse $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTelegram(): ?AllLeadContactResponse
    {
        return $this->telegram;
    }

    public function setTelegram(?AllLeadContactResponse $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }
}
