<?php

namespace App\Service\System\Resolver;

use App\Entity\Visitor\VisitorEvent;
use App\Enum\VisitorEventStatusEnum;
use App\Service\DtoRepository\ContractDtoRepository;
use App\Service\System\Common\SenderService;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\System\Resolver\Jumps\JumpResolver;
use App\Service\System\Resolver\Steps\StepResolver;
use Throwable;

class EventResolver
{
    public function __construct(
        private readonly StepResolver $stepResolver,
        private readonly SenderService $senderService,
        private readonly JumpResolver $gotoResolver,
        private readonly ContractDtoRepository $contractDtoRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function resolve(VisitorEvent $visitorEvent, Contract $contract): Contract
    {
        $this->stepResolver->resolve($contract);

        $jump = $contract->getJump();

        if (is_null($jump)) {
            $this->senderService->sendMessages($contract);

            $status = $contract->isStepsStatus() ? VisitorEventStatusEnum::Done : VisitorEventStatusEnum::Waiting;
        } else {
            $status = VisitorEventStatusEnum::New;

            // todo другая реализация...
            dd('Вернуть jump');

//            $this->gotoResolver->resolveJump(
//                visitorEvent: $visitorEvent,
//                cacheDto: $cacheDto,
//                visitorSession: $visitorSession,
//                contract: $contract
//            );
        }

        if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'dev') {
            $this->contractDtoRepository->save($visitorEvent, $contract);
        }

        if ($status === VisitorEventStatusEnum::Done) {
            $contract->getCacheDto()->clearEvent();
        }

        $contract->setStatus($status);

        return $contract;
    }
}
