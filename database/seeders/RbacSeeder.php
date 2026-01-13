<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrador']);
        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Cliente']);
    }
}
