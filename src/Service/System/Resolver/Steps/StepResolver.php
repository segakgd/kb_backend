<?php

namespace App\Service\System\Resolver\Steps;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheStepDto;
use App\Service\System\Resolver\Chains\ChainsResolver;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\System\Resolver\Scenario\ScenarioResolver;
use Psr\Log\LoggerInterface;
use Throwable;

class StepResolver
{
    public function __construct(
        private readonly ChainsResolver $chainsResolver,
        private readonly ScenarioResolver $scenarioResolver,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function resolve(Contract $contract): void
    {
        $steps = $contract->getCacheDto()->getEvent()->getSteps();

        try {
            foreach ($steps as $step) {
                if (!$step->isFinished()) {
                    $this->resolveStep($contract, $step);

                    $unfinishedChains = array_filter($step->getChains(), fn (CacheChainDto $chain) => !$chain->isFinished());

                    if (empty($unfinishedChains)) {
                        $step->setFinished(true);
                    }

                    break;
                }
            }

            $unfinishedSteps = array_filter($steps, fn (CacheStepDto $step) => !$step->isFinished());

            if (empty($unfinishedSteps)) {
                $contract->setStepsStatus(true);
            }
        } catch (Throwable $exception) {
            $this->handleException($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
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
     * @throws Throwable
     */
    private function handleChain(Contract $contract, CacheStepDto $step): void
    {
        $this->chainsResolver->resolve($contract, $step->getChains());
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
