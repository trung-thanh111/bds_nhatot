<?php

namespace App\Http\Requests\RealEstate\ProjectCatalogue;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:project_catalogues,slug,' . $this->route('id'),
            'property_group_id' => 'nullable|integer|exists:project_property_groups,id',
            'publish' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên danh mục.',
            'slug.required' => 'Bạn chưa nhập đường dẫn.',
            'slug.unique' => 'Đường dẫn đã tồn tại.',
            'property_group_id.exists' => 'Nhóm BĐS không hợp lệ.',
        ];
    }
}
