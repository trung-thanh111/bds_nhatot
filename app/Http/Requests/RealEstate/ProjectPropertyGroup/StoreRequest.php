<?php

namespace App\Http\Requests\RealEstate\ProjectPropertyGroup;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:project_property_groups,code',
            'publish' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên nhóm thuộc tính.',
            'code.required' => 'Bạn chưa nhập mã nhóm thuộc tính.',
            'code.unique' => 'Mã nhóm thuộc tính đã tồn tại.',
        ];
    }
}
