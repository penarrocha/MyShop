<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => 'pending',
            'total' => $this->faker->randomFloat(2, 10, 500),
        ];
    }

    public function paid(): self
    {
        return $this->state(fn () => ['status' => 'paid']);
    }
}
