<?php

namespace App\Service\Constructor\Core;

use App\Entity\Visitor\VisitorEvent;
use App\Enum\VisitorEventStatusEnum;
use App\Service\Common\SenderService;
use App\Service\Constructor\Core\Contract\ContractResolver;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\DtoRepository\ResponsibleDtoRepository;
use Throwable;

readonly class EventResolver
{
    public function __construct(
        private ContractResolver         $contractResolver,
        private SenderService            $senderService,
        private ResponsibleDtoRepository $responsibleDtoRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function resolve(VisitorEvent $visitorEvent, Responsible $responsible): Responsible
    {
        $this->contractResolver->resolve($responsible);

        if ($responsible->isExistJump()) {
            return $responsible;
        }

        $this->senderService->sendMessages($responsible);

        $status = $responsible->isContractStatus() ? VisitorEventStatusEnum::Done : VisitorEventStatusEnum::Waiting;

        if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'dev') {
            $this->responsibleDtoRepository->save($visitorEvent, $responsible);
        }

        if ($status === VisitorEventStatusEnum::Done) {
            $responsible->getCacheDto()->clearEvent();
        }

        $responsible->setStatus($status);

        return $responsible;
    }
}
