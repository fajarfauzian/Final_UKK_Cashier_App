<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;

// class SalesDetail extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'sale_id',
//         'product_id',
//         'unit_price',
//         'quantity',
//         'subtotal'
//     ];

//     protected $casts = [
//         'unit_price' => 'decimal:2',
//         'subtotal' => 'decimal:2',
//         'quantity' => 'integer',
//     ];

//     public function sale(): BelongsTo
//     {
//         return $this->belongsTo(Sale::class);
//     }

//     public function product(): BelongsTo
//     {
//         return $this->belongsTo(Product::class);
//     }
// }