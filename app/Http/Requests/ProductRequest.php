<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $this->route('product'),
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'about' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
            'unit' => 'required|string|max:24',
            'is_active' => 'boolean',
            // Validasi Array Prices
            'prices'                => 'nullable|array',
            'prices.*.price_type_id' => 'required|exists:price_types,id',
            'prices.*.rule_type'    => 'required|in:percent,minus,custom',
            'prices.*.value'        => 'required|numeric',
        ];
    }
}
