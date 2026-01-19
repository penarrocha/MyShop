<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Support\CreatesUsersWithRoles;

class RbacAccessTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsersWithRoles;

    public function test_customer_cannot_access_admin_panel(): void
    {
        $customer = $this->createUserWithRole('customer');

        $this->actingAs($customer)
            ->get('/admin/categories')
            ->assertForbidden();
    }

    public function test_admin_can_reach_admin_panel(): void
    {
        $admin = $this->createUserWithRole('admin');

        $this->actingAs($admin)
            ->get('/admin/categories')
            ->assertOk();
    }
}
