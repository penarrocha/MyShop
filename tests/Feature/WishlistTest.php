<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Support\CreatesUsersWithRoles;

class WishlistTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsersWithRoles;

    public function test_customer_can_add_and_remove_products_from_wishlist(): void
    {
        $user = $this->createUserWithRole('customer');
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post("/mi-cuenta/favoritos/{$product->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->delete("/mi-cuenta/favoritos/{$product->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_admin_cannot_access_customer_account_area(): void
    {
        $admin = $this->createUserWithRole('admin');

        $this->actingAs($admin)
            ->get('/mi-cuenta/favoritos')
            ->assertForbidden();
    }
}
