<?php

namespace App\Service\System\Resolver\Chains;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Service\System\Resolver\Dto\Contract;
use Exception;

class ChainsResolver
{
    public function __construct(
        private readonly ChainResolver $chainResolver,
    ) {
    }

    /**
     * @param array<CacheChainDto> $chains
     *
     * @throws Exception
     */
    public function resolve(Contract $contract, array $chains): array
    {
        foreach ($chains as $chain) {
            if ($chain->isFinished()) {
                continue;
            }

            $nextChain = next($chains) ?? null;

            $contract->setChain($chain);

            $this->chainResolver->resolve($contract, $nextChain?->getTarget());

            break;
        }

        return $chains;
    }
}
