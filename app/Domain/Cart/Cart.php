<?php

namespace App\Domain\Cart;

class Cart
{
    private array $items = [];

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function total(): float
    {
        return array_reduce($this->items, fn($sum, $item) => $sum + $item->total(), 0.0);
    }
}
