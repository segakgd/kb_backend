<?php

namespace App\Exception\History;

interface BaseExceptionInterface
{
    public function getProject(): int;

    public function getMessages(): string;
}