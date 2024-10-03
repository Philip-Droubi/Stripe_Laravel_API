<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use App\Models\Product;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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
        if ($order->status != OrderStatus::PENDING->value)
            throw new InvalidRequestException("You cannot pay for this order.", 400);
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
            'success_url' => $domain . '/checkout-success?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
            'cancel_url' => $domain . '/checkout-cancel?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
        ]);
        $order->session_id = $checkOutSession->id;
        $order->save();
        return $checkOutSession->url;
    }

    public function paySuccess($order_id, $session_id)
    {
        DB::beginTransaction();
        Order::query()->where(['status' => OrderStatus::PENDING->value, 'session_id' => $session_id])
            ->findOrFail($order_id)
            ->update(['status' => OrderStatus::DONE->value]);
        DB::commit();
        return true;
    }

    public function payCancel($order_id, $session_id)
    {
        DB::beginTransaction();
        $order = Order::query()->where(['status' => OrderStatus::PENDING->value, 'session_id' => $session_id])
            ->with('products')
            ->findOrFail($order_id);
        foreach ($order->products as $product) {
            Product::where('id', $product->id)->update([
                'amount' => DB::raw('amount + ' . $product->pivot->amount)
            ]);
        }
        $order->status = OrderStatus::CANCEL->value;
        $order->save();
        DB::commit();
        return true;
    }
}
