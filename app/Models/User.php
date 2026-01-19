<?php

namespace App\Models;

use App\Models\Address;
use App\Models\Order;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property \Illuminate\Database\Eloquent\Collection<int, Role> $roles
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }


    /**
     * Get the products in the user's cart (N:M relationship).
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_user')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getRoleNamesAttribute(): Collection
    {
        // Si la relación está cargada, no ejecuta consultas adicionales
        if ($this->relationLoaded('roles')) {
            return $this->roles
                ->pluck('name')
                ->filter()
                ->unique()
                ->values();
        }

        // Si no está cargada
        return $this->roles()
            ->pluck('name')
            ->filter()
            ->unique()
            ->values();
    }
}
