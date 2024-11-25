<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderService.
 */
class OrderService extends MainService
{
    public function __construct(private PayService $payService) {}

    public function myOrders(): array
    {
        $data = [];
        //Get orders
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->with(['user', 'products'])
            ->withCount('products')
            ->orderBy("created_at", "desc")
            ->paginate(16);
        //Generate response
        $data["items"] = OrderResource::collection($orders);
        return  $this->setPaginationData($orders, $data);
    }

    public function store($validatedData): string
    {
        DB::beginTransaction();
        $totlaPrice = 0;
        $products = collect($validatedData["products"]);
        $productsIds = $products->pluck('id')->toArray();
        foreach (Product::query()->whereIn("id", $productsIds)->get(["id", "price"]) as $product) {
            $totlaPrice += $product->price * $products->firstWhere('id', $product->id)['amount'];
        }

        $order = Order::create([
            "user_id" => auth()->id(),
            "total_price" => $totlaPrice,
            "status" => OrderStatus::PENDING->value,
        ]);

        foreach ($productsIds as $id) {
            $order->products()->attach($id, [
                "amount" => $products->firstWhere('id', $id)['amount'],
            ]);
            //Update products amount
            Product::query()->where("id", $id)->update([
                'amount' => DB::raw('amount - ' . $products->firstWhere('id', $id)['amount'])
            ]);
        }

        //Create Session URL
        $sessionURL = $this->payService->checkout(auth()->user(), $order);
        DB::commit();
        return $sessionURL;
    }

    public function show($id): OrderResource
    {
        return new OrderResource(
            Order::query()
                ->where('user_id', auth()->id())
                ->with('user', 'products')
                ->findOrFail($id)
        );
    }
}
