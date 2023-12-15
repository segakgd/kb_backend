<?php

namespace App\Controller\Admin\Lead\DTO\Response\Chanel;

class LeadChanelRespDto
{
    private string $name;

    private string $link;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }
}
