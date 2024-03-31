<?php

namespace App\Service\System\Resolver;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\GotoChainsEnum;
use App\Service\System\Common\CacheService;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\Visitor\Scenario\ScenarioService;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @deprecated need refactoring
 */
class JumpResolver
{
    public function __construct(
        private readonly ScenarioService $scenarioService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function goto(
        VisitorEvent $visitorEvent,
        CacheDto $cacheDto,
        VisitorSession $visitorSession,
        Contract $contract
    ): void {
        $enum = GotoChainsEnum::tryFrom($contract->getGoto());

        if ($enum) {
            $chains = $cacheDto->getEvent()->getChains();

            $flag = true;

            /** @var CacheChainDto $chain */
            foreach ($chains as $chain) {
                if ($chain->getTarget() === $enum) {
                    $chain->setRepeat(true);

                    $flag = false;
                }

                $chain->setFinished($flag);
            }
        } else {
            $scenario = match ($contract->getGoto()) {
                'main' => $this->scenarioService->getMainScenario(),
                'cart' => $this->scenarioService->getCartScenario(),
                default => $this->scenarioService->getDefaultScenario(),
            };

            $visitorEvent->setScenarioUUID($scenario->getUUID());

            $cacheDto->setEvent(CacheService::createCacheEventDto());
        }

        $this->insertCacheDtoFromSession($visitorSession, $cacheDto);

        $contract->setStatus(VisitorEvent::STATUS_NEW);
    }

    private function insertCacheDtoFromSession(VisitorSession $visitorSession, CacheDto $cacheDto): void
    {
        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);
    }
}
