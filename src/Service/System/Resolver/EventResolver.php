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
        private StepResolver          $stepResolver,
        private SenderService         $senderService,
        private JumpResolver          $jumpResolver,
        private ContractDtoRepository $contractDtoRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function resolve(VisitorEvent $visitorEvent, Contract $contract): Contract
    {
        $this->stepResolver->resolve($contract);

        $jump = $contract->getJump();

        if (!is_null($jump)) {
            return $contract;
        }

        $this->senderService->sendMessages($contract);

        $status = $contract->isStepsStatus() ? VisitorEventStatusEnum::Done : VisitorEventStatusEnum::Waiting;

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
