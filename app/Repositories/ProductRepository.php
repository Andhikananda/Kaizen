<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository
{

  // *
  public function getAll(array $fields)
  {
    return Product::select($fields)->with(['category', 'productPrices.priceType'])->latest()->paginate(50);
  }

  // *
  public function getById(int $id, array $fields)
  {
    return Product::select($fields)->with(['category', 'productPrices.priceType'])->findOrFail($id);
  }

  public function create(array $data)
  {
    return DB::transaction(function () use ($data) {
      // 1. Pisahkan data harga dari data produk utama
      $prices = $data['prices'] ?? [];
      unset($data['prices']);

      // 2. Simpan produk utama
      $product = Product::create($data);

      // 3. Generate SKU
      $product->update([
        'sku' => 'PRD/' . str_pad($product->id, 5, '0', STR_PAD_LEFT)
      ]);

      // 4. Simpan data harga kustom (Nested Insert)
      if (!empty($prices)) {
        foreach ($prices as $price) {
          $product->productPrices()->create([
            'price_type_id' => $price['price_type_id'],
            'rule_type'     => $price['rule_type'],
            'value'         => $price['value'],
          ]);
        }
      }

      return $product->load('productPrices.priceType')->fresh();
    });
  }
}
