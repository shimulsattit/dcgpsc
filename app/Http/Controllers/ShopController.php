<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\HeaderSetting;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $headerSetting = HeaderSetting::first();
        if (!$headerSetting || !$headerSetting->is_shop_enabled) {
            abort(404);
        }

        $products = Product::active()->orderBy('order')->get();
        return view('shop.index', compact('products'));
    }

    public function show(Product $product)
    {
        $headerSetting = HeaderSetting::first();
        if (!$headerSetting || !$headerSetting->is_shop_enabled || !$product->is_active) {
            abort(404);
        }

        return view('shop.show', compact('product'));
    }

    public function checkout(Product $product)
    {
        $headerSetting = HeaderSetting::first();
        if (!$headerSetting || !$headerSetting->is_shop_enabled || !$product->is_active) {
            abort(404);
        }

        return view('shop.checkout', compact('product'));
    }

    public function storeOrder(Request $request, Product $product)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:manual,automated',
        ]);

        $order = Order::create([
            'product_id' => $product->id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'amount' => $product->discount_price ?? $product->price,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status' => 'pending',
            'transaction_id' => $request->transaction_id,
            'order_notes' => $request->order_notes,
        ]);

        if ($request->payment_method === 'automated') {
            // Redirect to automated gateway (SSLCommerz placeholder)
            return redirect()->route('shop.payment.automated', $order);
        }

        return redirect()->route('shop.success', $order)->with('success', 'Order placed successfully! Please wait for verification.');
    }

    public function success(Order $order)
    {
        return view('shop.success', compact('order'));
    }
}
