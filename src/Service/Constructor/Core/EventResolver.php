<?php

namespace App\Service\Constructor\Core;

use App\Enum\VisitorEventStatusEnum;
use App\Service\Constructor\Core\Contract\ContractResolver;
use App\Service\Constructor\Core\Dto\Responsible;
use Throwable;

readonly class EventResolver
{
    public function __construct(
        private ContractResolver $contractResolver,
    ) {}

    /**
     * @throws Throwable
     */
    public function resolve(Responsible $responsible): Responsible
    {
        try {
            $this->contractResolver->resolve($responsible);

            if ($responsible->isExistJump()) {
                return $responsible;
            }

            $status = $responsible->getStatus() ?? VisitorEventStatusEnum::Waiting; // todo ну такое

            if ($status === VisitorEventStatusEnum::Done) {
                $responsible->clearEvent();
            }

            $responsible->setStatus($status);

            return $responsible;
        } catch (Throwable $exception) {
            dd($exception);
        }
    }
}
