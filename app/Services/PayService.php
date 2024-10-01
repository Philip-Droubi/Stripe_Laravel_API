<?php

namespace App\Services;

use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use App\Models\Product;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;
use Stripe\Webhook;
use Stripe\Customer;
use Stripe\Price;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;

/**
 * Class PayService.
 */
class PayService extends MainService
{
    public function checkout($user, $order)
    {
        $lineItems = [];
        $products = $order->products;
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'USD',
                    'product_data' => [
                        'name' =>  $product->name,
                        'description' => "price is: " . $product->price,
                    ],
                    'unit_amount' => intval(($product->price) * 100),
                ],
                'quantity' => $product->pivot->amount,
            ];
        }
        Stripe::setApiKey(config('custom.stripe_secret'));
        if ($order->is_done)
            throw new InvalidRequestException("You already paid for this order.", 400);
        //App Domain
        $domain = config('app.url');
        //Create checkout session
        $checkOutSession = Session::create([
            'customer_email' => $user->email,
            'line_items' => $lineItems,
            'payment_intent_data' => [
                'metadata' => ['order_id' => $order->id, 'user_id' => $user->id],
            ],
            'metadata' => ['order_id' => $order->id, 'user_id' => $user->id],
            'mode' => 'payment',
            'success_url' => $domain . '/api/checkout-success?order_id=' . $order->id,
            'cancel_url' => $domain . '/api/checkout-cancel?order_id=' . $order->id,
        ]);
        return $checkOutSession->url;
    }

    public function paySuccess($order_id)
    {
        DB::beginTransaction();
        Order::query()->where('is_done', 0)->findOrFail($order_id)
            ->update(['is_done' => true]);
        DB::commit();
        return true;
    }

    public function payCancel($order_id)
    {
        DB::beginTransaction();
        $order = Order::query()->where('is_done', 0)->with('products')->findOrFail($order_id);
        foreach ($order->products as $product) {
            Product::where('id', $product->id)->update([
                'amount' => DB::raw('amount + ' . $product->pivot->amount)
            ]);
        }
        DB::commit();
        return true;
    }
}
