<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\HasUniqueSlug;

class Order extends Model
{

    use SoftDeletes;

    use HasUniqueSlug;

    protected $fillable = ['user_id', 'status', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
