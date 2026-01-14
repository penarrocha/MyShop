<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $label
 * @property string $recipient_name
 * @property string|null $phone
 * @property string $line1
 * @property string|null $line2
 * @property string $city
 * @property string $province
 * @property string $postcode
 * @property string $country
 * @property bool $is_default
 */
class Address extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'line1',
        'line2',
        'city',
        'province',
        'postcode',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
