<?php

namespace App\Exception\History;

use Exception;

class BaseException extends Exception implements BaseExceptionInterface
{
    public function __construct(
        private readonly int $projectId,
        private readonly string $messages,
    ) {
        parent::__construct();
    }

    public function getMessages(): string
    {
        return $this->messages;
    }

    public function getProject(): int
    {
        return $this->projectId;
    }
}