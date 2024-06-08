<?php

namespace App\Service\System\Core\Contract;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Service\System\Core\Chains\ChainsResolver;
use App\Service\System\Core\Dto\Responsible;
use App\Service\System\Core\Scenario\ScenarioResolver;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class ContractResolver
{
    public function __construct(
        private ChainsResolver   $chainsResolver,
        private ScenarioResolver $scenarioResolver,
        private LoggerInterface  $logger,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function resolve(Responsible $responsible): void
    {
        try {
            $cacheContract = $responsible->getCacheDto()->getEvent()->getContract();

            $this->resolveContract($responsible, $cacheContract);

            $unfinishedChains = array_filter($cacheContract->getChains(), fn (CacheChainDto $chain) => !$chain->isFinished());

            if (empty($unfinishedChains)) {
                $cacheContract->setFinished(true);
                $responsible->setContractStatus(true);
            }

        } catch (Throwable $exception) {
            $this->handleException($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    private function resolveContract(Responsible $responsible, CacheContractDto $cacheContractDto): void
    {
        if ($cacheContractDto->hasChain()) {
            $this->chainsResolver->resolve($responsible, $cacheContractDto->getChains());
        } else {
            $this->scenarioResolver->resolve($responsible, $cacheContractDto);

            $responsible->setContractStatus(true);
        }
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
