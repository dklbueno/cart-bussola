<?php

namespace App\Domain\Payment;

use App\Domain\Cart\PaymentStrategyInterface;

class PixPayment implements PaymentStrategyInterface
{
    public function calculateTotal(float $baseAmount): float
    {
        return $baseAmount * 0.9; // 10% de desconto
    }
}
