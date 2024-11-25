<?php

namespace App\Http\Controllers;

use App\Services\PayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private PayService $payService) {}

    public function checkoutSuccess(Request $request)
    {
        $session_id = $request->query('session_id');
        $this->payService->paySuccess($session_id);
        return view('payment_success');
    }

    public function checkoutCancel(Request $request)
    {
        $session_id = $request->query('session_id');
        $this->payService->payCancel($session_id);
        return view('payment_cancel');
    }

    public function webhook(): JsonResponse
    {
        $this->payService->webhook();
        return $this->success();
    }
}
