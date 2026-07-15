<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),                          // nume de firma
            'type' => $this->faker->randomElement(['fizic', 'online']), // tipul magazinului
            'domain_or_address' => $this->faker->domainName(),          // ex: magazin.ro
            'tva_rate' => 19.00,                                        // cota TVA
            'price_includes_tva' => true,
        ];
    }
}
