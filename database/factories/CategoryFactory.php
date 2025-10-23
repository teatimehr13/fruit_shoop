<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => $this->faker->unique()->words(2, true), // 自動產生不同名稱
            'sort_order' => $this->faker->numberBetween(0, 99),
            'is_enabled' => true,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Category $cat) {
            \App\Models\Subcategory::factory()
                ->count(3)
                ->sequence(fn($seq) => ['sort_order' => $seq->index + 1])
                ->create(['category_id' => $cat->id]); // 指向剛建立的分類
        });
    }
}
