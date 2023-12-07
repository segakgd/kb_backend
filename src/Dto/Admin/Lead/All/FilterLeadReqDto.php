<?php

namespace App\Dto\Admin\Lead\All;

use Symfony\Component\Validator\Constraints as Assert;

class FilterLeadReqDto
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

    public function setScript(string $script): void
    {
        $this->script = $script;
    }

    public function getMessenger(): string
    {
        return $this->messenger;
    }

    public function setMessenger(string $messenger): void
    {
        $this->messenger = $messenger;
    }
}
