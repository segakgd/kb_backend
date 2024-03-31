<?php

namespace App\Service\System\Resolver;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\ChainStatusEnum;
use App\Service\System\Common\CacheService;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\Visitor\Scenario\ScenarioService;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class JumpResolver
{
    public function __construct(
        private readonly ScenarioService $scenarioService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws Exception
     */
    public function goto(
        VisitorEvent $visitorEvent,
        CacheDto $cacheDto,
        VisitorSession $visitorSession,
        Contract $contract,
    ): void {
        $jump = $contract->getJump();

        if (!$jump) {
            throw new Exception('Непредвиденная ситуация, несуществует enum-а.');
        }

        $scenario = match ($contract->getJump()->value) {
            'main' => $this->scenarioService->getMainScenario(),
            'cart' => $this->scenarioService->getCartScenario(),
            default => null,
        };

        if ($scenario) {
            $visitorEvent->setScenarioUUID($scenario->getUUID());

            $cacheDto->setEvent(CacheService::createCacheEventDto());
        } else {
            $chains = $cacheDto->getEvent()->getChains();

            $flag = true;

            /** @var CacheChainDto $chain */
            foreach ($chains as $chain) {
                if ($chain->getTarget() === $jump) {
                    $chain->setRepeat(true);

                    $flag = false;
                }

                $chain->setFinished($flag);
            }
        }

        $this->insertCacheDtoFromSession($visitorSession, $cacheDto);

        $contract->setStatus(ChainStatusEnum::New);
    }

    private function insertCacheDtoFromSession(VisitorSession $visitorSession, CacheDto $cacheDto): void
    {
        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);
    }
}
