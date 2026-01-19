<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1, 500),
            'category_id' => Category::factory(),
            'offer_id' => null,
            'image' => null,
        ];
    }

    public function withOffer(?Offer $offer = null): self
    {
        return $this->state(fn () => [
            'offer_id' => $offer?->id ?? Offer::factory(),
        ]);
    }
}
