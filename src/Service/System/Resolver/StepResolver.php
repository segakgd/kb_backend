<?php

namespace App\Service\System\Resolver;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Common\CacheService;
use App\Service\System\Resolver\Dto\Contract;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

class StepResolver
{
    public function __construct(
        private readonly ChainResolver $chainResolver,
        private readonly ScenarioResolver $scenarioResolver,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function resolve(array $steps, Contract $contract, CacheDto $cacheDto): void
    {
        try {
            foreach ($steps as $step) {
                $chains = $step['chain'] ?? [];

                if (!empty($chains)) {
                    $this->resolveChain($contract, $cacheDto, $step);
                } else {
                    $this->resolveScenario($contract, $cacheDto, $step);
                }

                // todo вот тут ещё нужно повозиться, как по мне...
                // todo проблема с сообщениями...
                break;
            }
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @throws Exception
     */
    private function resolveChain(Contract $contract, CacheDto $cacheDto, array $step): void
    {
        if ($cacheDto->getEvent()->isEmptyChains()) {
            CacheService::enrichStepCache($step['chain'], $cacheDto);
        }

        $chains = $this->chainResolver->resolve($contract, $cacheDto->getEvent()->getChains());

        $cacheDto->getEvent()->setChains($chains);
    }

    private function resolveScenario(Contract $contract, CacheDto $cacheDto, array $step): void
    {
        $this->scenarioResolver->resolve($contract, $step);

        $cacheDto->getEvent()->setFinished(true);
    }

    private function handleException(Throwable $exception): void
    {
        $this->logger->error(
            message: $exception->getMessage(),
            context: [
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        );
    }
}
