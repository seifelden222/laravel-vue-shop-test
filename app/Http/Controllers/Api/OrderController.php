<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $user = auth()->user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        try {
            return DB::transaction(function () use ($request, $user, $cartItems) {
                // Validate Stock
                foreach ($cartItems as $cartItem) {
                    if ($cartItem->product->stock < $cartItem->quantity) {
                        throw new \Exception("Product {$cartItem->product->name} is out of stock or insufficient quantity.");
                    }
                }

                $total = $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => Str::upper(Str::random(10)),
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'total' => $total,
                    'status' => 'pending',
                ]);

                $itemsSummary = [];

                foreach ($cartItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price,
                        'subtotal' => $cartItem->product->price * $cartItem->quantity,
                    ]);

                    // Decrease Stock
                    $cartItem->product->decrement('stock', $cartItem->quantity);

                    // Update status if stock is 0
                    if ($cartItem->product->fresh()->stock == 0) {
                        $cartItem->product->update(['status' => 'out_of_stock']);
                    }

                    $itemsSummary[] = [
                        'product' => $cartItem->product->name,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price,
                    ];
                }

                CartItem::where('user_id', $user->id)->delete();

                return response()->json([
                    'message' => 'Order created successfully',
                    'order_number' => $order->order_number,
                    'total' => $total,
                    'items' => $itemsSummary,
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
