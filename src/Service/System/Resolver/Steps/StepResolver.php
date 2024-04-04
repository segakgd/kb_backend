<?php

namespace App\Service\System\Resolver\Steps;

use App\Dto\Scenario\ScenarioStepDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Service\System\Common\CacheService;
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

    public function resolve(array $steps, Contract $contract, CacheDto $cacheDto): void
    {
        try {
            /** @var ScenarioStepDto $step */
            foreach ($steps as $step) {
                $event = $cacheDto->getEvent();

                if ($step->hasChain()) {
                    $this->handleChain($contract, $event, $step);

                    if ($event->isEmptyChains()) {
                        // показываем что все шаги закончились
                        $contract->setStepsStatus(true);
                    }
                } else {
                    $this->handleScenario($contract, $event, $step);

                    // показываем что все шаги закончились
                    $contract->setStepsStatus(true);
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
    private function handleChain(Contract $contract, CacheEventDto $event, ScenarioStepDto $step): void
    {
        if ($event->isEmptyChains()) {
            CacheService::enrichStepCache($step->getChain(), $event);
        }

        $chains = $this->chainResolver->resolve($contract, $event->getChains());

        $event->setChains($chains);
    }

    private function handleScenario(Contract $contract, CacheEventDto $event, ScenarioStepDto $step): void
    {
        $this->scenarioResolver->resolve($contract, $step);

        $event->setFinished(true);
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
