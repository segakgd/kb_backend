<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\DTO\Request\Field;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LeadContactsReqDto
{
    #[Assert\Valid]
    private ?LeadFieldReqDto $phone = null;

    #[Assert\Valid]
    private ?LeadFieldReqDto $email = null;

    #[Assert\Valid]
    private ?LeadFieldReqDto $firstName = null;

    #[Assert\Valid]
    private ?LeadFieldReqDto $lastName = null;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void // todo -> Вообще тут бы хорошо подумть над валидацией филдов).
    {
        if ($this->phone === null && $this->email === null && $this->firstName === null && $this->lastName === null) {
            $context
                ->buildViolation('No field specified')
                ->addViolation();
        }


        if ($this->email !== null && !filter_var($this->email->getValue(), FILTER_VALIDATE_EMAIL)) {
            $context
                ->buildViolation('Mail field invalid')
                ->atPath('mail.type')
                ->addViolation();
        }
    }

    public function getPhone(): ?LeadFieldReqDto
    {
        return $this->phone;
    }

    public function setPhone(?LeadFieldReqDto $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?LeadFieldReqDto
    {
        return $this->email;
    }

    public function setEmail(?LeadFieldReqDto $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?LeadFieldReqDto
    {
        return $this->firstName;
    }

    public function setFirstName(?LeadFieldReqDto $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?LeadFieldReqDto
    {
        return $this->lastName;
    }

    public function setLastName(?LeadFieldReqDto $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
