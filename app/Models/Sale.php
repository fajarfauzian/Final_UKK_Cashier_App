<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'amount_paid',
        'change',
        'is_member',
        'phone',
        'customer_name',
        'use_points',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change' => 'decimal:2',
        'is_member' => 'boolean',
        'use_points' => 'boolean',
    ];

    protected $with = ['user', 'salesDetails'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesDetails(): HasMany
    {
        return $this->hasMany(SalesDetail::class);
    }
}