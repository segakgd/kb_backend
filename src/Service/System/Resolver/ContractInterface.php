<?php

namespace App\Service\System\Resolver;

use App\Dto\Contract\ContractMessageDto;
use App\Service\System\Contract;

interface ContractInterface
{
    public function getData(): array;

    public function setData(array $data);

    public function getMessages(): array;

    public function setMessages(array $messages): self;

    public function addMessage(ContractMessageDto $message): self;

    public function getStatus(): string;

    public function isStatusDone(): bool;

    public function setStatus(string $status): Contract;

    public function getGoto(): ?string;

    public function isNotGoto(): bool;

    public function setGoto(?string $goto): self;
}
