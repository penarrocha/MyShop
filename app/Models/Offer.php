<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

/**
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property float $discount_percentage
 *
 * @property bool $is_in_window
 * @property string $is_active_label
 * @property string $window_label
 */
class Offer extends Model
{
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
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

    protected function isInWindow(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                if ($this->start_date === null || $this->end_date === null) {
                    return false;
                }

                return now()->between($this->start_date, $this->end_date);
            }
        );
    }

    protected function isActiveLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->active ? 'Activa' : 'Inactiva'
        );
    }

    protected function windowLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->is_in_window ? 'En vigor' : 'Expirada'
        );
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
