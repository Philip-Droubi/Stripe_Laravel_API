<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use App\Models\Product;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;

/**
 * Class PayService.
 */
class PayService extends MainService
{
    public function checkout($user, $order): string
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
                'metadata' => ['user_id' => $user->id],
            ],
            'metadata' => ['user_id' => $user->id],
            'mode' => 'payment',
            'success_url' => $domain . '/checkout-success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $domain . '/checkout-cancel?session_id={CHECKOUT_SESSION_ID}',
        ]);
        $order->session_id = $checkOutSession->id;
        $order->save();
        return $checkOutSession->url;
    }

    public function paySuccess($session_id): bool
    {
        DB::beginTransaction();
        $order = Order::query()
            ->where(['session_id' => $session_id])
            ->firstOrFail();
        if (!$order->status == OrderStatus::PENDING->value) {
            $order->status = OrderStatus::DONE->value;
            $order->save();
        }
        DB::commit();
        return true;
    }

    public function payCancel($session_id): bool
    {
        DB::beginTransaction();
        $order = Order::query()
            ->where(['status' => OrderStatus::PENDING->value, 'session_id' => $session_id])
            ->with('products')
            ->firstOrFail();
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

    public function webhook(): bool
    {
        $webhookSecret = config('custom.stripe_webhook');
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        //Create a stripe webhook construct event
        try {
            $event = Webhook::constructEvent($payload, $sig_header, $webhookSecret);
        } catch (\UnexpectedValueException $ex) {
            //Invalid payload
            throw new InvalidRequestException("", 400);
        } catch (SignatureVerificationException $ex) {
            //Invalid signature
            throw new InvalidRequestException("", 400);
        }

        //Handle events by its types
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $sessionId = $session->id;
                DB::beginTransaction();
                $order = Order::query()
                    ->where(['session_id' => $sessionId])
                    ->firstOrFail();
                if (!$order->status == OrderStatus::PENDING->value) {
                    $order->status = OrderStatus::DONE->value;
                    $order->save();
                }
                DB::commit();
                break;

            default:
                throw new InvalidRequestException("", 400);
        }
        return true;
    }
}
