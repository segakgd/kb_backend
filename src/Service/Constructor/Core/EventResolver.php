<?php

namespace App\Service\Constructor\Core;

use App\Entity\Visitor\VisitorEvent;
use App\Enum\VisitorEventStatusEnum;
use App\Message\SendTelegramMessage;
use App\Service\Constructor\Core\Contract\ContractResolver;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\DtoRepository\ResponsibleDtoRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

readonly class EventResolver
{
    public function __construct(
        private ContractResolver $contractResolver,
        private MessageBusInterface $bus,
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

            $status = $responsible->isContractStatus() ? VisitorEventStatusEnum::Done : VisitorEventStatusEnum::Waiting;

            // todo отправку нудно вынести отсюда вероятно
            $this->bus->dispatch(new SendTelegramMessage($responsible->getResult(), $responsible->getBotDto()));

            if ($status === VisitorEventStatusEnum::Done) {
                $responsible->getCacheDto()->clearEvent();
            }

            $responsible->setStatus($status);

            return $responsible;
        } catch (Throwable $exception) {
            dd($exception);
        }
    }
}
