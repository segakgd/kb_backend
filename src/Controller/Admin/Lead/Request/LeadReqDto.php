<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Request;

use App\Controller\Admin\Lead\Request\Field\LeadContactsReqDto;
use App\Controller\Admin\Lead\Request\Field\LeadFieldReqDto;
use App\Controller\Admin\Lead\Request\Order\OrderReqDto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LeadReqDto
{
    #[Assert\Valid]
    private ?LeadContactsReqDto $contacts = null;

    #[Assert\Valid]
    private array $fields = [];

    #[Assert\Valid]
    private ?OrderReqDto $order = null;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (empty($this->fields) && null === $this->contacts && $this->order === null) {
            $context
                ->buildViolation('Lead has no nested parameters')
                ->addViolation();
        }
    }

    public function getContacts(): ?LeadContactsReqDto
    {
        return $this->contacts;
    }

    public function setContacts(?LeadContactsReqDto $contact): self
    {
        $this->contacts = $contact;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addFields(LeadFieldReqDto $fields): self
    {
        $this->fields[] = $fields;

        return $this;
    }

    /**
     * @param LeadFieldReqDto[] $fields
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function getOrder(): ?OrderReqDto
    {
        return $this->order;
    }

    public function setOrder(?OrderReqDto $order): self
    {
        $this->order = $order;

        return $this;
    }
}
