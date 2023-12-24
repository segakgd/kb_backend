<?php

namespace App\Dto\Project;

use App\Dto\Security\UserDto;

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
