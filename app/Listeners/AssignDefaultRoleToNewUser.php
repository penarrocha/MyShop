<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Role;
use App\Models\User;

class AssignDefaultRoleToNewUser
{
    public function handle(Registered $event): void
    {
        $customer = Role::where('slug', 'customer')->first();

        // Si no existiere el Rol customer
        if (! $customer) {
            return;
        }

        if ($event->user instanceof User) {
            $event->user->roles()->syncWithoutDetaching([$customer->id]);
        }
    }
}
