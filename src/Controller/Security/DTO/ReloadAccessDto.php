<?php

namespace App\Controller\Security\DTO;

class ReloadAccessDto
{
    private string $refreshToken;

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
