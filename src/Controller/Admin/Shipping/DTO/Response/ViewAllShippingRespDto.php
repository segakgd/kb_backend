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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getApplyFromAmount(): int
    {
        return $this->applyFromAmount;
    }

    public function setApplyFromAmount(int $applyFromAmount): self
    {
        $this->applyFromAmount = $applyFromAmount;

        return $this;
    }

    public function getApplyFromAmountWF(): string
    {
        return $this->applyFromAmountWF;
    }

    public function setApplyFromAmountWF(string $applyFromAmountWF): self
    {
        $this->applyFromAmountWF = $applyFromAmountWF;

        return $this;
    }

    public function getApplyToAmount(): int
    {
        return $this->applyToAmount;
    }

    public function setApplyToAmount(int $applyToAmount): self
    {
        $this->applyToAmount = $applyToAmount;

        return $this;
    }

    public function getApplyToAmountWF(): string
    {
        return $this->applyToAmountWF;
    }

    public function setApplyToAmountWF(string $applyToAmountWF): self
    {
        $this->applyToAmountWF = $applyToAmountWF;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
