<?php

namespace App\Http\Controllers;

use App\Services\PayService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private PayService $payService) {}

    public function checkoutSuccess(Request $request)
    {
        $order_id = $request->query('order_id');
        $this->payService->paySuccess($order_id);
        return $this->success("Payment succeeded");
    }

    public function checkoutCancel(Request $request)
    {
        $order_id = $request->query('order_id');
        $this->payService->payCancel($order_id);
        return $this->success("Payment failed");
    }
}
