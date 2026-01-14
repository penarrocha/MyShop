<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Support\CloudinaryUrl;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property int $category_id
 * @property int|null $offer_id
 * @property string|null $image
 * @property \App\Models\Offer|null $offer
 * @property int|null $quantity
 * @property float|null $final_price
 * @property string $image_url
 */
class Product extends Model
{
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'slug', 'description', 'price', 'category_id', 'offer_id', 'image'];

    /**
     * Since we are going to use the {slug} instead of the {id}
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'image' => 'string'];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the offer that applies to this product.
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * Get the users who have this product in their cart (N:M relationship).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'product_user')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get a default image if the product doesn't have one
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?: config('app.no_image'),
        );
    }

    /**
     * Para el carrito ajax!
     */
    public function getImageUrlAttribute(): ?string
    {

        return CloudinaryUrl::url($this->image, 48, 48);
    }

    /**
     * Get the product's final price after applying discounts.
     */
    protected function finalPrice(): Attribute
    {
        return Attribute::make(
            get: function () {

                if ($this->offer && $this->offer->discount_percentage > 0) {
                    $discount = $this->price * ($this->offer->discount_percentage / 100);
                    return round($this->price - $discount, 2);
                }

                return $this->price;
            }
        );
    }
}
