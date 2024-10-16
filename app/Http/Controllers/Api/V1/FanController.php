<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Fan;
use Illuminate\Validation\ValidationException;

class FanController extends Controller
{
    // Register a new fan
    public function register(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:fans',
            'password' => 'required|string|min:8|confirmed',
            'favourite_player' => 'nullable|string|max:255',
        ]);
    
        $fan = Fan::create($validated_data);
    
        // Create token for fan after registration
        $token = $fan->createToken($request->email)->plainTextToken;
    
        return response()->json([
            'message' => 'Registration successful',
            'fan' => $fan, 
            'token' => $token
        ], 201);
    }

    // Fan login
    public function login(Request $request)
    {
        // Validate login data
        $request->validate([
            'email' => 'required|email|exists:fans',
            'password' => 'required',
        ]);

        $fan = Fan::where('email', $request->email)->first();

        if (!Hash::check($request->password, $fan->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $fan->createToken($request->email)->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'fan' => $fan, 
            'token' => $token
        ], 201);

        return 'Login';
    }

    // Fan logout
    public function logout(Request $request)
    {
        // delete all tokens for the fan (logout)
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
        return 'Logout';
    }

    public function getFanOrders(Request $request, Fan $fan)
    {
        // Get the authenticated fan
        $authenticatedFan = $request->user();

        // Check if the authenticated fan is the same as the one in the route
        if ($authenticatedFan->id !== $fan->id) {
            return response()->json(['message' => 'Access denied. You cannot view other fans\' orders.'], 403);
        }

        $orders = $fan->orders()->with('product')->get()->map(function ($order) {
            return [
                'orderID' => $order->id,
                'productID' => $order->product->id,
                'productName' => $order->product->product_name,
                'quantity' => $order->quantity,
                'price' => $order->total_price,
                'orderCreatedAt' => $order->created_at->format('Y-m-d H:i:s'),
                'status' => $order->status,
            ];
        });

        return response()->json([
            'fan' => $fan->name,
            'orders' => $orders
        ]);
    }
}
