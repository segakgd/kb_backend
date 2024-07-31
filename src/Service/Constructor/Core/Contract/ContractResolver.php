<?php

namespace App\Service\Constructor\Core\Contract;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Enum\VisitorEventStatusEnum;
use App\Service\Constructor\Core\Chains\ActionResolver;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\Constructor\Core\Scenario\ScenarioResolver;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class ContractResolver
{
    public function __construct(
        private ActionResolver   $chainsResolver,
        private ScenarioResolver $scenarioResolver,
        private LoggerInterface  $logger,
    ) {}

    /**
     * @throws Throwable
     */
    public function resolve(Responsible $responsible): void
    {
        try {
            $cacheContract = $responsible->getEvent()->getContract();

            $this->resolveContract($responsible, $cacheContract);

            $unfinishedChains = array_filter($cacheContract->getChains(), fn (CacheChainDto $chain) => !$chain->isFinished());

            if (empty($unfinishedChains)) {
                $cacheContract->setFinished(true);
                $responsible->setStatus(VisitorEventStatusEnum::Done);
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

            $responsible->setStatus(VisitorEventStatusEnum::Done);
        }
    }

    private function handleException(Throwable $exception): void
    {
        $this->logger->error(
            message: $exception->getMessage(),
            context: [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]
        );
    }
}
