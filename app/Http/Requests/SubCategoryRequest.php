<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
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
            'category_id' => [$req, 'integer', 'exists:categories,id'],
            'name' => [
                $req,
                'string',
                'max:255',
                Rule::unique('subcategories', 'name')  //name不重複
                    ->ignore($this->route('subcategory'))  //跳過更新中的這筆
            ],
            // 'sort_order' => [$req, 'integer', 'min:0'],
            'is_enabled' => [$req, 'boolean'],
        ];
    }
}
