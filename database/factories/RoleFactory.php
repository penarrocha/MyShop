<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
        ];
    }

    public function admin(): self
    {
        return $this->state(fn () => ['name' => 'Admin', 'slug' => 'admin']);
    }

    public function customer(): self
    {
        return $this->state(fn () => ['name' => 'Customer', 'slug' => 'customer']);
    }
}
