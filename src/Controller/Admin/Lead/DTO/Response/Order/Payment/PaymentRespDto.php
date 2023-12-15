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

    private int $totalPrice = 0;

    private string $totalPriceWithFraction = '0'; // todo WF

    public function isPaymentStatus(): bool
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(bool $paymentStatus): void
    {
        $this->paymentStatus = $paymentStatus;
    }

    public function getProductPrice(): int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): void
    {
        $this->productPrice = $productPrice;
    }

    public function getShippingPrice(): int
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice(int $shippingPrice): void
    {
        $this->shippingPrice = $shippingPrice;
    }

    public function getPromotionSum(): int
    {
        return $this->promotionSum;
    }

    public function setPromotionSum(int $promotionSum): void
    {
        $this->promotionSum = $promotionSum;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getTotalPriceWithFraction(): string
    {
        return $this->totalPriceWithFraction;
    }

    public function setTotalPriceWithFraction(string $totalPriceWithFraction): void
    {
        $this->totalPriceWithFraction = $totalPriceWithFraction;
    }
}
