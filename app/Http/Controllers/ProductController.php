<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductSevice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    private ProductSevice $productSevice;

    public function __construct(ProductSevice $productSevice)
    {
        $this->productSevice = $productSevice;
    }

    /**
     * Menampilkan daftar semua produk.
     * **
     */
    public function index()
    {
        $fields = ['id', 'sku', 'name', 'thumbnail', 'price', 'category_id'];
        $product = $this->productSevice->getAll($fields);

        return response()->json(ProductResource::collection($product));
    }

    /**
     * Menyimpan produk baru ke database.
     * **
     */
    public function store(ProductRequest $request)
    {
        $product = $this->productSevice->create($request->validated());
        return response()->json(new ProductResource($product), 201);
    }


    /**
     * Menampilkan detail satu produk spesifik.
     * **
     */
    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'thumbnail', 'price', 'category_id'];
            $product = $this->productSevice->getById($id, $fields);

            return response()->json(new ProductResource($product));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'product not found'], 404);
        }
    }

    /**
     * Memperbarui data produk.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'sku'          => 'sometimes|unique:products,sku,' . $product->id,
            'name'         => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock'        => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product->update($request->all());

        return response()->json([
            'message' => 'Product updated successfully',
            'data'    => new ProductResource($product)
        ]);
    }

    /**
     * Menghapus produk (Soft Delete).
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully (soft delete)'
        ]);
    }
}
