<?php

namespace App\Dto\Scenario;

//
// BookingMonth = 'booking.month';
//
// BookingDay = 'booking.day';
//
// BookingTime = 'booking.time';
//
// ContactAddress = 'contact.address';
//
// ContactFirstName = 'contact.firstName';
//
// ContactLastName = 'contact.lastName';
//
// ContactMiddleName = 'contact.middleName';
//
class ScenarioChainItemDto
{
    private string $target; // booking.month

    // run - запустить какой-то процесс(к примеру процесс оплаты товаров)
    // show - показать
    // save - сохранить
    // brake.if - выходим из цепочки
    // allowed.if - разрешён если выаолняется какой-то action с каким-то значением

    private string $action; // show save brake.if

    private bool $isFinish = false;

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function isFinish(): bool
    {
        return $this->isFinish;
    }

    public function setIsFinish(bool $isFinish): static
    {
        $this->isFinish = $isFinish;

        return $this;
    }
}
