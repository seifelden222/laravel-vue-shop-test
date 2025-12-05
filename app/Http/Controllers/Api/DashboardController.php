<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return response()->json([
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
        ]);
    }
}
