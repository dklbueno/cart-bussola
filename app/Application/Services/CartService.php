<?php 

namespace App\Application\Services;

use App\Domain\Cart\Cart;
use App\Domain\Cart\Item;
use App\Domain\Cart\PaymentStrategyInterface;

class CartService
{
    public function checkout(array $itemsData, PaymentStrategyInterface $paymentStrategy): array
    {
        $cart = new Cart();

        foreach ($itemsData as $data) {
            $cart->addItem(new Item($data['name'], $data['unit_price'], $data['quantity']));
        }

        $baseTotal = $cart->total();
        $finalTotal = $paymentStrategy->calculateTotal($baseTotal);

        return [
            'items' => $cart->getItems(),
            'total' => $baseTotal,
            'final_total' => round($finalTotal, 2),
        ];
    }
}
