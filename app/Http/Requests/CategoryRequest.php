<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'name' => [
                $req,
                'string',
                'max:255',
                Rule::unique('categories', 'name')  //name不重複
                    ->ignore($this->route('category'))  //跳過更新中的這筆
            ],
            // 'sort_order' => [$req, 'integer', 'min:0'],
            'is_enabled' => [$req, 'boolean'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'name.required' => '請輸入類別名稱',
    //         'sort_order.integer' => '排序需為整數',
    //         'is_enabled.boolean' => '啟用狀態格式不正確',
    //     ];
    // }
    // public function attributes(): array
    // {
    //     return ['name' => '類別名稱', 'sort_order' => '排序', 'is_enabled' => '啟用狀態'];
    // }
}
