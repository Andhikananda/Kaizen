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
     * ***
     */
    public function index()
    {

        $fields = ['id', 'sku', 'name', 'thumbnail', 'price', 'category_id'];
        $products = $this->productSevice->getAll($fields);

        // Kirim data ke file blade: resources/views/products/index.blade.php
        return view('products.index', compact('products'));
        // return response()->json(ProductResource::collection($product));
    }

    /**
     * Menyimpan produk baru ke database.
     * **
     */
    public function store(ProductRequest $request)
    {
        $this->productSevice->create($request->validated());
        return redirect()->route('products.index')->with('success', 'Product created successfully');
        // return response()->json(new ProductResource($product), 201);
    }


    /**
     * Menampilkan detail satu produk spesifik.
     * ***
     */
    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'thumbnail', 'price', 'category_id'];
            $product = $this->productSevice->getById($id, $fields);

            return view('products.show', compact('product'));
            // return response()->json(new ProductResource($product));
        } catch (ModelNotFoundException $e) {
            abort(404, 'Product not found');
            // return response()->json(['message' => 'product not found'], 404);
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
