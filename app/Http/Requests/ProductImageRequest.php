<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageRequest extends FormRequest
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

        return [
            // 'product_id' => [$req, 'integer', 'exists:products,id'],
            // 'image' => ['image', 'nullable', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // 'is_primary' => [$req, 'boolean'],
            // 'alt_text' => ['nullable','string'],
            // 'sort_order' => [],

            'productImages' => [$req, 'array'],
            'productImages.*.product_id' => [$req, 'integer', 'exists:products,id'],
            'productImages.*.image' => ['image', 'nullable', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'productImages.*.is_primary' => [$req, 'boolean'],
            'productImages.*.alt_text' => ['nullable','string'],
        ];
    }
}
