<?php

namespace App\Domain\Cart;

class Item
{
    public function __construct(
        public string $name,
        public float $unitPrice,
        public int $quantity
    ) {}

    public function total(): float
    {
        return $this->unitPrice * $this->quantity;
    }
}
