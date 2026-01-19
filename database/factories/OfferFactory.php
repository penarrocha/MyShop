<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        $start = now()->subDays(2);
        $end = now()->addDays(2);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'discount_percentage' => $this->faker->randomFloat(2, 5, 50),
            'description' => $this->faker->sentence(),
            'active' => true,
            'start_date' => $start,
            'end_date' => $end,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn () => ['active' => false]);
    }

    public function expired(): self
    {
        return $this->state(fn () => [
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(5),
        ]);
    }
}
