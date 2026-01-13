<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Offer extends Model
{
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'discount_percentage',
        'description',
        'active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_percentage' => 'decimal:2',
    ];

    protected $appends = [
        'is_active_label',
        'is_in_window',
        'window_label',
    ];

    public function isInWindow(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->start_date || !$this->end_date) {
                return false;
            }

            return now()->between($this->start_date, $this->end_date);
        });
    }

    public function isActiveLabel(): Attribute
    {
        return Attribute::get(fn() => $this->active ? 'Activa' : 'Inactiva');
    }

    public function windowLabel(): Attribute
    {
        return Attribute::get(fn() => $this->is_in_window ? 'En vigor' : 'Expirada');
    }


    /**
     * Since we are going to use the {slug} instead of the {id}
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the products that have this offer.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
