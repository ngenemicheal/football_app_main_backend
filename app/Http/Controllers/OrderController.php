<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create([
            'fan_id' => $request->fan_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Order created', 
            'order' => $order
        ]);
    }

    public function index()
    {
        $orders = Order::with('fan', 'product')->paginate(10);
    
        return view('orders.products', compact('orders'));
    }   
}
