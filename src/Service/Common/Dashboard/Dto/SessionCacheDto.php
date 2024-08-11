<?php

namespace App\Service\Common\Dashboard\Dto;

class SessionCacheDto
{
    private ?string $content;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }
}