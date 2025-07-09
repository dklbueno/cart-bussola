<?php 

use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_checkout_with_pix()
    {
        $response = $this->postJson('/api/checkout', [
            'payment_type' => 'pix',
            'items' => [
                ['name' => 'Produto A', 'unit_price' => 100, 'quantity' => 2],
                ['name' => 'Produto B', 'unit_price' => 50, 'quantity' => 1],
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'total' => 250.0,
                     'final_total' => 225.0, // 10% de desconto
                 ]);
    }

    public function test_checkout_with_credit_card_payment()
    {
        $response = $this->postJson('/api/checkout', [
            'payment_type' => 'credit_card',
            'card' => [
                'name' => 'Maria Souza',
                'number' => '4111111111111111',
                'expiry' => '10/26',
                'cvv' => '123',
            ],
            'items' => [
                ['name' => 'Produto X', 'unit_price' => 100, 'quantity' => 2],
            ]
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'total' => 200.0,
                    'final_total' => 180.0 // 10% desconto
                ]);
    }

    public function test_checkout_with_installment_payment()
    {
        $response = $this->postJson('/api/checkout', [
            'payment_type' => 'installment',
            'installments' => 6,
            'card' => [
                'name' => 'Carlos Lima',
                'number' => '4111111111111111',
                'expiry' => '11/27',
                'cvv' => '456',
            ],
            'items' => [
                ['name' => 'Notebook', 'unit_price' => 1000, 'quantity' => 1],
            ]
        ]);

        // 1000 * (1 + 0.01)^6 = 1061.52
        $response->assertStatus(200)
                ->assertJson([
                    'total' => 1000.0,
                    'final_total' => round(1000 * pow(1.01, 6), 2),
                ]);
    }

    public function test_installment_with_invalid_number_of_installments()
    {
        $response = $this->postJson('/api/checkout', [
            'payment_type' => 'installment',
            'installments' => 1, // inválido, mínimo é 2
            'card' => [
                'name' => 'Carlos Lima',
                'number' => '4111111111111111',
                'expiry' => '11/27',
                'cvv' => '456',
            ],
            'items' => [
                ['name' => 'TV', 'unit_price' => 500, 'quantity' => 1],
            ]
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['installments']);
    }

    public function test_credit_card_payment_missing_card_data()
    {
        $response = $this->postJson('/api/checkout', [
            'payment_type' => 'credit_card',
            'items' => [
                ['name' => 'Produto X', 'unit_price' => 100, 'quantity' => 1],
            ]
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'card.name',
                    'card.number',
                    'card.expiry',
                    'card.cvv',
                ]);
    }


}
