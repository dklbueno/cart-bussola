<?php

namespace App\Domain\Cart;

interface PaymentStrategyInterface
{
    public function calculateTotal(float $baseAmount): float;
}
