<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Response\Fields;

class LeadContactsRespDto
{
    private ?LeadFieldRespDto $fullName = null;

    private ?LeadFieldRespDto $phone = null;

    private ?LeadFieldRespDto $mail = null;

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
}
