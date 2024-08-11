<?php

namespace App\Service\Common\Dashboard\Dto;

use App\Enum\Constructor\ChannelEnum;

class SessionDto
{
    private int $id;
    private string $sessionName;
    private ChannelEnum $sessionChannel;
    private SessionCacheDto $cache;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSessionName(): string
    {
        return $this->sessionName;
    }

    public function setSessionName(string $sessionName): static
    {
        $this->sessionName = $sessionName;

        return $this;
    }

    public function getSessionChannel(): ChannelEnum
    {
        return $this->sessionChannel;
    }

    public function setSessionChannel(ChannelEnum $sessionChannel): static
    {
        $this->sessionChannel = $sessionChannel;

        return $this;
    }

    public function getCache(): SessionCacheDto
    {
        return $this->cache;
    }

    public function setCache(SessionCacheDto $cache): static
    {
        $this->cache = $cache;

        return $this;
    }
}