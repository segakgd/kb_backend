<?php

namespace App\Dto\deprecated\Project;

use App\Dto\deprecated\Security\UserDto;

class ProjectDto
{
    private string $name;

    private array $users;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function addUsers(UserDto $user): self
    {
        $this->users[] = $user;

        return $this;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;

        return $this;
    }
}
