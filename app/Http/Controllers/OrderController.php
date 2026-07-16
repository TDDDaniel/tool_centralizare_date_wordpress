<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($id)
    {
        dump($id);
        $order = Order::where('id', '=', $id)->with('items')->first();
        dump($order);
        // Pass the $order variable to a Blade view
        return view('show', compact('order'));
    }
}
