<?php

namespace App\Service\System\Handler\Items\Sub;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainsEnum;
use App\Service\System\Handler\Contract;
use Exception;
use Throwable;

class StepHandler
{
    public function __construct(
        private readonly ChainHandler $chainHandler,
        private readonly ScenarioHandler $scenarioHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(
        Contract $contract,
        CacheDto $cacheDto,
        array $step,
        string $scenarioUUID,
    ): Contract {
        $stepChains = $step['chain'];
        try {
            if (!empty($step['chain'])) {
                // todo проверить, можем ли взять данный step и chain

                if (!$cacheDto->getEvent()->isExistChains()) {
                    $this->enrich($stepChains, $cacheDto);
                    // Не существует, обогащаем
                }


//                $cacheDto->setContent();

                // существует

                $contract = $this->chainHandler->handle($contract, $cacheDto);


                // по хорошему, нужно посмотреть, есть ли какие-то цепочки которые не завершены в предыдущей сесии.
                // и это делать нужно до обработки по сценарию, до того как в сценарий лезть
                //
                // если нету ничего, тогда в кеш копируем chain-сы если они есть у сценария
            } else {
                $contract = $this->scenarioHandler->handle($contract, $step);
            }
        } catch (Throwable $exception) {
            dd($exception);
        }

//        if (isset($step['chain']) || $cacheDto->getEvent()->getChains()) {
//            $contract = $this->chainHandler->handle($contract, $cacheDto);
//        } else {
//            $contract = $this->scenarioHandler->handle($contract, $step);
//        }
//        dd('sad');

        return $contract;
    }

    private function enrich(array $stepChains, CacheDto $cacheDto): CacheDto
    {
        foreach ($stepChains as $stepChain) {
            $chain = (new CacheChainDto)
                ->setTarget(ChainsEnum::from('shop.products'))
                ->setFinished($stepChain['finish']);

            $cacheDto->getEvent()->addChain($chain);
        }

        return $cacheDto;
    }
}
