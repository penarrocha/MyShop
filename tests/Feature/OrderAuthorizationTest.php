<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Support\CreatesUsersWithRoles;

class OrderAuthorizationTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsersWithRoles;

    public function test_customer_can_view_own_order_but_not_others(): void
    {
        $alice = $this->createUserWithRole('customer');
        $bob = $this->createUserWithRole('customer');

        $orderAlice = Order::factory()->create(['user_id' => $alice->id]);
        $orderBob = Order::factory()->create(['user_id' => $bob->id]);

        $this->actingAs($alice)
            ->get("/mi-cuenta/pedidos/{$orderAlice->id}")
            ->assertOk();

        $this->actingAs($alice)
            ->get("/mi-cuenta/pedidos/{$orderBob->id}")
            ->assertForbidden();
    }
}
