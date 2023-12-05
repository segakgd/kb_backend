<?php

namespace App\Dto\Admin\Project;

use Symfony\Component\Validator\Constraints as Assert;

class ProjectCreateDto
{
    private string $name;

    #[Assert\Choice(['booking', 'shop'])]
    private string $mode;

    #[Assert\Choice(['telegram', 'vk'])]
    private string $bot;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function getBot(): string
    {
        return $this->bot;
    }

    public function setBot(string $bot): void
    {
        $this->bot = $bot;
    }
}
