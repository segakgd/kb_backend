<?php

namespace App\Dto\Scenario;

class ScenarioChainDto
{
    private const TARGETS = [
        'booking.month',
        'booking.day',
        'booking.time',
        'contact.address',
        'contact.firstName',
        'contact.lastName',
        'contact.middleName',
        'lead.products',

        'shop.products.category',
        'shop.products.popular',
        'shop.products.promotion',
        'shop.products',
        'shop.product',
    ];

    // cart.order
    // cart.contact
    // cart.shipping
    // cart.products
    // cart
    // cart.pay


    // or.not.empty
    // not.empty
    // empty

    private const ACTIONS = [
        'show',
        'save', // todo а нужен ли? edit подразумевает в себе созранение
        'run',
        'brake.if',
        'allowed.if',
    ];

    private string $target; // contact.lastName

//    private string $action;

    private array $requirements;

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

//    public function getAction(): string
//    {
//        return $this->action;
//    }
//
//    public function setAction(string $action): static
//    {
//        $this->action = $action;
//
//        return $this;
//    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function setRequirements(array $requirements): void
    {
        $this->requirements = $requirements;
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
