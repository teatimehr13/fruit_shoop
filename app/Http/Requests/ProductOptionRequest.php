<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductOptionRequest extends FormRequest
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
        // $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $req = $isUpdate ? 'sometimes' : 'required';

        return [
            'options' => 'nullable|array',
            'options.*.id' => 'nullable|exists:product_options,id',
            'options.*.option_text' => ['required', 'string', 'max:255'],
            'options.*.original_price' => ['required', 'numeric', 'min:0'],
            'options.*.price' => ['required', 'numeric', 'min:0',],
            'options.*.inventory' => ['required', 'integer', 'min:0',],
            'options.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'options.*.is_enabled' => ['required', 'boolean'],
            'deleted_ids' => 'nullable|array',
            'deleted_ids.*' => 'exists:product_options,id',

            // 'product_id' => [$req, 'integer', 'exists:products,id'],
            // 'option_text' => [$req, 'string', 'max:255'],
            // 'price' => [$req, 'numeric', 'min:0',],
            // 'original_price' => [$req, 'numeric', 'min:0'],
            // 'inventory' => ['nullable', 'integer', 'min:0',],
            // 'is_enabled' => [$req, 'boolean'],
            // 'image' => ['image', 'nullable', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // 'remove_image' => ['sometimes','boolean']
        ];
    }
}
