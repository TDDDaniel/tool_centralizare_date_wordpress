<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shop_id' => Shop::factory(),
            'order_reference' => 'CMD-' . $this->faker->unique()->numberBetween(1000, 9999),
            // client: nume + prenume separat
            'customer_first_name' => $this->faker->firstName(),
            'customer_last_name' => $this->faker->lastName(),
            'customer_phone' => $this->faker->phoneNumber(),
            'customer_email' => $this->faker->safeEmail(),
            // adresa structurata (pe locale ro_RO => date romanesti)
            'address_county' => $this->faker->county(),      // judet
            'address_city' => $this->faker->city(),          // localitate
            'address_street' => $this->faker->streetName(),  // strada
            'address_number' => (string)$this->faker->numberBetween(1, 200),
            'address_postal_code' => $this->faker->postcode(),
            // optionale: optional() lasa uneori campul gol (null), realist
            'address_building' => $this->faker->optional()->bothify('##'),
            'address_entrance' => $this->faker->optional()->randomElement(['A', 'B', 'C']),
            'address_floor' => $this->faker->optional()->numberBetween(1, 10),
            'address_apartment' => $this->faker->optional()->numberBetween(1, 60),
            'total_price' => 0,   // il calculam din produse, in seeder (Pasul 3)
            'status' => $this->faker->randomElement(['asteapta confirmare', 'confirmata', 'livrata']),
            'employee_notes' => null,
        ];
    }
}
