<?php

namespace App\Service\System\Resolver\Steps;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheStepDto;
use App\Service\System\Resolver\Chains\ChainResolver;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\System\Resolver\Scenario\ScenarioResolver;
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

    /**
     * @throws Throwable
     */
    public function resolve(Contract $contract, CacheDto $cacheDto): void
    {
        $steps = $cacheDto->getEvent()->getSteps();

        try {
            foreach ($steps as $step) {
                $this->resolveStep($contract, $step);

                break;
            }
        } catch (Throwable $exception) {
            $this->handleException($exception);

            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    private function resolveStep(Contract $contract, CacheStepDto $step): void
    {
        if ($step->hasChain()) {
            $this->handleChain($contract, $step);
        } else {
            $this->handleScenario($contract, $step);
            $contract->setStepsStatus(true);
        }
    }

    /**
     * @throws Exception
     */
    private function handleChain(Contract $contract, CacheStepDto $step): void
    {
        $this->chainResolver->resolve($contract, $step->getChains());
    }

    private function handleScenario(Contract $contract, CacheStepDto $step): void
    {
        $this->scenarioResolver->resolve($contract, $step);
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
