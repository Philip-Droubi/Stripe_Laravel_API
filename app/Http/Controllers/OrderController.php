<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $data = $this->orderService->myOrders();
        return $this->success($data);
    }

    public function store(OrderRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->orderService->store($validatedData);
        return $this->success(["url" => $data], "created");
    }

    public function show(Request $request)
    {
        $data = $this->orderService->show($request->id);
        return $this->success($data);
    }
}
