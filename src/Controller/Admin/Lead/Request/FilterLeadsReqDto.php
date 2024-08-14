<?php

namespace App\Controller\Admin\Lead\Request;

use Symfony\Component\Validator\Constraints as Assert;

class FilterLeadsReqDto
{
    private const AVAILABLE_MESSENGER = [
        'telegram',
        'vk',
    ];

    private string $script = 'all';

    #[Assert\Choice(self::AVAILABLE_MESSENGER)]
    private string $messenger;

    public function getScript(): string
    {
        return $this->script;
    }

    public function setScript(string $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function getMessenger(): string
    {
        return $this->messenger;
    }

    public function setMessenger(string $messenger): self
    {
        $this->messenger = $messenger;

        return $this;
    }
}
