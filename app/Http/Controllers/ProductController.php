<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $data = $this->productService->list($request->search);
        return $this->success($data);
    }

    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->productService->store($validatedData);
        return $this->success(null, "created");
    }

    public function show(Request $request)
    {
        $data = $this->productService->show($request->id);
        return $this->success($data);
    }

    public function update(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->productService->update($validatedData, $request->id);
        return $this->success(null, "Updated successfully");
    }

    public function destroy(Request $request)
    {
        $data = $this->productService->destroy($request->id);
        return $this->success(null, "deleted");
    }
}