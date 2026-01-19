<?php

namespace Tests\Feature;

use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Support\CreatesUsersWithRoles;

class AdminOfferCrudTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsersWithRoles;

    public function test_admin_can_create_update_and_soft_delete_offer(): void
    {
        $admin = $this->createUserWithRole('admin');

        $payload = [
            'name' => 'Rebajas Enero',
            'slug' => 'rebajas-enero',
            'discount_percentage' => 15,
            'description' => 'Promo',
            'active' => '1',
            'start_date' => now()->subDay()->format('Y-m-d H:i:s'),
            'end_date' => now()->addDay()->format('Y-m-d H:i:s'),
        ];

        // Create
        $this->actingAs($admin)
            ->post('/admin/offers', $payload)
            ->assertRedirect();

        $offer = Offer::query()->where('slug', 'rebajas-enero')->firstOrFail();

        // Update
        $this->actingAs($admin)
            ->put("/admin/offers/{$offer->id}", array_merge($payload, [
                'name' => 'Rebajas Enero 2',
                'discount_percentage' => 20,
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('offers', [
            'id' => $offer->id,
            'name' => 'Rebajas Enero 2',
        ]);

        // Delete (soft)
        $this->actingAs($admin)
            ->delete("/admin/offers/{$offer->id}")
            ->assertRedirect('/admin/offers');

        $this->assertSoftDeleted('offers', ['id' => $offer->id]);
    }
}
