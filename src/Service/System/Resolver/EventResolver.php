<?php

namespace App\Service\System\Resolver;

use App\Entity\Visitor\VisitorEvent;
use App\Enum\VisitorEventStatusEnum;
use App\Service\DtoRepository\ResponsibleDtoRepository;
use App\Service\System\Common\SenderService;
use App\Service\System\Resolver\Dto\Responsible;
use App\Service\System\Resolver\Jumps\JumpResolver;
use App\Service\System\Resolver\Steps\StepResolver;
use Throwable;

class EventResolver
{
    public function __construct(
        private StepResolver             $stepResolver,
        private SenderService            $senderService,
        private JumpResolver             $jumpResolver,
        private ResponsibleDtoRepository $responsibleDtoRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function resolve(VisitorEvent $visitorEvent, Responsible $responsible): Responsible
    {
        $this->stepResolver->resolve($responsible);

        $jump = $responsible->getJump();

        if (!is_null($jump)) {
            return $responsible;
        }

        $this->senderService->sendMessages($responsible);

        $status = $responsible->isStepsStatus() ? VisitorEventStatusEnum::Done : VisitorEventStatusEnum::Waiting;

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
