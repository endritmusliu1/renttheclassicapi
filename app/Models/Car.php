<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'year',
        'price_per_day',
        'image_url',
        'description',
    ];

    protected $casts = [
        'year' => 'integer',
        'price_per_day' => 'float',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
