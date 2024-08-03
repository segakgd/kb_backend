<?php

namespace App\Service\Constructor\Core;

use App\Dto\SessionCache\Cache\CacheChainDto;
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

            $unfinishedChains = array_filter($cacheContract->getChains(), fn (CacheChainDto $chain) => !$chain->isFinished());

            if (empty($unfinishedChains)) {
                $cacheContract->setFinished(true);
                $responsible->setStatus(VisitorEventStatusEnum::Done);
            }

            $status = $responsible->getStatus() ?? VisitorEventStatusEnum::Waiting; // todo ну такое

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
    private function resolveContract(Responsible $responsible, CacheContractDto $cacheContractDto): void
    {
        if ($cacheContractDto->hasChain()) {
            $this->actionResolver->resolve($responsible, $cacheContractDto->getChains());
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
