<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes; // WAJIB

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'thumbnail',
        'about',
        'stock',
        'price',
        'unit',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke harga kustom (Workshop, Ojol, dll)
    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function getPriceForCustomer($customerType)
    {
        // Cari aturan harga berdasarkan tipe customer (ojol, bengkel, dll)
        $rule = $this->productPrices()->whereHas('priceType', function ($q) use ($customerType) {
            $q->where('code', $customerType);
        })->first();

        if (!$rule) return $this->price;

        return match ($rule->rule_type) {
            'percent' => $this->price - ($this->price * ($rule->value / 100)),
            'minus'   => $this->price - $rule->value,
            'custom'  => $rule->value,
            default   => $this->price,
        };
    }

    public function getFinalPrice($type = 'normal')
    {
        // Jika normal, langsung kembalikan price
        if ($type == 'normal') return $this->price;

        // Cari aturan harga di tabel product_prices
        $rule = $this->productPrices()->whereHas('priceType', function ($q) use ($type) {
            $q->where('code', $type);
        })->first();

        if (!$rule) return $this->price;

        // Hitung berdasarkan rule_type
        return match ($rule->rule_type) {
            'percent' => $this->price - ($this->price * ($rule->value / 100)),
            'minus'   => $this->price - $rule->value,
            'custom'  => $rule->value,
            default   => $this->price,
        };
    }

    public function getPriceForType($typeCode)
    {
        // Cari aturan harga untuk tipe ini (bengkel/ojol/dll)
        $priceRule = $this->productPrices()->whereHas('priceType', function ($query) use ($typeCode) {
            $query->where('code', $typeCode);
        })->first();

        if (!$priceRule) return $this->price;

        // Logika perhitungan sesuai ERD Anda
        return match ($priceRule->rule_type) {
            'percent' => $this->price - ($this->price * ($priceRule->value / 100)),
            'minus'   => $this->price - $priceRule->value,
            'custom'  => $priceRule->value, // Harga tetap (fixed price)
            default   => $this->price,
        };
    }

    public function getThumbnailAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return url(Storage::url($value));
    }
}
