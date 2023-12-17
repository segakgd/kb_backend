<?php

namespace App\Controller\Admin\History\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class HistoryReqDto
{
    private const AVAILABLE_PARAMS = [
        'type',
        'status',
        'dateTime',
        'sender',
        'recipient',
    ];

    #[Assert\Choice(self::AVAILABLE_PARAMS)]
    private ?string $filter = null;

    public function getFilter(): ?string
    {
        return $this->filter;
    }

    public function setFilter(?string $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
