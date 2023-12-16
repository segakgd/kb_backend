<?php

namespace App\Controller\Admin\History\DTO\Response;

class HistoryErrorRespDto
{
    private string $code;

    private array $context;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function addContext(HistoryErrorRespDtoInterface $context): void
    {
        $this->context[] = $context;
    }
}
