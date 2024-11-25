<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductService.
 */
class ProductService extends MainService
{
    public function list($search): array
    {
        $data = [];

        //Get products
        $products = Product::query();
        if ($search)
            $products->where('name', 'like', '%' . strtolower($search) . '%');
        $products = $products->with("user")
            ->orderBy("created_at", "desc")
            ->paginate(16);

        //Generate response
        $data["items"] = ProductResource::collection($products);
        return $this->setPaginationData($products, $data);
    }

    public function store($validatedData): bool
    {
        DB::beginTransaction();
        Product::create([
            "user_id" => auth()->id(),
            "name" => $validatedData["name"],
            "amount" => $validatedData["amount"],
            "price" => $validatedData["price"],
        ]);
        DB::commit();
        return true;
    }

    public function show($id): ProductResource
    {
        return new ProductResource(
            Product::query()
                ->with(["user"])
                ->findOrFail($id)
        );
    }

    public function update($validatedData, $id): bool
    {
        DB::beginTransaction();
        $product = Product::query()->where("user_id", auth()->id())->findOrFail($id);
        $product->name = $validatedData["name"];
        $product->amount = $validatedData["amount"];
        $product->price = $validatedData["price"];
        $product->save();
        DB::commit();
        return true;
    }

    public function destroy($id): bool
    {
        return Product::query()
            ->where("user_id", auth()->id())
            ->findOrFail($id)
            ->delete();
    }
}
