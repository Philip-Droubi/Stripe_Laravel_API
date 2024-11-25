<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to cancel unfinished payments after 3 hours of payments creation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::query()->where(["status" => OrderStatus::PENDING->value])
            ->where("created_at", '<', Carbon::now()->subHours(3))
            ->with("products")
            ->get();

        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                Product::where('id', $product->id)->update([
                    'amount' => DB::raw('amount + ' . $product->pivot->amount)
                ]);
            }

            $order["status"] = OrderStatus::CANCEL->value;
            $order->save();
        }
    }
}