<?php

namespace App\Service\System\Resolver\Contracts;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Service\System\Resolver\Chains\ChainsResolver;
use App\Service\System\Resolver\Dto\Responsible;
use App\Service\System\Resolver\Scenario\ScenarioResolver;
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
        $contracts = $responsible->getCacheDto()->getEvent()->getContracts();

        try {
            foreach ($contracts as $cacheContractDto) {
                if (!$cacheContractDto->isFinished()) {
                    $this->resolveContract($responsible, $cacheContractDto);

                    $unfinishedChains = array_filter($cacheContractDto->getChains(), fn (CacheChainDto $chain) => !$chain->isFinished());

                    if (empty($unfinishedChains)) {
                        $cacheContractDto->setFinished(true);
                    }

                    break;
                }
            }

            $unfinishedContracts = array_filter($contracts, fn (CacheContractDto $cacheContractDto) => !$cacheContractDto->isFinished());

            if (empty($unfinishedContracts)) {
                $responsible->setContractsStatus(true);
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
            $this->handleChain($responsible, $cacheContractDto);
        } else {
            $this->handleScenario($responsible, $cacheContractDto);
            $responsible->setContractsStatus(true);
        }
    }

    /**
     * @throws Throwable
     */
    private function handleChain(Responsible $responsible, CacheContractDto $cacheContractDto): void
    {
        $this->chainsResolver->resolve($responsible, $cacheContractDto->getChains());
    }

    private function handleScenario(Responsible $responsible, CacheContractDto $cacheContractDto): void
    {
        $this->scenarioResolver->resolve($responsible, $cacheContractDto);
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
