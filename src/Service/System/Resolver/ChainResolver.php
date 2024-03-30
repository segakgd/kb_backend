<?php

namespace App\Service\System\Resolver;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\GotoChainsEnum;
use App\Service\System\Contract;
use App\Service\System\Resolver\Chains\Items\C10Chain;
use App\Service\System\Resolver\Chains\Items\C1Chain;
use App\Service\System\Resolver\Chains\Items\C2Chain;
use App\Service\System\Resolver\Chains\Items\C3Chain;
use App\Service\System\Resolver\Chains\Items\C4Chain;
use App\Service\System\Resolver\Chains\Items\C5Chain;
use App\Service\System\Resolver\Chains\Items\C6Chain;
use App\Service\System\Resolver\Chains\Items\C7Chain;
use App\Service\System\Resolver\Chains\Items\C8Chain;
use App\Service\System\Resolver\Chains\Items\C9Chain;
use Exception;

class ChainResolver
{
    /**
     * @throws Exception
     */
    public function resolve(Contract $contract, CacheDto $cacheDto): Contract
    {
        $chains = $cacheDto->getEvent()->getChains(); // todo вот была бы тут коллекция...

        // todo подумай в рамках ооп, создай сущность которая будех зранить значения нунешнего шага и всё такое...

        $chainCount = count($chains);

        // todo было бы здорово, если бы мы знаки какой chain не обработан... чтоб не проходить постоянно масиивом
        foreach ($chains as $key => $chain) {
            /** @var CacheChainDto $chain */

            if ($chain->isNotFinished()) {
                $isHandle = $this->handleByTarget($chain->getTarget(), $contract, $cacheDto);

                if ($contract->getGoto() !== null) {
                    break;
                }

                if ($isHandle) {
                    $chain->setFinished(true);
                }

                if ($chainCount === 1 + $key && $chain->isFinished()) {
                    $cacheDto->getEvent()->setFinished(true);
                }

                break;
            }
        }

        $cacheDto->getEvent()->setChains($chains); // todo зачем? там же внутри объекты, это же ссылки

        return $contract;
    }

    /**
     * @throws Exception
     */
    private function handleByTarget(GotoChainsEnum $target, Contract $contract, CacheDto $cacheDto): bool
    {
        $chain = match ($target) {
            GotoChainsEnum::refChain1 => new C1Chain(),
            GotoChainsEnum::refChain2 => new C2Chain(),
            GotoChainsEnum::refChain3 => new C3Chain(),
            GotoChainsEnum::refChain4 => new C4Chain(),
            GotoChainsEnum::refChain5 => new C5Chain(),
            GotoChainsEnum::refChain6 => new C6Chain(),
            GotoChainsEnum::refChain7 => new C7Chain(),
            GotoChainsEnum::refChain8 => new C8Chain(),
            GotoChainsEnum::refChain9 => new C9Chain(),
            GotoChainsEnum::refChain10 => new C10Chain(),
        };

        return $chain->chain($contract, $cacheDto);
    }
}
