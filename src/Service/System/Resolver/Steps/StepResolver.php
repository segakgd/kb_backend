<?php

namespace App\Service\System\Resolver\Steps;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheStepDto;
use App\Service\System\Resolver\Chains\ChainsResolver;
use App\Service\System\Resolver\Dto\Responsible;
use App\Service\System\Resolver\Scenario\ScenarioResolver;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class StepResolver
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
        $steps = $responsible->getCacheDto()->getEvent()->getSteps();

        try {
            foreach ($steps as $step) {
                if (!$step->isFinished()) {
                    $this->resolveStep($responsible, $step);

                    $unfinishedChains = array_filter($step->getChains(), fn (CacheChainDto $chain) => !$chain->isFinished());

                    if (empty($unfinishedChains)) {
                        $step->setFinished(true);
                    }

                    break;
                }
            }

            $unfinishedSteps = array_filter($steps, fn (CacheStepDto $step) => !$step->isFinished());

            if (empty($unfinishedSteps)) {
                $responsible->setStepsStatus(true);
            }
        } catch (Throwable $exception) {
            $this->handleException($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    private function resolveStep(Responsible $responsible, CacheStepDto $step): void
    {
        if ($step->hasChain()) {
            $this->handleChain($responsible, $step);
        } else {
            $this->handleScenario($responsible, $step);
            $responsible->setStepsStatus(true);
        }
    }

    /**
     * @throws Throwable
     */
    private function handleChain(Responsible $responsible, CacheStepDto $step): void
    {
        $this->chainsResolver->resolve($responsible, $step->getChains());
    }

    private function handleScenario(Responsible $responsible, CacheStepDto $step): void
    {
        $this->scenarioResolver->resolve($responsible, $step);
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
