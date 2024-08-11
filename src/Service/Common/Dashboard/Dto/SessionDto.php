<?php

namespace App\Service\Common\Dashboard\Dto;

class SessionDto
{
    private int $id;
    private string $sessionName;
    private string $sessionChannel;
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

    public function getSessionChannel(): string
    {
        return $this->sessionChannel;
    }

    public function setSessionChannel(string $sessionChannel): static
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