<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        // 轉 slug（英文 OK；若擔心轉不出，可加簡單備援）
        $slug = Str::slug($name, '-');
        if ($slug === '') {
            $slug = Str::random(8);
        }

        return [
            'subcategory_id' => 31,
            'slug'        => $slug,
            'name'        => $name,
            'price'       => $this->faker->numberBetween(100, 9999),
            // 'image'       => '/products/' . $name . $this->faker->uuid . '.jpg',
            'description' => $this->faker->sentence(12),
            'is_enabled'  => $this->faker->boolean(90),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $pro) {
            ProductOption::factory()
                ->count(3)
                ->sequence(fn($seq) => ['sort_order' => $seq->index + 1])
                ->for($pro)  //將product_option 關聯到這個 Product
                ->create();
                // ->create(['product_id' => $pro->id]); // 指向剛建立的分類
        });
    }
}
