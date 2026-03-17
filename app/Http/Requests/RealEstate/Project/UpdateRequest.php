<?php

namespace App\Http\Requests\RealEstate\Project;

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
            'slug' => 'required|string|max:255|unique:projects,slug,' . $this->route('id'),
            'catalogue_id' => 'required|integer|gt:0',
            'publish' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tiêu đề tin đăng.',
            'slug.required' => 'Bạn chưa nhập đường dẫn.',
            'slug.unique' => 'Đường dẫn đã tồn tại.',
            'catalogue_id.required' => 'Bạn chưa chọn danh mục dự án.',
            'catalogue_id.gt' => 'Bạn chưa chọn danh mục dự án.',
        ];
    }
}
