<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProductImage::class;
    public function definition(): array
    {
        $ext = $this->faker->randomElement(['jpg', 'jpeg', 'png', 'webp']);
        return [
            // 同時執行product的facotry，讓其有關聯的父層
            'product_id' => Product::factory(),

            // 資料庫只存相對路徑即可；前端顯示用 Storage::url()
            'image'      => 'products/' . $this->faker->unique()->uuid . '.' . $ext,

            // 預設不是封面，方便在 seeder 或 state 指定
            'is_primary' => false,

            // 60% 機率給說明文字
            'alt_text'   => $this->faker->optional(0.6)->sentence(3),
        ];
    }

    /** 方便指定封面 */
    public function primary(): static
    {
        return $this->state(fn() => ['is_primary' => true]);
    }
}
