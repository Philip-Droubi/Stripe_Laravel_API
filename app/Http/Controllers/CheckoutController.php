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
        $session_id = $request->query('session_id');
        $this->payService->paySuccess($order_id, $session_id);
        return view('payment_success');
    }

    public function checkoutCancel(Request $request)
    {
        $order_id = $request->query('order_id');
        $session_id = $request->query('session_id');
        $this->payService->payCancel($order_id, $session_id);
        return view('payment_cancel');
    }
}
