<?php

namespace App\Controller\Admin\History\DTO\Request;

class HistoryReqDto
{
    private string $filter; // type, status, dateTime, sender, recipient

    public function getFilter(): string
    {
        return $this->filter;
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }
}
