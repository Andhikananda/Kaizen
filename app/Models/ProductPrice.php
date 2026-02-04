<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $fillable = [
        'product_id',
        'price_type_id', // WAJIB ADA DI SINI
        'rule_type',
        'value',
    ];

    /**
     * Relasi balik ke Produk
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke Tipe Harga (Workshop, Ojol, dll)
     */
    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }
}
