<?php

namespace App\Service\Admin\Bot;

use App\Repository\User\BotRepository;

class BotService implements BotServiceInterface
{
    public function __construct(
        private readonly BotRepository $botRepository,
    ) {
    }

    public function add(): void
    {
        $this->botRepository->saveAndFlush();
    }
}
