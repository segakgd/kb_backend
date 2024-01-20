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

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function addContext(array $context): self
    {
        $this->context[] = $context;

        return $this;
    }
}
