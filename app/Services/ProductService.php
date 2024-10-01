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
    public function list($search)
    {
        $data = [];
        $items = [];
        //Get products
        $products = Product::query();
        if ($search)
            $products->where('name', 'like', '%' . strtolower($search) . '%');
        $products = $products->with("user")
            ->orderBy("created_at", "desc")->paginate(16);
        //Generate response
        foreach ($products as $product) {
            $items[] = new ProductResource($product);
        }
        $data["items"] = $items;
        $data = $this->setPaginationData($products, $data);
        return $data;
    }

    public function store($validatedData)
    {
        DB::beginTransaction();
        $product = Product::create([
            "user_id" => auth()->id(),
            "name" => $validatedData["name"],
            "amount" => $validatedData["amount"],
            "price" => $validatedData["price"],
        ]);
        DB::commit();
        return true;
    }

    public function show($id)
    {
        $product = Product::query()->with(["user"])->findOrFail($id);
        return new ProductResource($product);
    }

    public function update($validatedData, $id)
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

    public function destroy($id)
    {
        return Product::query()->where("user_id", auth()->id())->findOrFail($id)->delete();
    }
}
