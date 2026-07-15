<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),   // fiecare produs apartine unei comenzi
            'product_name' => $this->faker->randomElement(['Ciment Holcim 40 kg', 'Adeziv Ceresit CM 11', 'Placa OSB 3 12mm', 'Vopsea lavabila Savana 15L', 'Glet de finisare CT 127', 'Spuma poliuretanica Ceresit']),
            'quantity' => $this->faker->numberBetween(1, 4),
            'price_per_unit' => $this->faker->randomFloat(2, 20, 300),
        ];
    }
}
