<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // userul de test (pentru login rapid)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 1. Cream 3 magazine false
        $shops = Shop::factory(3)->create();

        // 2. Cream 15 comenzi false
        foreach (range(1, 15) as $i) {
            // fiecare comanda apartine unui magazin ales aleator dintre cele 3
            $order = Order::factory()->create([
                'shop_id' => $shops->random()->id,
            ]);

            // adaugam intre 1 si 3 produse la comanda
            $items = OrderItem::factory(rand(1, 3))->create([
                'order_id' => $order->id,
            ]);

            // calculam totalul = suma (cantitate * pret pe bucata) a produselor
            $total = $items->sum(fn($item) => $item->quantity * $item->price_per_unit);

            // scriem totalul real in comanda (factory-ul il lasase 0)
            $order->update(['total_price' => $total]);
        }
    }
}
