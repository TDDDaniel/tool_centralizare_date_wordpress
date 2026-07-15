<?php

namespace App\Http\Controllers;

use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // luam toate comenzile, cu magazinul si produsele lor, cele mai noi primele,
        // cate 10 pe pagina (paginate face impartirea automat)
        $orders = Order::with(['shop', 'items'])->latest()->paginate(10);

        // le trimitem la view sub numele "orders"
        return view('dashboard', ['orders' => $orders]);
    }
}
