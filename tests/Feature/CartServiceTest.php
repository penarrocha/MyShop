<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private CartService $cart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cart = app(CartService::class);
    }

    public function test_raw_is_empty_by_default(): void
    {
        $this->assertSame([], $this->cart->raw());
    }

    public function test_add_creates_item_in_session(): void
    {
        $this->cart->add(10);

        $cart = session()->get('cart', []);
        $this->assertArrayHasKey(10, $cart);
        $this->assertSame(1, $cart[10]['quantity']);
    }

    public function test_add_increments_existing_item_quantity(): void
    {
        $this->cart->add(10);
        $this->cart->add(10);

        $cart = session()->get('cart', []);
        $this->assertSame(2, $cart[10]['quantity']);
    }

    public function test_add_can_add_multiple_quantity(): void
    {
        $this->cart->add(10, 3);

        $cart = session()->get('cart', []);
        $this->assertSame(3, $cart[10]['quantity']);
    }

    public function test_update_sets_quantity_and_never_below_1(): void
    {
        $this->cart->add(10);

        $this->cart->update(10, 7);
        $this->assertSame(7, session('cart')[10]['quantity']);

        $this->cart->update(10, 0);
        $this->assertSame(1, session('cart')[10]['quantity']);
    }

    public function test_update_non_existing_product_does_nothing(): void
    {
        $this->cart->update(999, 5);

        $this->assertSame([], session()->get('cart', []));
    }

    public function test_remove_deletes_item_from_session(): void
    {
        $this->cart->add(10);
        $this->cart->add(11);

        $this->cart->remove(10);

        $cart = session()->get('cart', []);
        $this->assertArrayNotHasKey(10, $cart);
        $this->assertArrayHasKey(11, $cart);
    }

    public function test_clear_empties_cart(): void
    {
        $this->cart->add(10);
        $this->cart->clear();

        $this->assertSame([], session()->get('cart', []));
    }

    public function test_products_returns_collection_with_quantity_attribute(): void
    {
        // Creamos productos reales en DB
        $p1 = Product::factory()->create();
        $p2 = Product::factory()->create();

        // Metemos cantidades en sesión (tal como tu app)
        session()->put('cart', [
            $p1->id => ['quantity' => 2],
            $p2->id => ['quantity' => 5],
        ]);

        $products = $this->cart->products();

        $this->assertCount(2, $products);
        $this->assertSame(2, $products->firstWhere('id', $p1->id)->quantity);
        $this->assertSame(5, $products->firstWhere('id', $p2->id)->quantity);
    }

    public function test_state_returns_totals_and_items_using_final_price(): void
    {
        // Si tu modelo Product tiene accessor final_price, esto funciona.
        // Si NO lo tiene, ajusta el test a price o crea offer según tu app.
        $p1 = Product::factory()->create(['price' => 10.00]);
        $p2 = Product::factory()->create(['price' => 5.00]);

        session()->put('cart', [
            $p1->id => ['quantity' => 2],
            $p2->id => ['quantity' => 3],
        ]);

        $state = $this->cart->state();

        $this->assertArrayHasKey('totalQuantity', $state);
        $this->assertArrayHasKey('total', $state);
        $this->assertArrayHasKey('items', $state);

        $this->assertSame(5, $state['totalQuantity']); // 2 + 3
        $this->assertCount(2, $state['items']);

        // OJO: si final_price = price (sin ofertas), total = 2*10 + 3*5 = 35
        $this->assertSame(35.00, $state['total']);

        $item1 = collect($state['items'])->firstWhere('id', $p1->id);
        $this->assertSame(2, $item1['quantity']);
        $this->assertSame(20.00, $item1['subtotal']);
    }
}
