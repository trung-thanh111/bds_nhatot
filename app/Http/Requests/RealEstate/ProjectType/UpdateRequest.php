<?php

namespace App\Http\Requests\RealEstate\ProjectType;

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
            'code' => 'required|string|max:100|unique:project_types,code,' . $this->route('id'),
            'group_id' => 'required|integer|exists:project_property_groups,id',
            'publish' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên loại hình BĐS.',
            'name.max' => 'Tên loại hình BĐS không được vượt quá 255 ký tự.',
            'code.required' => 'Bạn chưa nhập mã loại hình BĐS.',
            'code.max' => 'Mã loại hình BĐS không được vượt quá 100 ký tự.',
            'code.unique' => 'Mã loại hình BĐS đã tồn tại.',
            'group_id.required' => 'Bạn chưa chọn nhóm BĐS.',
            'group_id.exists' => 'Nhóm BĐS không hợp lệ.',
        ];
    }
}
