<?php

namespace App\Controller\Admin\Shipping\DTO\Response;

class ViewAllShippingRespDto
{
    private string $name;

    private int $applyFromAmount;

    private string $applyFromAmountWF;

    private int $applyToAmount;

    private string $applyToAmountWF;

    private bool $isActive;

    private string $type;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getApplyFromAmount(): int
    {
        return $this->applyFromAmount;
    }

    public function setApplyFromAmount(int $applyFromAmount): void
    {
        $this->applyFromAmount = $applyFromAmount;
    }

    public function getApplyFromAmountWF(): string
    {
        return $this->applyFromAmountWF;
    }

    public function setApplyFromAmountWF(string $applyFromAmountWF): void
    {
        $this->applyFromAmountWF = $applyFromAmountWF;
    }

    public function getApplyToAmount(): int
    {
        return $this->applyToAmount;
    }

    public function setApplyToAmount(int $applyToAmount): void
    {
        $this->applyToAmount = $applyToAmount;
    }

    public function getApplyToAmountWF(): string
    {
        return $this->applyToAmountWF;
    }

    public function setApplyToAmountWF(string $applyToAmountWF): void
    {
        $this->applyToAmountWF = $applyToAmountWF;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
