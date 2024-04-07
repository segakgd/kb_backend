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
        foreach ($chains as $key => $chain) {
            $nextChain = $chains[1 + $key] ?? null;

            $contract->setChain($chain);

            $this->chainResolver->resolve($contract, $nextChain?->getTarget());

            if ($contract->getJump() !== null) {
                break;
            }

            if ($chain->isFinished()) {
                unset($chains[$key]);
            }

            break;
        }

        return $chains;
    }
}
