<?php

namespace App\Service\System\Resolver\Steps;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheStepDto;
use App\Service\System\Resolver\Chains\ChainsResolver;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\System\Resolver\Scenario\ScenarioResolver;
use Exception;
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
    public function resolve(Contract $contract): void
    {
        $steps = $contract->getCacheDto()->getEvent()->getSteps();

        try {
            foreach ($steps as $step) {
                if (!$step->isFinished()) {
                    $this->resolveStep($contract, $step);

                    $chains = array_filter(
                        $step->getChains(),
                        function (CacheChainDto $object) {
                            return !$object->isFinished();
                        }
                    );

                    if (empty($chains)) {
                        $step->setFinished(true);
                    }

                    break;
                }
            }

            $chains = array_filter(
                $steps,
                function (CacheStepDto $step) {
                    return !$step->isFinished();
                }
            );

            if (empty($chains)) {
                $contract->setStepsStatus(true);
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
