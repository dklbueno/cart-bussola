<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Application\Services\CartService;
use App\Domain\Payment\PixPayment;
use App\Domain\Payment\CreditCardPayment;
use App\Domain\Payment\InstallmentPayment;

class CartController extends Controller
{
    public function checkout(Request $request, CartService $service)
    {
        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|in:pix,credit_card,installment',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.unit_price' => 'required|numeric|min:0.01',
            'items.*.quantity' => 'required|integer|min:1',

            'card.name' => 'required_if:payment_type,credit_card,installment|string',
            'card.number' => 'required_if:payment_type,credit_card,installment|digits:16',
            'card.expiry' => 'required_if:payment_type,credit_card,installment|date_format:m/y',
            'card.cvv' => 'required_if:payment_type,credit_card,installment|digits_between:3,4',

            'installments' => 'required_if:payment_type,installment|integer|min:2|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $paymentType = $request->input('payment_type');
        $items = $request->input('items');
        $installments = $request->input('installments');

        $strategy = match ($paymentType) {
            'pix' => new PixPayment(),
            'credit_card' => new CreditCardPayment(),
            'installment' => new InstallmentPayment($installments),
            default => throw new \InvalidArgumentException("Forma de pagamento inválida")
        };

        $result = $service->checkout($items, $strategy);
        return response()->json($result);
    }
}
