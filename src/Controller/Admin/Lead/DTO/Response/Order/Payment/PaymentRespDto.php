<?php

namespace App\Controller\Admin\Lead\DTO\Response\Order\Payment;

/**
 * Внимание! Все расчёты валют делаем в целых, не в дробных, к тому же, используем только операции умножения!
 * Для отображения переводим в дробь отдельно. (totalPriceWithFraction)
 */
class PaymentRespDto
{
    private bool $paymentStatus = false;

    private int $productPrice = 0;

    private int $shippingPrice = 0;

    private int $promotionSum = 0;

    private int $totalAmount = 0;

    private string $totalAmountWF = '0';

    public function isPaymentStatus(): bool
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(bool $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getProductPrice(): int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getShippingPrice(): int
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice(int $shippingPrice): self
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }

    public function getPromotionSum(): int
    {
        return $this->promotionSum;
    }

    public function setPromotionSum(int $promotionSum): self
    {
        $this->promotionSum = $promotionSum;

        return $this;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getTotalAmountWF(): string
    {
        return $this->totalAmountWF;
    }

    public function setTotalAmountWF(string $totalAmountWF): self
    {
        $this->totalAmountWF = $totalAmountWF;

        return $this;
    }
}
