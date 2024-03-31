<?php

namespace App\Service\System\Resolver;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Enum\JumpEnum;
use App\Helper\ChainsGeneratorHelper;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\System\Resolver\Dto\ContractInterface;
use Exception;

/**
 * @deprecated need refactoring
 */
class ChainResolver
{
    /**
     * @param array<CacheChainDto> $chains
     *
     * @throws Exception
     */
    public function resolve(Contract $contract, array $chains): array
    {
        // todo подумай в рамках ооп, создай сущность которая будех зранить значения нунешнего шага и всё такое...

        $chainCount = count($chains);

        // todo было бы здорово, если бы мы знаки какой chain не обработан... чтоб не проходить постоянно масиивом
        foreach ($chains as $key => $chain) {
            if ($chain->isNotFinished()) {
                $isHandle = $this->handleByTarget($chain->getTarget(), $contract);

                if ($contract->getJump() !== null) {
                    break;
                }

                if ($isHandle) {
                    $chain->setFinished(true);
                }

                if ($chainCount === 1 + $key && $chain->isFinished()) {
                    $contract->getChain()->setFinished(true);
                }

                break;
            }
        }

        return $chains;
    }

    /**
     * @throws Exception
     */
    private function handleByTarget(JumpEnum $target, ContractInterface $contract): bool
    {
        $chain = ChainsGeneratorHelper::generate($target);

        // todo вот тут по сути должно быть состояние того чейна, который бкдет следующий.
        $condition = ChainsGeneratorHelper::generate($target)->condition();

        $contract->setNextCondition($condition);

        return $chain->chain($contract);
    }
}
