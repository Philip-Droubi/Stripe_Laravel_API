<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $this->orderService->myOrders();
        return $this->success($data);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $data = $this->orderService->store($validatedData);
        return $this->success(["url" => $data], "created");
    }

    public function show(Request $request): JsonResponse
    {
        $data = $this->orderService->show($request->id);
        return $this->success($data);
    }
}
