<?php

namespace App\Controller\Admin\Lead\Response\Order\Promotion;

use App\Controller\AbstractResponse;

class PromotionRespDto extends AbstractResponse
{
    public const CALCULATION_TYPE_PERCENT = 'percent';

    public const CALCULATION_TYPE_FIXED = 'fixed';

    public string $name;

    /** Могут быть как percent так и fixed */
    public string $calculationType;

    public string $code;

    /** Могут быть как проценты, так и валюта (зависит от типа) */
    public int $discount = 0;

    public int $totalAmount = 0;

    public string $totalAmountWF = '0.0';
}
