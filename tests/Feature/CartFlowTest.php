<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_update_remove_and_checkout_cart(): void
    {
        $product = Product::factory()->create();

        // Add
        $this->post('/cart', ['product_id' => $product->id])
            ->assertRedirect('/cart');

        $this->assertSame(1, session('cart')[$product->id]['quantity']);

        // Update
        $this->put("/cart/{$product->id}", ['quantity' => 3])
            ->assertRedirect('/cart');

        $this->assertSame(3, session('cart')[$product->id]['quantity']);

        // Remove
        $this->delete("/cart/{$product->id}")
            ->assertRedirect('/cart');

        $this->assertSame([], session('cart', []));

        // Checkout clears even if empty
        session()->put('cart', [$product->id => ['quantity' => 2]]);

        $this->post('/checkout')
            ->assertRedirect('/');

        $this->assertSame([], session('cart', []));
    }

    public function test_cart_state_endpoint_returns_expected_payload(): void
    {
        $product = Product::factory()->create(['price' => 10.00]);

        session()->put('cart', [
            $product->id => ['quantity' => 2],
        ]);

        $this->getJson('/cart/state')
            ->assertOk()
            ->assertJsonStructure(['totalQuantity', 'total', 'items'])
            ->assertJsonPath('totalQuantity', 2)
            ->assertJsonPath('total', 20);
    }
}
