<?php

namespace App\Service\System\Resolver;

use App\Dto\Scenario\ScenarioStepDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
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
            /** @var ScenarioStepDto $step */
            foreach ($steps as $step) {
                dd($step);
                // todo $step['chain'] переделать в объекты
                $chains = $step['chain'] ?? [];

                if (!empty($chains)) {
                    $event = $cacheDto->getEvent();

                    if ($event->isEmptyChains()) {
                        CacheService::enrichStepCache($step['chain'], $cacheDto);
                    }

                    $this->chainResolver->resolve($contract, $event->getChains());

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
