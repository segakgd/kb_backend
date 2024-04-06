<?php

namespace App\Service\System\Resolver\Jumps;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\ChainStatusEnum;
use App\Service\DtoRepository\SessionCacheDtoRepository;
use App\Service\System\Common\CacheService;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\Visitor\Scenario\ScenarioService;
use Exception;

class JumpResolver
{
    public function __construct(
        private readonly ScenarioService $scenarioService,
        private readonly SessionCacheDtoRepository $sessionCacheDtoRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function resolveJump(
        VisitorEvent $visitorEvent,
        CacheDto $cacheDto,
        VisitorSession $visitorSession,
        Contract $contract,
    ): void {
        $jump = $contract->getJump();

        if (!$jump) {
            throw new Exception('Непредвиденная ситуация: переход не существует.');
        }

        $scenario = $this->resolveScenario($contract->getJump()->value);

        if ($scenario) {
            $visitorEvent->setScenarioUUID($scenario->getUUID());
            $cacheDto->setEvent(CacheService::createCacheEventDto());
        } else {
            $this->updateCacheChains($cacheDto, $jump->value);
        }

        $this->sessionCacheDtoRepository->save($visitorSession, $cacheDto);

        $contract->setStatus(ChainStatusEnum::New);
    }

    private function resolveScenario(string $jumpValue): ?Scenario
    {
        return match ($jumpValue) {
            'main' => $this->scenarioService->getMainScenario(),
            'cart' => $this->scenarioService->getCartScenario(),
            default => null,
        };
    }

    private function updateCacheChains(CacheDto $cacheDto, string $jumpValue): void
    {
        $chains = $cacheDto->getEvent()->getChains();
        $flag = true;

        foreach ($chains as $chain) {
            if ($chain->getTarget() === $jumpValue) {
                $chain->setRepeat(true);
                $flag = false;
            }

            $chain->setFinished($flag);
        }
    }
}
