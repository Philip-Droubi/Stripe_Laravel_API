<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $this->productService->list($request->search);
        return $this->success($data);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $this->productService->store($validatedData);
        return $this->success(null, "created");
    }

    public function show(Request $request): JsonResponse
    {
        $data = $this->productService->show($request->id);
        return $this->success($data);
    }

    public function update(ProductRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $this->productService->update($validatedData, $request->id);
        return $this->success(null, "Updated successfully");
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->productService->destroy($request->id);
        return $this->success(null, "deleted");
    }
}
