<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Shop;

class OrderController extends Controller
{
    public function show($id)
    {
        // dump($id);
        $order = Order::where('id', '=', $id)->with('items')->first();
        //dump($order);
        // Pass the $order variable to a Blade view
        return view('show', compact('order'));
    }

    public function create()
    {
        $shops = Shop::all();
        return view('comanda', compact('shops'));
    }

    public function store(Request $request)
    {
        // 1. Validam datele primite
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email',
            'address_county' => 'required|string',
            'address_city' => 'required|string',
            'address_street' => 'required|string',
            'address_number' => 'required|string',
            'address_building' => 'nullable|string',
            'address_entrance' => 'nullable|string',
            'address_floor' => 'nullable|string',
            'address_apartment' => 'nullable|string',
            'product_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);
        // 2. Cream comanda
        $order = Order::create([
            'shop_id' => $validated['shop_id'],
            'order_reference' => 'CMD-' . rand(1000, 9999),
            'customer_first_name' => $validated['customer_first_name'],
            'customer_last_name' => $validated['customer_last_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'address_county' => $validated['address_county'],
            'address_city' => $validated['address_city'],
            'address_street' => $validated['address_street'],
            'address_number' => $validated['address_number'],
            'address_postal_code' => '',
            'address_building' => $validated['address_building'] ?? null,
            'address_entrance' => $validated['address_entrance'] ?? null,
            'address_floor' => $validated['address_floor'] ?? null,
            'address_apartment' => $validated['address_apartment'] ?? null,
            'total_price' => $validated['quantity'] * $validated['price_per_unit'],
            'status' => 'asteapta_confirmare',
        ]);
        $order->items()->create([
            'product_name' => $validated['product_name'],
            'quantity' => $validated['quantity'],
            'price_per_unit' => $validated['price_per_unit'],
        ]);
        // 4. Il trimitem la pagina de detalii a comenzii noi
        return redirect('/comenzi/' . $order->id);
    }
}
