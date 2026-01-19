<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 5);
        $unit = $this->faker->randomFloat(2, 1, 200);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'unit_price' => $unit,
            'quantity' => $qty,
            'line_total' => round($unit * $qty, 2),
        ];
    }
}
