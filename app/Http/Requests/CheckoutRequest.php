<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'recipient_name' => ['required', 'string', 'max:30'],
            'shipping_email' => ['required', 'string','email'],
            'shipping_city' => ['required', 'string', 'max:20'],
            'shipping_district' => ['required', 'string', 'max:20'],
            'shipping_zip_code' => ['required', 'digits:3'],
            'shipping_address_detail' => ['required', 'string'],
            'recipient_phone' => ['required', 'string', 'regex:/^09\d{8}$/'],
            'note' => ['nullable', 'string', 'max:500'],
            // 'payment_method' => ['required','string']
        ];

        // $validated = $request->validate([
        // 'selected_ids' => 'required|array|min:1',
        // 'selected_ids.*' => 'integer|exists:product_options,id',
        // 'shipping_name' => ['required', 'string', 'max:30'],
        // 'shipping_email' => ['required', 'email'],
        // 'shipping_city' => ['required', 'string'],
        // 'shipping_district' => ['required', 'string'],
        // 'shipping_zip_code' => ['required', 'Number'],
        // 'shipping_address_detail' => ['required', 'string'],
        // 'shipping_phone' => ['required', 'string', 'max:10'],
        // 'note' => 'nullable|string|max:500',
        // 'address' => 'required|string|max:255',
        // 'phone' => 'required|string|max:20',
        // 'order_status' => 'required|integer',
        // 'payment_method' => 'required|string'
        // ]);
    }
}
