<?php

namespace App\Controller\Admin\Project\Request;

use App\Entity\User\Enum\ProjectStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectUpdateRequest
{
    private string $name;

    #[Assert\Choice([ProjectStatusEnum::Active->value, ProjectStatusEnum::Frozen->value])]
    private string $status;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
