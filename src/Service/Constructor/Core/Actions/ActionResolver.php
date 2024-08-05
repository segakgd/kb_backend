<?php

namespace App\Service\Constructor\Core\Actions;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Service\Constructor\ActionProvider;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

readonly class ActionResolver
{
    public function __construct(
        private ActionProvider $actionProvider,
    ) {}

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

            $chainInstance = $this->getChainInstance($responsible);

            $chainInstance->execute(
                responsible: $responsible,
                nextChain: $this->getNextChain($nextChain->getTarget())
            );

            break;
        }

        return $chains;
    }

    /**
     * @throws Exception
     */
    private function getChainInstance(Responsible $responsible): AbstractAction
    {
        $target = $responsible->getChain()->getTarget();

        return $this->actionProvider->getByTarget($target);
    }

    /**
     * @throws Exception
     */
    private function getNextChain(?string $targetNext): ?AbstractAction
    {
        if (is_null($targetNext)) {
            return null;
        }

        return $this->actionProvider->getByTarget($targetNext);
    }
}
