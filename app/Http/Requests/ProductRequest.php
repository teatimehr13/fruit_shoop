<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


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
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);
        $req = $isUpdate ? 'sometimes' : 'required';
        $productId = $this->route('product');

        return [
            'subcategory_id' => [$req, 'integer', 'exists:subcategories,id'],
            'slug' => [$req, 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            // 'name' => [$req, 'string', 'max:255'],
            'price' => [$req, 'numeric', 'min:0',],
            'image' => ['image', 'nullable', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_enabled' => [$req, 'boolean'],
            'remove_image' => ['sometimes','boolean']
        ];
    }
}
