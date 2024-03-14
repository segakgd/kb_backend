<?php

namespace App\Service\System\Handler\Chain\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Contract;

abstract class AbstractChain
{
    public function chain(Contract $contract, CacheDto $cacheDto): bool
    {
        if ($this->validateCondition($cacheDto->getContent())) {
            return $this->success($contract, $cacheDto);
        }

        return $this->fall($contract, $cacheDto);
    }

    abstract public function success(Contract $contract, CacheDto $cacheDto): bool;

    abstract public function fall(Contract $contract, CacheDto $cacheDto): bool;

    abstract public function validateCondition(string $content): bool;
}
