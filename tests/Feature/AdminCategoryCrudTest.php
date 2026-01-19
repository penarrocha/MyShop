<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Support\CreatesUsersWithRoles;

class AdminCategoryCrudTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsersWithRoles;

    public function test_admin_can_create_update_and_delete_category_when_empty(): void
    {
        $admin = $this->createUserWithRole('admin');

        // Create
        $payload = [
            'name' => 'Cascos',
            'slug' => 'cascos',
            'description' => 'Categoría de cascos',
        ];

        $this->actingAs($admin)
            ->post('/admin/categories', $payload)
            ->assertRedirect('/admin/categories');

        $category = Category::query()->where('slug', 'cascos')->firstOrFail();

        // Update
        $this->actingAs($admin)
            ->put("/admin/categories/{$category->id}", [
                'name' => 'Cascos HiFi',
                'slug' => 'cascos',
                'description' => 'Actualizada',
            ])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Cascos HiFi',
        ]);

        // Delete
        $this->actingAs($admin)
            ->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories');

        // Soft deletes are enabled in your app
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_products(): void
    {
        $admin = $this->createUserWithRole('admin');

        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs($admin)
            ->delete("/admin/categories/{$category->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
