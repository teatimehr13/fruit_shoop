<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductOption>
 */
class ProductOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        $original = $this->faker->numberBetween(300, 9999);
        // 折扣價 0~原價之間
        $price = $this->faker->numberBetween(100, $original);

        return [
            'product_id'  => null,
            'option_text' => $this->faker->unique()->words(2, true),
            'original_price' => $original,
            'price' => $price,
            'inventory' => $this->faker->numberBetween(1, 50),
            // 'image'  => '/storage/products/' . $name . $this->faker->uuid . '.jpg',
            'sort_order' => $this->faker->numberBetween(0, 99),
            'is_enabled' => $this->faker->boolean(90),
        ];
    }
}
