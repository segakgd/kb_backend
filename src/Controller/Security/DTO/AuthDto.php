<?php

namespace App\Controller\Security\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AuthDto
{
    #[Assert\NotBlank(message: 'Username should not be blank.')]
    #[Assert\Email(message: 'The username must be a vaid email address.')]
    private string $username;

    #[Assert\NotBlank(message: 'Password should not be blank.')]
    #[Assert\Length(min: 6, minMessage: 'Password should be at least {{ limit }} characters long.')]
    private string $password;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
