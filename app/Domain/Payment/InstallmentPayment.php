<?php 

namespace App\Domain\Payment;

use App\Domain\Cart\PaymentStrategyInterface;

class InstallmentPayment implements PaymentStrategyInterface
{
    private int $months;

    public function __construct(int $months)
    {
        $this->months = $months;
    }

    public function calculateTotal(float $baseAmount): float
    {
        $i = 0.01; // 1% ao mês
        $n = $this->months;

        return $baseAmount * pow((1 + $i), $n);
    }
}
