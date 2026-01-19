<?php

namespace Tests\Support;

use App\Models\Role;
use App\Models\User;

trait CreatesUsersWithRoles
{
    protected function createUserWithRole(string $slug): User
    {
        $user = User::factory()->create();

        $role = Role::query()->firstOrCreate(
            ['slug' => $slug],
            ['name' => ucfirst($slug), 'slug' => $slug]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);

        // Evita queries extra en middleware
        $user->load('roles');

        return $user;
    }
}
