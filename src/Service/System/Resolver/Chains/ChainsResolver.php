<?php

namespace App\Service\System\Resolver\Chains;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Service\System\Resolver\Dto\Responsible;
use Exception;

readonly class ChainsResolver
{
    public function __construct(
        private ChainResolver $chainResolver,
    ) {
    }

    /**
     * @param array<CacheChainDto> $chains
     *
     * @throws Exception
     */
    public function resolve(Responsible $responsible, array $chains): array
    {
        $count = count($chains);
        $now = 1;
        $nextChain = null;

        foreach ($chains as $key => $chain) {
            if ($chain->isFinished()) {
                continue;
            }

            if ($now < $count) {
                $nextKey = $key + 1;
                $nextChain = $chains[$nextKey] ?? null;
            }

            $responsible->setChain($chain);

            $this->chainResolver->resolve($responsible, $nextChain?->getTarget());

            break;
        }

        return $chains;
    }
}
