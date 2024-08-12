<?php

namespace App\Service\Constructor\Core;

use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Enum\VisitorEventStatusEnum;
use App\Service\Constructor\Core\Actions\ActionResolver;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\Constructor\Core\Scenario\ScenarioResolver;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class EventResolver
{
    public function __construct(
        private ActionResolver $actionResolver,
        private ScenarioResolver $scenarioResolver,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws Throwable
     */
    public function resolve(Responsible $responsible): Responsible
    {
        try {
            $cacheContract = $responsible->getEvent()->getContract();

            $this->resolveContract($responsible, $cacheContract);

            if ($cacheContract->isAllActionsFinished()) {
                $cacheContract->setFinished(true);
                $responsible->setStatus(VisitorEventStatusEnum::Done);
            }

            $status = $responsible->getStatus();

            if ($status === VisitorEventStatusEnum::Done) {
                $responsible->clearEvent();
            }

            $responsible->setStatus($status);

            return $responsible;
        } catch (Throwable $exception) {
            $this->handleException($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    private function resolveContract(Responsible $responsible, CacheContractDto $cacheContract): void
    {
        if ($cacheContract->hasActions()) {
            $responsible->setStatus(VisitorEventStatusEnum::Waiting);

            $this->actionResolver->resolve($responsible, $cacheContract->getActions());
        } else {
            $this->scenarioResolver->resolve($responsible, $cacheContract);

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
